<?php
/**
 * ----------------------
 * DocsController.class.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/4/15
 * Time: 16:08
 * ----------------------
 */
namespace Service\Controller;

use Anodoc;
use Think\Controller;

/**
 * 接口文档生成工具
 * @package Service\Controller
 * @version 0.2
 */
class DocsController extends Controller
{
    //过滤掉相关类文件
    private $fileFilter = array('BaseController','Docs');
    //过滤掉的方法
    private $methodFilter = array();
    //缓存接口信息
    private $classMethods = array();

    public function _initialize()
    {
        //parent::_initialize();
        $this->initData();
        //print_r($this->classMethods);exit;
    }

    public function index()
    {
        echo "API文档生成工具 <a href='".__CONTROLLER__."/build'>build</a><hr/>";
        echo "<li><a href='/apidocs'>API Doc</a></li>";
    }

    /**
     * 生成API文档
     * Html格式和PDF格式
     */
    public function build()
    {
        set_time_limit(0);
        $this->assign('docData', $this->classMethods);
        $this->assign('updateTime', date('Y-m-d l H:i', time()));
        //$this->display('index');
        //生成接口网页
        $this->buildHtml('index.html', APP_PATH.'/../Public/apidocs/', 'index');
        //创建pdf文档
        //$this->buildPdf();
        //打包Html
        $this->buildZip();
    }

    private function buildPdf()
    {
        require_once(APP_PATH."/../vendor/dompdf/dompdf/dompdf_config.inc.php");
        $dompdf = new \DOMPDF();
        $html = file_get_contents(APP_PATH.'/../Public/apidocs/index.html');
        $dompdf->load_html($html, 'UTF-8');
        $dompdf->render();
        $dompdf->stream("index.pdf");
    }

    private function buildZip()
    {
        $zip = new \ZipArchive();
        if ($zip->open(APP_PATH.'/../Public/apidocs/files/api.zip', \ZipArchive::OVERWRITE) === true) {
            //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $this->addFileToZip(APP_PATH.'/../Public/apidocs/', $zip);
            $zip->close(); //关闭处理的zip文件
        }
    }

    /**
     * 初始化数据
     * @throws Exception
     */
    private function initData()
    {
        $files = $this->getDirFiles(MODULE_PATH . "Controller", $this->fileFilter);
        foreach ($files as $classfile) {
            $class = __NAMESPACE__.'\\'.rtrim($classfile, '.class.php');
            $anodoc = Anodoc::getNew();
            $classDoc = $anodoc->getDoc($class);
            $classInfo = $classDoc->getMainDoc();

            $ref = new \ReflectionClass(new $class);

            $clsName = end(explode('\\', $class));
            $apiUrl = '/'.strtolower(explode('Controller', $clsName)[0]);

            $this->classMethods[$apiUrl] = array(
                'doc'       => $apiUrl,
                'docName'   => $classInfo->getShortDescription(),
                'docDesc'   => $classInfo->getLongDescription(),
                'package'   => $classInfo->getTagValue('package'),
                'author'    => $classInfo->getTagValue('author'),
                'date'      => $classInfo->getTagValue('date')
            );

            foreach ($ref->getMethods() as $method) {
                if ($method->isUserDefined() && $method->isPublic()) {
                    $apiFullUrl = $apiUrl.'/'.$method->name;
                    //排除掉两级父类的类属性
                    $pcls = $ref->getParentClass();
                    if ($pcls && $method->class == $pcls->name) {
                        continue;
                    }
                    if ($pcls && $pcls->getParentClass() && $method->class == $pcls->getParentClass()->name) {
                        continue;
                    }
                    $methodDoc = $classDoc->getMethodDoc($method->name);
                    //描述
                    //echo $methodDoc->getDescription();exit;
                    //echo $methodDoc->getLongDescription();exit;
                    //echo $methodDoc->getShortDescription();exit;
                    //参数
                    //print_r($methodDoc->getTagValue('param'));exit;
                    //print_r($methodDoc->getTags('param'));exit;
                    //返回信息
                    //echo $methodDoc->getTagValue('return');exit;
                    //echo $methodDoc->getTagValue('todo');exit;
                    $this->classMethods[$apiUrl]['methods'][] = array('method'=>$apiFullUrl,'methodDoc'=>$methodDoc);
                }
            }
        }
    }

    /**
     * 获取目录
     *
     * @param string $directory 目录路径
     * @return array
     * @throws Exception
     */
    private function getDirs($directory)
    {
        $files = array();
        try {
            $dir = new \DirectoryIterator($directory);
        } catch (Exception $e) {
            throw new Exception($directory . ' is not readable');
        }
        foreach ($dir as $file) {
            if ($file->isDot()) {
                continue;
            }
            if ($file->isFile()) {
                continue;
            }
            $files[] = $file->getFileName();
        }
        return $files;
    }

    /**
     * 获取目录中的文件
     * @param $dir
     * @param array $fileFilter 过滤掉的文件
     * @return array
     * @throws Exception
     */
    private function getDirFiles($dir, array $fileFilter = array())
    {
        $files = array();
        try {
            $dir = new \DirectoryIterator($dir);
        } catch (Exception $e) {
            throw new Exception($dir . ' is not readable');
        }
        foreach ($dir as $file) {
            if ($file->isDot()) {
                continue;
            }
            if ($file->isFile()) {
                $fileName = $file->getFilename();
                $isFiltered = false;
                foreach ($fileFilter as $v) {
                    if (false !== strpos($fileName, $v)) {
                        $isFiltered = true;
                        break;
                    }
                }
                if (!$isFiltered) {
                    $files[] = $file->getFileName();
                }
            }
        }
        return $files;
    }

    /**
     * 打包目录
     * if($zip->open('images.zip', ZipArchive::OVERWRITE)=== TRUE){
            addFileToZip('images/', $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); //关闭处理的zip文件
        }
     * @param $path
     * @param $zip
     */
    private function addFileToZip($path, $zip)
    {
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path."/".$filename)) {// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path."/".$filename, $zip);
                } else { //将文件加入zip对象
                    $zip->addFile($path."/".$filename);
                }
            }
        }
        @closedir($path);
    }
}
