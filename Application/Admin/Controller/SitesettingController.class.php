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
namespace Admin\Controller;

use Lib\Util;
use Think\Upload;

/**
 * Class SitesettingController
 * 系统设置-〉网站设置
 * @package Admin\Controller
 */
class SitesettingController extends AdminController
{
    //成功
    const __OK__ = 0;

    //配置保存失败
    const __ERROR__1 = 21001;

    //图片上传失败
    const __ERROR__2 = 21002;

    /**
     * 显示网站设置分组
     *  未分组的设置不显示
     */
    public function index()
    {
        $type   =   C('CONFIG_GROUP_LIST');
        $configModel = D('Config');
        $listTabData = array();
        foreach ($type as $groupId => $groupValue) {
            $list =  $configModel
                ->where(array('status'=>1,'group'=>$groupId))
                ->field('id,name,title,extra,value,remark,type')
                ->order('sort')
                ->select();
            $stdObj = array();
            $stdObj['groupId'] = $groupId;
            $stdObj['groupNamePix'] = $groupValue;
            $stdObj['config'] = $list;
            $listTabData[] = $stdObj;
        }
        if ($listTabData && count($listTabData) > 0) {
            $this->assign('listTabData', $listTabData);
        }
        $this->assign('postAction', U('save'));
        $this->assign('backAction', U('/Admin'));
        $this->display();
    }

    /**
     * 保存网站设置
     * @param $config
     */
    public function save($config)
    {
        $configModel = D('Config');
        $res = $configModel->saveConfigValue($config);
        if ($res) {
            exit(Util::response(self::__OK__, "保存成功!"));
        } else {
            exit(Util::response(self::__ERROR__1, "保存失败!"));
        }
    }

    public function picUploadHandler()
    {
        if (IS_POST) {
            $keys = array_keys($_FILES);
            $configName = $keys['0'];

            $upload = new Upload();
            //设置附件上传大小
            $upload->maxSize = 3145728 ;
            //设置附件上传类型
            $upload->exts =  array('jpg', 'gif', 'png', 'jpeg');
            //设置附件上传根目录
            $upload->rootPath  =  './uploads/';
            $info = $upload->upload();

            //上传错误提示错误信息
            if (!$info) {
                exit(Util::response(self::__ERROR__2, "上传失败!"));
            } else {
                $filePath = ltrim($upload->rootPath, '.').$info[$configName]['savepath'].$info[$configName]['savename'];
                $configModel = D('Config');
                $res = $configModel->getConfigByName($configName);
                $res['value'] = $filePath;
                if ($res) {
                    $updateRes = $configModel->saveConfigValue($res);
                    if ($updateRes) {
                        exit(Util::response(self::__OK__, "上传成功!"));
                    } else {
                        exit(Util::response(self::__ERROR__2, "上传失败!"));
                    }
                }
            }
        }
    }
}
