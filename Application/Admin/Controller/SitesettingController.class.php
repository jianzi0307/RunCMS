<?php
namespace Admin\Controller;

use Lib\Util;

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
}
