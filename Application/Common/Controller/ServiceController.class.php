<?php
/**
 * ----------------------
 * BaseController.class.php
 *
 * Date: 2015/4/15
 * Time: 13:08
 * ----------------------
 */
namespace Common\Controller;

use Common\Model\CacheModel;
use Lib\Util;

/**
 * Class ServiceController
 * API服务控制器基类
 * @package Common\Controller
 */
class ServiceController extends RestfulController
{
    const __ILLEGAL_ERROR_1 = -1;//非法操作
    const __TOKEN_ERROR_0 = -2;//token错误
    const __TOKEN_ERROR_1 = -3;//token过期

    /**
     * mc操作句柄
     * @var
     */
    protected $mc;

    /**
     * 响应的数据格式 json、xml、php
     * @var string
     */
    protected $format = 'json';

    /**
     * 服务器当前时间戳
     * @var
     */
    protected $time;

    public function _initialize()
    {
        //当前服务器时间
        $this->time = time();
        //缓存句柄
        $this->mc = CacheModel::getMc('mcMain');
        //判断要返回的数据格式 json、xml、php
        $format = I('get.format');
        if (isset($format)) {
            $this->format = strtolower(Util::getSafeText(trim($format)));
            if (!array_key_exists($this->format, $this->allowResponseFormat)) {
                $this->format = 'json';
            }
        }
        //不支持的请求方法
        if (!in_array(strtolower(REQUEST_METHOD), $this->allowRequestMethod)) {
            $this->invalidRequestMethod();
        }
    }

    /**
     * 响应无效的请求方法
     * Http Status 405
     */
    protected function invalidRequestMethod()
    {
        $resData = $this->responseData(self::__ILLEGAL_ERROR_1, 'Invalid request method!');
        $this->response(405, $resData, $this->format);
    }

    /**
     * 检查token
     * @param $token
     * @return array|null
     */
    public function _checkToken($token)
    {
        $retBody = array('errno' => 0, 'errmsg'=>'ok');
        if (!$token || empty($token)) {
            return null;
        }
        //分析token
        $auth = $this->_analysisToken($token);
        if (empty($auth)) {
            $retBody['errno'] = self::__TOKEN_ERROR_0;
            $retBody['errmsg'] = "Invalid token.";
        } elseif ($auth == -1) {
            $retBody['errno'] = self::__TOKEN_ERROR_1;
            $retBody['errmsg'] = "Expired token.";
        }
        return array_merge($retBody, array('auth'=>$auth));
    }

    /**
     * 分析token
     * @param $token
     * @return array|int|null
     */
    public function _analysisToken($token)
    {
        //解密
        $arr = explode('_', Util::aesDecode($token));
        //验证码错误
        if (count($arr)<3 || $arr[2] !== C('TOKEN_DISTURB_CODE')) {
            return null;
        }
        //验证码过期，一周失效
        if ($arr[1] < $this->time-C('TOKEN_EXPIRES_IN')) {
            return -1;
        }
        //24小时续期
//        println('当前服务器时间：'.$this->time,false);
//        println('时间差：'$this->time-$arr[1],false);
//        print_r(C('TOKEN_REFRESH_TIME'));die;
        if ($this->time-$arr[1] > C('TOKEN_REFRESH_TIME')) {
            return array('userId'=>$arr[0],'mktime' =>$arr[1], 'refresh_token' =>$this->_createToken($arr[0]));
        }
        return array('userId'=>$arr[0],'mktime'=>$arr[1]);
    }

    /**
     * 生成Token
     * @param $userId
     * @return mixed
     */
    public function _createToken($userId)
    {
        return Util::aesEncode($userId . '_' . $this->time . '_' . C('TOKEN_DISTURB_CODE'));
    }

    /**
     * 自动判断返回的数据格式
     * @param int $code
     * @param null $data
     */
    public function response($code = 200, $data = null)
    {
        parent::response($code, $data, $this->format);
    }

    /**
     * 响应的数据
     * {
     *      "errno" => 0,
     *      "errmsg" => "ok",
     *      "data" => {
     *          ...
     *      }
     * }
     * @param int $errno
     * @param string $errmsg
     * @param null $data
     * @return array|null
     */
    protected function responseData($errno = 0, $errmsg = 'ok', $data = null)
    {
        $res = array("errno"=>$errno, 'errmsg' => $errmsg);
        $data = array_merge($res, array('data'=>$data));
        return $data;
    }
}
