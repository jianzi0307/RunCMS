<?php
/**
 * Created by PhpStorm.
 * User: jianzi0307
 * Date: 15/11/4
 * Time: 下午6:34
 */
namespace Common\Controller;

use Think\Controller;

class RestfulController extends Controller
{
    const REQUEST_METHOD_GET = 'get';
    const REQUEST_METHOD_POST = 'post';
    const REQUEST_METHOD_PUT = 'put';
    const REQUEST_METHOD_DELETE = 'delete';

    const REQUEST_RESOURCE_JSON = 'json';
    const REQUEST_RESOURCE_XML = 'xml';
    const REQUEST_RESOURCE_PHP = 'php';
    const REQUEST_RESOURCE_JSONP = 'jsonp';

    public static $mimeType = array(
        'html'  =>  'text/html,application/xhtml+xml,*/*',
        'xml'   =>  'application/xml,text/xml,application/x-xml',
        'json'  =>  'application/json,text/x-json,application/jsonrequest,text/json',
        'js'    =>  'text/javascript,application/javascript,application/x-javascript',
        'css'   =>  'text/css',
        'rss'   =>  'application/rss+xml',
        'yaml'  =>  'application/x-yaml,text/yaml',
        'atom'  =>  'application/atom+xml',
        'pdf'   =>  'application/pdf',
        'text'  =>  'text/plain',
        'png'   =>  'image/png',
        'jpg'   =>  'image/jpg,image/jpeg,image/pjpeg',
        'gif'   =>  'image/gif',
        'csv'   =>  'text/csv'
    );

