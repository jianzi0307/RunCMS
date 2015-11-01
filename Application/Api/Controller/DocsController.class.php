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
namespace Api\Controller;

use Anodoc;
use Think\Controller;

/**
 * Class DocsController
 * 接口文档生成 + HTTP测试工具
 *
 * @package Api\Controller
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

    /**
     * 首页
     */
    public function index()
    {
        $this->assign('docData', $this->classMethods);
        $this->display('index');
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
     * 文本入库前的过滤工作
     *
     * @param $textString
     * @param bool $htmlspecialchars
     * @return string
     */
    private function getSafeText($textString, $htmlspecialchars = true)
    {
        return $htmlspecialchars
            ? htmlspecialchars(trim(strip_tags($this->qj2bj($textString))))
            : trim(strip_tags($this->qj2bj($textString)))
            ;
    }

    /**
     * 全角 => 半角
     *
     * @param string $string
     * @return string
     */
    private function qj2bj($string)
    {
        $convert_table = array(
            '０' => '0','１' => '1','２' => '2','３' => '3','４' => '4',
            '５' => '5','６' => '6','７' => '7','８' => '8','９' => '9',
            'Ａ' => 'A','Ｂ' => 'B','Ｃ' => 'C','Ｄ' => 'D','Ｅ' => 'E',
            'Ｆ' => 'F','Ｇ' => 'G','Ｈ' => 'H','Ｉ' => 'I','Ｊ' => 'J',
            'Ｋ' => 'K','Ｌ' => 'L','Ｍ' => 'M','Ｎ' => 'N','Ｏ' => 'O',
            'Ｐ' => 'P','Ｑ' => 'Q','Ｒ' => 'R','Ｓ' => 'S','Ｔ' => 'T',
            'Ｕ' => 'U','Ｖ' => 'V','Ｗ' => 'W','Ｘ' => 'X','Ｙ' => 'Y',
            'Ｚ' => 'Z','ａ' => 'a','ｂ' => 'b','ｃ' => 'c','ｄ' => 'd',
            'ｅ' => 'e','ｆ' => 'f','ｇ' => 'g','ｈ' => 'h','ｉ' => 'i',
            'ｊ' => 'j','ｋ' => 'k','ｌ' => 'l','ｍ' => 'm','ｎ' => 'n',
            'ｏ' => 'o','ｐ' => 'p','ｑ' => 'q','ｒ' => 'r','ｓ' => 's',
            'ｔ' => 't','ｕ' => 'u','ｖ' => 'v','ｗ' => 'w','ｘ' => 'x',
            'ｙ' => 'y','ｚ' => 'z','　' => ' ','：' => ':','。' => '.',
            '？' => '?','，' => ',','／' => '/','；' => ';','［' => '[',
            '］' => ']','｜' => '|','＃' => '#',
        );
        return strtr($string, $convert_table);
    }
}
