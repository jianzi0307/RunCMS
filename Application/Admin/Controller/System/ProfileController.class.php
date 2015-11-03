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
namespace Admin\Controller\System;

use Admin\Model\LogsModel;
use Lib\Util;

/**
 * Class ProfileController
 * 用户资料管理
 * @package Admin\Controller
 */
class ProfileController extends AdminController
{
    //更新密码成功
    const __OK__ = 0;
    //两次输入密码不相同，请重新输入
    const __ERROR__1 = 80001;
    //原密码校验错误
    const __ERROR__2 = 80002;
    //该用户不存在
    const __ERROR__3 = 80003;
    //更新密码失败
    const __ERROR__4 = 80004;

    public function index()
    {
        $this->display();
    }

    /**
     * 提供修改密码功能
     */
    public function pwd()
    {
        if (IS_POST) {
            $oldpwd = Util::getSafeText(trim(I('post.oldpwd')));
            $pwd = Util::getSafeText(trim(I('post.pwd')));
            $repwd = Util::getSafeText(trim(I('post.repwd')));
            if ($pwd != $repwd) {
                exit(Util::response(self::__ERROR__1, '两次输入密码不相同，请重新输入'));
            }
            //检查旧密码
            $userAdminModel = D('Useradmin');
            $row = $userAdminModel->getRow(array("id = {$this->userId}"));
            if ($row) {
                if (Util::genMd5Pwd($oldpwd) !== $row['passwd']) {
                    exit(Util::response(self::__ERROR__2, '原密码校验错误'));
                }
                $res = $userAdminModel->updatePwd($pwd);

                $this->logWriter = $this->logWriter
                    ->action(LogsModel::ACT_UPDATE)
                    ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
                    ->exec($userAdminModel->_sql());

                if ($res) {
                    $this->logWriter->ok();
                    //注销
                    Util::setCookie('u', '', -1);
                    exit(Util::response(self::__OK__, '更新密码成功，请重新登录'));
                } else {
                    $this->logWriter->fail();
                    exit(Util::response(self::__ERROR__4, '更新密码失败'));
                }
            } else {
                exit(Util::response(self::__ERROR__3, '该用户不存在'));
            }
        } else {
            $this->pageTitle('修改密码');
            $this->display();
        }
    }

    /**
     * 提供修改用户资料功能
     */
    public function userinfo()
    {
        $id = $this->userId;
        $userModel = D('Useradmin');
        if (IS_POST) {
            $uname = Util::getSafeText(trim(I('post.uname')));

            $data = array(
                'uname' => $uname,
                'createtime' => time(),
                'expirtime' => time() + 100 * 12 * 30 * 24 * 3600
            );
            $res = $userModel->updateRows($data, intval($id));

            $this->logWriter = $this->logWriter
                ->action(LogsModel::ACT_UPDATE)
                ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
                ->exec($userModel->_sql());

            if ($res) {
                $authGroupAccessModel = D('AuthGroupAccess');
                $authGroupAccessModel->updateRows(array(
                ), array("uid" => intval($id)));
                $this->logWriter->ok();
                exit(Util::response(self::__OK__, "修改用户信息成功!"));
            } else {
                $this->logWriter->fail();
                exit(Util::response(self::__ERROR__2, "修改用户信息失败!"));
            }
        } else {
            $authGroupModel = D('AuthGroup');
            $groups = $authGroupModel->getAll();
            $this->assign('groups', $groups);

            $authGroupAccessModel = D('AuthGroupAccess');
            $row = $authGroupAccessModel->getRow(array("uid={$id}"));
            $group_id = $row['group_id'];
            $this->assign('groupId', $group_id);

            $map = array("id={$id}");
            $user = $userModel->getRow($map);
            $this->assign('user', $user);

            $this->assign('isEdit', true);
            $this->pageTitle("修改用户信息");

            $this->display();
        }
    }
}
