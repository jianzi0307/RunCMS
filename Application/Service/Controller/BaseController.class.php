<?php
/**
 * ----------------------
 * BaseController.class.php
 *
 * Date: 2015/4/15
 * Time: 13:08
 * ----------------------
 */
namespace Service\Controller;

use Lib\Utils;
use Think\Controller;

class BaseController extends Controller
{
    const __TOKEN_ERROR_0 = -2;//token错误
    const __TOKEN_ERROR_1 = -3;//token过期
    const __ILLEGAL_ERROR_1 = -1;//非法操作

    /**
     * 服务器当前时间戳
     * @var
     */
    protected $time;

    public function _initialize()
    {
        $this->time = time();
    }


    /**
     * 获取当前登录的用户信息
     * @param string $token
     * @return array
     */
    public function getUserInfo($token)
    {
        $token = $this->_checkToken($token);
        if ($token) {
            $userModel = M('UserModel');
            return $userInfo  = $userModel->getUserById($token['userId']);
        }
        return null;
    }

    /**
     * 检查token
     * @param $token
     * @return array|null
     */
    public function _checkToken($token)
    {
        $retBody = array('errno'=>0, 'errmsg'=>'成功');
        if (!$token || empty($token)) {
            return null;
        }
        //分析token
        $token = $this->_analysisToken($token);
        if (empty($token)) {
            $retBody['errno'] = self::__TOKEN_ERROR_0;
            $retBody['errmsg'] = "token错误";
        } elseif ($token == -1) {
            $retBody['errno'] = self::__TOKEN_ERROR_1;
            $retBody['errmsg'] = "token过期";
        }
        return array_merge($retBody, array('token'=>$token));
    }

    /**
     * 分析token
     * @param $token
     * @return array|int|null
     */
    public function _analysisToken($token)
    {
        //解密
        $arr = explode('_', Utils::aesDecode($token));
        //验证码错误
        if (count($arr)<3 || $arr[2] !== C('TOKEN_DISTURB_KEY')) {
            return null;
        }
        //验证码过期，最长30分钟，最短20分钟无操作过期
        if ($arr[1] < $this->time-C('TOKEN_TIMEOUT')) {
            return -1;
        }
        //10分钟续期一次
        if ($arr[1] < $this->time-C('TOKEN_RENEW_TIME')) {
            return array('userId'=>$arr[0],'time' =>$arr[1],'token' =>$this->_createToken($arr[0]));
        }
        return array('userId'=>$arr[0],'time'=>$arr[1]);
    }

    /**
     * 生成Token
     * @param $userId
     * @return mixed
     */
    public function _createToken($userId)
    {
        return Utils::aesEncode($userId . '_' . $this->time . '_' . C('TOKEN_DISTURB_KEY'));
    }
}