<?php
/**
 * Created by PhpStorm.
 * User: jianzi0307
 * Date: 16/1/6
 * Time: 09:02
 */

namespace Home\Controller;

use Common\Controller\ServiceController;

class FindpwdController extends ServiceController
{
    public function index()
    {
        $this->assign("siteAppName", "爱都来");
        $this->assign("pageTitle", "找回密码");
        $this->display();
    }
}
