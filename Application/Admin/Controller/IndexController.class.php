<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * Class IndexController
 *
 * 系统首页
 * @package Admin\Controller
 */
class IndexController extends AdminController
{
    /**
     * 我的面板
     */
    public function index()
    {

        $this->display();
    }
}
