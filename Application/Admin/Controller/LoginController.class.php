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
use Lib\Captcha;
use Think\Controller;

/**
 * Class LoginController
 *
 * 登录控制器
 * @package Admin\Controller
 */
class LoginController extends BaseController
{
    //账号不存在
    const __ERROR__1 = 10001;
    //用户名或密码错误
    const __ERROR__2 = 10002;
    //用户被禁用
    const __ERROR__3 = 10003;
    //用户已经过期
    const __ERROR__4 = 10004;
    //验证码为空
    const __ERROR__5 = 10005;
    //验证码错误
    const __ERROR__6 = 10006;
    //成功
    const __OK__ = 0;
    //其他未知错误
    const __UNKOWN__ = -1;

    private $captcha;
    private $logs;

    public function _initialize()
    {
        parent::_initialize();

        $this->captcha = new Captcha();//Captcha::getInstance();
        $this->logs = D('Logs');
    }

    /**
     * 登录界面
     */
    public function index()
    {
        //判断是否已经登录，已登录跳转到管理首页
        $user = D('Useradmin');
        if ($user->isLogin()) {
            exit($this->success('已经登录!', '/Admin'));
        }
        $this->display();
    }

    /**
     * 显示验证码
     */
    public function showCaptcha()
    {
        $_SESSION['_phrase'] = $this->captcha->getPhrase();
        echo $this->captcha->inline();
    }

    /**
     * 登录验证
     */
    public function loginAuth()
    {
        $uname = Util::getSafeText(trim(I('post.username')));
        $upasswd = I('post.password');
        $captcha = Util::getSafeText(trim(I('post.captcha')));
        if (empty($captcha)) {
            exit(Util::response(self::__ERROR__5, "验证码不能为空"));
        }
        $this->captcha->setPhrase($_SESSION['_phrase']);
        $chkCaptcha = $this->captcha->testPhrase($captcha);
        if (!$chkCaptcha) {
            exit(Util::response(self::__ERROR__6, "验证码错误"));
        }
        if ($uname && $upasswd) {
            $uname = Util::getSafeText($uname);
            $upasswd = Util::getSafeText($upasswd);

            $userModel = D('Useradmin');
            $authRes = $userModel->userLogin($uname, $upasswd);
            $this->logs = $this->logs
                ->setUser($uname)
                ->action(LogsModel::ACT_LOGIN)
                ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
                ->exec($userModel->_sql());
            if ($authRes > 0) {
                $this->logs->ok();
                exit(Util::response(self::__OK__));
            }
            $this->logs->fail();
            if ($authRes == -1) {
                exit(Util::response(self::__ERROR__1, "账号不存在"));
            }
            if ($authRes == -2) {
                exit(Util::response(self::__ERROR__2, "用户名或密码错误"));
            }
            if ($authRes == -3) {
                exit(Util::response(self::__ERROR__3, "该用户审核中，请联系管理员"));
            }
            if ($authRes == -4) {
                exit(Util::response(self::__ERROR__4, "该账号已过期，请联系系统管理员"));
            }
            exit(Util::response(self::__UNKOWN__, "未知错误"));
        }
    }

    public function regedit()
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
                exit(Util::response(self::__OK__, "注册成功，等待管理员审核!"));
            } else {
                exit(Util::response(self::__ERROR__1, "注册失败!"));
            }
        } else {
            $authGroupModel = D('AuthGroup');
            $groups = $authGroupModel->getAll();
            $this->assign('groups', $groups);
            $this->assign('isEdit', false);
            $this->assign('addAction', U('regedit'));
            $this->display();
        }
    }
}