    public static $httpStatus = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );

    /**
     * 当前请求方法
     * @var string
     */
    protected $requestMethod = '';

    /**
     * 当前请求资源类型
     * @var string
     */
    protected $requestResourceType = '';

    /**
     * 默认请求方法
     * @var string
     */
    protected $defaultRequestMethod = self::REQUEST_METHOD_GET;

    /**
     * 默认请求的资源类型
     * @var string
     */
    protected $defaultResourceType = self::REQUEST_RESOURCE_JSON;

    /**
     * 允许的请求方法
     * @var array
     */
    protected $allowRequestMethod = array(
        self::REQUEST_METHOD_GET,
        self::REQUEST_METHOD_POST,
        self::REQUEST_METHOD_PUT,
        self::REQUEST_METHOD_DELETE
    );

    /**
     * 允许请求的资源类型
     * @var array
     */
    protected $allowRequestResourceType = array(
        self::REQUEST_RESOURCE_JSON,
        self::REQUEST_RESOURCE_XML,
        self::REQUEST_RESOURCE_PHP,
        self::REQUEST_RESOURCE_JSONP
    );

    /**
     * 允许响应数据格式
     * @var array
     */
    protected $allowResponseFormat = array(
        self::REQUEST_RESOURCE_XML => 'application/xml',
        self::REQUEST_RESOURCE_JSON => 'application/json',
        self::REQUEST_RESOURCE_PHP => 'text/plain',
        self::REQUEST_RESOURCE_JSONP => 'application/json'
    );

    public function __construct()
    {
        //资源类型检测
        if ('' == __EXT__) {
            //自动检测资源类型
            $this->requestResourceType = $this->getContentType();
        } elseif (!in_array(__EXT__, $this->allowRequestResourceType)) {
            //资源类型非法 则用默认资源类型访问
            $this->requestResourceType = $this->defaultResourceType;
        } else {
            // 检测实际资源类型
            $this->requestResourceType   =  ($this->getContentType() == __EXT__)
                ? __EXT__
                : $this->defaultResourceType;
        }

        // 请求方式检测
        $method = strtolower(REQUEST_METHOD);
        if (!in_array($method, $this->allowRequestMethod)) {
            // 请求方式非法 则用默认请求方法
            $method = $this->defaultRequestMethod;
        }
        $this->requestMethod = $method;

        parent::__construct();
    }

    /**
     * 魔术方法 有不存在的操作的时候执行
     * @param string $method
     * @param array $args
     * @return mixed|void
     */
    public function __call($method, $args)
    {
        if (0 === strcasecmp($method, ACTION_NAME.C('ACTION_SUFFIX'))) {
            if (method_exists($this, $method.'_'.$this->requestMethod.'_'.$this->requestResourceType)) {
                $fun  =  $method.'_'.$this->requestMethod.'_'.$this->requestResourceType;
                $this->$fun();
            } elseif ($this->requestMethod == $this->defaultRequestMethod
                && method_exists($this, $method.'_'.$this->requestResourceType)) {
                $fun = $method.'_'.$this->requestResourceType;
                $this->$fun();
            } elseif ($this->requestResourceType == $this->defaultResourceType
                && method_exists($this, $method.'_'.$this->requestMethod)) {
                $fun  =  $method.'_'.$this->requestMethod;
                $this->$fun();
            } elseif (method_exists($this, '_empty')) {
                //如果定义了_empty操作 则调用
                $this->_empty($method, $args);
            } elseif (file_exists_case($this->view->parseTemplate())) {
                //检查是否存在默认模版 如果有直接输出模版
                $this->display();
            } else {
                E(L('_ERROR_ACTION_').':'.ACTION_NAME);
            }
        }
    }

    /**
     * 设置页面输出的CONTENT_TYPE和编码
     * @param $type
     * @param string $charset
     */
    public function setContentType($type, $charset = '')
    {
        if (headers_sent()) {
            return;
        }
        if (empty($charset)) {
            $charset = C('DEFAULT_CHARSET');
        }
        $type = strtolower($type);
        $format = $this->allowResponseFormat[$type];
        if (isset($format)) {
            //过滤content_type
            header('Content-Type: ' . $format . '; charset=' . $charset);
        }
    }

    /**
     * 获取当前请求的Accept头信息
     * @return bool|int|string
     */
    public function getContentType()
    {
        $httpAccept = $_SERVER['HTTP_ACCEPT'];
        foreach (self::$mimeType as $key => $val) {
            $array = explode(',', $val);
            foreach ($array as $k => $v) {
                if (stristr($httpAccept, $v)) {
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * 响应
     * @param int $code
     * @param null $data
     * @param string $type
     */
    protected function response($code = 200, $data = null, $type = 'json')
    {
        $formatType = strtolower($type);
        if (!array_key_exists($formatType, $this->allowResponseFormat)) {
            $formatType = $this->defaultResourceType;
        }
        $this->sendHttpStatus($code);
        exit($this->dataFormat($data, $formatType));
    }

    /**
     * 格式化响应数据
     * @param mixed $data
     * @param string $type
     * @return string
     */
    protected function dataFormat($data, $type)
    {
        if (empty($data)) {
            return '';
        }
        if (self::REQUEST_RESOURCE_JSON == $type) {
            //返回JSON数据格式到客户端 包含状态信息
            $data = json_encode($data);
        } elseif (self::REQUEST_RESOURCE_XML == $type) {
            //返回xml格式数据
            $data = xml_encode($data, 'root');
        } elseif (self::REQUEST_RESOURCE_PHP == $type) {
            $data = serialize($data);
        } elseif (self::REQUEST_RESOURCE_JSONP == $type) {
            $callback = trim($_GET['callback']);
            if (empty($callback)) {
                $this->sendHttpStatus(400);
                exit('Empty jsonp callback name.');
            }
            $data = $callback.'('.json_encode($data).')';
        }
        //默认直接输出
        $this->setContentType($type);
        //header('Content-Length: ' . strlen($data));
        return $data;
    }

    /**
     * 发送Http状态信息
     * @param $code
     */
    protected function sendHttpStatus($code)
    {
        $status = self::$httpStatus[$code];
        if (isset($status)) {
            header('HTTP/1.1 ' . $code . ' ' . $status);
            // 确保FastCGI模式下正常
            header('Status:' . $code . ' ' . $status);
            //JSONP请求不添加Access-Control-Allow-Origin头
            if (!$_GET['format'] ||
                ($_GET['format'] && strtolower($_GET['format']) != self::REQUEST_RESOURCE_JSONP)) {
                //TODO：Access-Control-Allow-Origin为*，这里安全性需要考虑
                header('Access-Control-Allow-Origin: *');
            }
        }
    }

    /**
     * 获取响应数据
     * @param int $errno
     * @param string $errmsg
     * @param mixed $data
     * @return array
     */
    protected function getResponseData($errno, $errmsg, $data = null)
    {
        $res = array("errno" => $errno, 'errmsg' => $errmsg);
        $data = array_merge($res, array('data' => $data));
        return $data;
    }
}
