<?php
/**
 * ----------------------
 * ConfsettingController.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/5/27
 * Time: 14:01
 * ----------------------
 */
namespace Admin\Model;

use Think\Model;

/**
 * Class FileModel
 *
 * 文件模型，文件处理
 * @package Admin\Model
 */
class FileModel
{

    //默认生成的模块目录名
    protected $moduleDir = array(
            'Common','Controller','Model','View','Conf'
        );

    /**
     * 生成模块目录结构
     * @return array
     */
    public function getModules()
    {
        $ignoreList = array("Common", "Runtime", "Admin");
        $allFileList = $this->getDirs(APP_PATH);
        return array_diff($allFileList, $ignoreList);
    }

    /**
     * 创建模块目录
     * -1 模块已存在
     * -2 目录不可写
     * 1 创建成功
     *
     * @param string $moduleName 模块名
     * @return int
     */
    public function createModuleDir($moduleName)
    {
        $modulePath = APP_PATH.$moduleName;
        //echo $modulePath;
        if (file_exists($modulePath) && is_dir($modulePath)) {
            return -1;
        }
        if (!is_writable(APP_PATH)) {
            return -2;
        }
        mkdir($modulePath);
        foreach ($this->moduleDir as $dir) {
            $subDir = $modulePath.'/'.$dir;
            if (file_exists($subDir)) {
                continue;
            }
            if (is_writable($modulePath)) {
                mkdir($subDir);
            }
        }
        return 1;
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
}
