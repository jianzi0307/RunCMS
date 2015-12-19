<?php
/**
 * ----------------------
 * BaseController.class.php
 * 
 * User: jian0307@icloud.com
 * Date: 2015/11/5
 * Time: 12:14
 * ----------------------
 */
namespace Service\Controller;

use Common\Controller\ServiceController;

class BaseController extends ServiceController
{
    /**
     * 日志
     * @var \Katzgrau\KLogger\Logger
     */
    protected $logger;

    /**
     * 请求的JSON数据
     * @var string
     */
    protected $requestBody = null;

    public function _initialize()
    {
        parent::_initialize();
        if (!$_GET['buildApi']) {
            $this->requestBody = json_decode(file_get_contents("php://input"), true);
            if (is_null($this->requestBody)) {
                $resData = $this->responseData(-1, '请求参数错误！');
                $this->response(400, $resData);
            }
        }
    }

    /**
     * 判断是否需要刷新token
     * @param $auth
     * @param $accessToken
     * @return array
     */
    protected function getRefreshToken($auth, $accessToken)
    {
        //判断是否需要续期token
        $refreshToken = $auth['refresh_token'];
        if (!empty($refreshToken)) {
            $expire = time() + C('TOKEN_EXPIRES_IN');
            $tokenCache = $this->getTokenCache($auth['userId'], $accessToken);
            $tokenCache->access_token = $refreshToken;
            $tokenCache->{expired-at} = $expire;
            $this->mc->set('token_'.$auth['userId'], json_encode($tokenCache));
            return array('refresh_token'=>$refreshToken,'expired-at'=>$expire);
        }
    }

    /**
     * 验证token合法性
     * @param $accessToken
     * @return mixed
     */
    protected function checkTokenValid($accessToken)
    {
        if (empty($accessToken)) {
            $resData = $this->responseData(-1, 'Empty token.');
            $this->response(400, $resData);
        }
        $chkRes = $this->_checkToken($accessToken);
        if (null == $chkRes) {
            $resData = $this->responseData(-2, 'Invalid token.');
            $this->response(403, $resData);
        }

        $auth = $chkRes['auth'];

        //响应1、token错误 2、超过一周未登录，token过期，需要重新登录
        if (empty($auth) || $auth == -1) {
            $this->response(403, $chkRes);
        }
        return $auth;
    }

    /**
     * 获取缓存的Token对象
     * @param string $jdyUserId
     * @param string $accessToken
     * @return mixed
     */
    protected function getTokenCache($jdyUserId, $accessToken)
    {
        $tokenCache = json_decode($this->mc->get('token_' . $jdyUserId));
        //token不存在，非法操作或者未曾登录，提示登录
        if (!$tokenCache) {
            $resData = $this->responseData(-2, 'Invalid token error.');
            $this->response(403, $resData);
        }
        //验证token
        if ($tokenCache->access_token != $accessToken) {
            $resData = $this->responseData(-2, 'Invalid token error.');
            $this->response(403, $resData);
        }
        return $tokenCache;
    }

    /**
     * 普通日志
     * @param $api
     * @param $msg
     */
    protected function infoLogs($api, $msg)
    {
        $this->logger->info($api. ":: ".$msg);
    }

    /**
     * 错误日志
     * @param string $api 方法名
     * @param string $msg 信息
     */
    protected function errorLogs($api, $msg)
    {
        $this->logger->error($api. ":: ". $msg);
    }
}
