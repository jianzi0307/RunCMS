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
                if ($res) {
                    //注销
                    Util::setCookie('u', '', -1);
                    exit(Util::response(self::__OK__, '更新密码成功，请重新登录'));
                } else {
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
            $factoryname = Util::getSafeText(I('post.factoryname'));
            $factoryaddress = Util::getSafeText(I('post.factoryaddress'));
            $factoryscale = Util::getSafeText(I('post.factoryscale'));
            $maintechnology = Util::getSafeText(I('post.maintechnology'));
            $personliable = Util::getSafeText(I('post.personliable'));
            $personphone = Util::getSafeText(I('post.personphone'));
            $dutyname = Util::getSafeText(I('post.dutyname'));
            $regname = Util::getSafeText(I('post.regname'));
            $regdutyname = Util::getSafeText(I('post.regdutyname'));
            $regpersonphone = Util::getSafeText(I('post.regpersonphone'));


            $data = array(
                'uname' => $uname,
                'factoryname' => $factoryname,
                'factoryaddress' => $factoryaddress,
                'factoryscale' => $factoryscale,
                'maintechnology' => $maintechnology,
                'personliable' => $personliable,
                'personphone' => $personphone,
                'dutyname' => $dutyname,
                'regname' => $regname,
                'regdutyname' => $regdutyname,
                'regpersonphone' => $regpersonphone,
                'createtime' => time(),
                'expirtime' => time() + 100 * 12 * 30 * 24 * 3600
            );
            $res = $userModel->updateRows($data, intval($id));
            if ($res) {
                $authGroupAccessModel = D('AuthGroupAccess');
                $authGroupAccessModel->updateRows(array(
                ), array("uid" => intval($id)));
                exit(Util::response(self::__OK__, "修改用户信息成功!"));
            } else {
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
