<?php
/**
 * Created by PhpStorm.
 * User: jianzi0307
 * Date: 16/1/5
 * Time: 22:58
 */

namespace Home\Controller;

use Common\Controller\ServiceController;

class RegisterController extends ServiceController
{
    public function index()
    {
        $this->assign("siteAppName", "爱都来");
        $this->assign("pageTitle", "用户注册");
        $this->display();
    }
}
