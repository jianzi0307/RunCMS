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

use Admin\Model\LogsModel;
use Lib\Util;
use Think\Controller;

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
        //记录日志
        D('Logs')
            ->action(LogsModel::ACT_LOGOUT)
            ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
            ->ok();

        Util::setCookie('u', '', -1);
        $this->assign('waitSecond', 0);
        //exit($this->success('退出成功!', U('/Admin/Login')));
        exit($this->redirect(U('/Admin/Login'), null, 0, '退出成功!'));
    }
}
