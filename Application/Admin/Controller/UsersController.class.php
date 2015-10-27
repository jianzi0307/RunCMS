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

class UsersController extends AdminController
{
    const __OK__ = 0;
    //参数错误
    const __ERROR__0 = 99999;
    //添加用户失败
    const __ERROR__1 = 60001;
    //修改用户失败
    const __ERROR__2 = 60002;
    //删除用户失败
    const __ERROR__3 = 60003;


    protected $db = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = M();
    }

    public function index()
    {
        $userList = $this->db->table('app_auth_group_access')
            ->field('app_useradmin.id, app_useradmin.uname, app_useradmin.nickname, app_auth_group.title, app_useradmin.blocked')
            ->join('app_useradmin ON app_auth_group_access.uid = app_useradmin.id')
            ->join('app_auth_group ON app_auth_group_access.group_id = app_auth_group.id', 'left')
            ->order('app_useradmin.id')
            ->select();

        $this->assign('userList', $userList);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            $uname = Util::getSafeText(trim(I('post.uname')));
            $userpwd = Util::getSafeText(I('post.passwd'));
            $userrepwd = Util::getSafeText(I('post.repwd'));
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
            $group = Util::getSafeText(I('post.group'));
            $blocked = Util::getSafeText(I('post.blocked')) ? Util::getSafeText(I('post.blocked')) : 1;
            //$expirtime = Util::getSafeText(trim(I('post.expirtime')));
            //$nickname = Util::getSafeText(trim(I('post.nickname')));
            if (empty($uname) || empty($userpwd) || empty($userrepwd) || ($userpwd !== $userrepwd)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'uname' => $uname,
                'passwd' => Util::genMd5Pwd($userpwd),
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
                'blocked' => $blocked,
                'createtime' => time(),
                'expirtime' => time() + 100 * 12 * 30 * 24 * 3600
            );
            $userModel = D('Useradmin');
            if ($userModel->where(array('uname'=>$uname))->find()) {
                exit(Util::response(self::__ERROR__2, "用户名已存在!"));
            }
            $res = $userModel->addRow($data);
            if ($res) {
                $authGroupAccessModel = D('AuthGroupAccess');
                $authGroupAccessModel->addRow(array(
                    'uid' => $res,
                    'group_id' => $group
                ));
                exit(Util::response(self::__OK__, "添加用户成功!"));
            } else {
                exit(Util::response(self::__ERROR__1, "添加用户失败!"));
            }
        } else {
            $authGroupModel = D('AuthGroup');
            $groups = $authGroupModel->getAll();
            $this->assign('groups', $groups);
            $this->assign('isEdit', false);
            $this->assign('addAction', U('add'));
            $this->pageTitle("添加用户");
            $this->display();
        }
    }

    public function edit($id = 0)
    {
        $userModel = D('Useradmin');
        if (IS_POST) {
            $uname = Util::getSafeText(trim(I('post.uname')));
            $userpwd = Util::getSafeText(I('post.passwd'));
            $userrepwd = Util::getSafeText(I('post.repwd'));
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
            $group = Util::getSafeText(I('post.group')) ? Util::getSafeText(I('post.group')) : 0;
            $blocked = Util::getSafeText(I('post.blocked'));
            //$expirtime = Util::getSafeText(trim(I('post.expirtime')));
            //$nickname = Util::getSafeText(trim(I('post.nickname')));

//            if (empty($uname) || empty($userpwd) || empty($userrepwd) || empty($group) || ($userpwd !== $userrepwd)) {
//                exit(Util::response(self::__ERROR__0, "参数错误!"));
//            }

            $data = array(
                'uname' => $uname,
                'passwd' => Util::genMd5Pwd($userpwd),
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
                'blocked' => $blocked,
                'createtime' => time(),
                'expirtime' => time() + 100 * 12 * 30 * 24 * 3600
            );
            if (!$userpwd) {
                unset($data['passwd']);
            }
            $res = $userModel->updateRows($data, intval($id));
            $userAdminModel = D('Useradmin');
            if ($res) {
                $authGroupAccessModel = D('AuthGroupAccess');
                $authGroupAccessModel->updateRows(array(
                    'group_id' => $group
                ), array("uid" => intval($id)));
                $userAdminModel->updateUserInfo($id);
                exit(Util::response(self::__OK__, "修改用户成功!"));
            } else {
                exit(Util::response(self::__ERROR__2, "修改用户失败!"));
            }
        } else {
            $id = intval(Util::getSafeText(I('get.id')));

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
            $this->pageTitle("修改用户");

            $this->display('add');
        }
    }

    public function del()
    {
        $ids = array_unique((array) Util::getSafeText(I('id', 0)));
        $userAdminModel = D('Useradmin');
        $res = $userAdminModel->delRowsInIds($ids);
        if ($res) {
            //$this->success('删除用户成功!');
            exit(Util::response(self::__OK__, "删除用户成功!"));
        } else {
            exit(Util::response(self::__ERROR__3, "删除用户失败!"));
        }
    }
}
