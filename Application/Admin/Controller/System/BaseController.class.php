<?php
/**
 * ----------------------
 * BaseController.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/5/27
 * Time: 10:12
 * ----------------------
 */
namespace Admin\Controller\System;

use Think\Controller;

/**
 * Class BaseController
 * @package Admin\Controller
 */
class BaseController extends Controller
{
    public function _initialize()
    {
        //从数据库读取配置
        $configModel = D('Config');
        $config = $configModel->cacheConfig();
        C($config);
    }

    /**
     * 设置页标题
     * @param $title
     */
    public function pageTitle($title)
    {
        $this->assign('pageTitle', $title);
    }

    /**
     * 设置面包屑导行
     * @param $nav
     */
    public function nav($nav)
    {
        $this->assign('nav', $nav);
    }

    /**
     * 每个页面可以设置一个子标题
     * @param $title
     */
    public function subTitle($title)
    {
        $this->assign('subTitle', $title);
    }
}
