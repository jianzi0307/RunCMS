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
use Lib\Util;

/**
 * Class LogoutController
 *
 * 注销账号
 * @package Admin\Controller
 */
class LogoutController extends BaseController
{
    /**
     * 退出
     */
    public function index()
    {
        Util::setCookie('u', '', -1);
        $this->assign('waitSecond', 0);
        //exit($this->success('退出成功!', U('/Admin/Login')));
        exit($this->redirect(U('/Admin/Login'), null, 0, '退出成功!'));
    }
}
