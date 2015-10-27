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
