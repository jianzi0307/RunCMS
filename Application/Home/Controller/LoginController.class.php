<?php
/**
 * Created by PhpStorm.
 * User: jianzi0307
 * Date: 16/1/5
 * Time: 14:00
 */

namespace Home\Controller;

use Common\Controller\ServiceController;

class LoginController extends ServiceController
{
    public function index()
    {
        $this->assign("siteAppName", "爱都来");
        $this->assign("pageTitle", "用户登录");
        $this->assign('returnUrl', urlencode('http://www.'.C('SITE_DOMAIN')));
        $this->display();
    }
}
