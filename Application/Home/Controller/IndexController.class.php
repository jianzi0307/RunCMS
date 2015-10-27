<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        //跳转到后台
        $this->redirect(U('/Admin/Login'), null, 0);
    }
}
