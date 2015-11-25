<?php
/**
 * ----------------------
 * HttpClient.class.php
 * 
 * User: jian0307@icloud.com
 * Date: 2015/11/12
 * Time: 12:54
 * ----------------------
 */
namespace Lib;

/**
 * Class HttpClient
 * HTTP客户端类
 * @package Lib
 */
class HttpClient
{
    const PROXY_NONE = 0;
    const PROXY_SOCKS4 = 1;
    const PROXY_SOCKS5 = 2;
    const PROXY_HTTP = 4;

    /**
     * 请求头
     * @var array
     */
    protected $requireHeader = [];

    /**
     * 请求Cookie信息
     * @var array
     */
    protected $requireCookie = [];

    /**
     * 响应头
     * @var array
     */
    protected $responseHeader = [];

    /**
     * 响应数据
     * @var array
     */
    protected $responseBody = [];

    /**
     * 响应Cookie信息
     * @var array
     */
    protected $responseCookie = [];

    /**
     * 响应状态
     * @var
     */
    protected $responseStatus;

    /**
     * 请求URI
     * @var
     */
    protected $requireUri;

    /**
     * 代理方式
     * @var
     */
    protected $proxyProtocol;

    /**
     * 代理服务器主机
     * @var
     */
    protected $proxyHost;

    /**
     * 代理认证用户名
     * @var
     */
    protected $proxyUser;

    /**
     * 代理认证密码
     * @var
     */
    protected $proxyPass;

    /**
     * cookie持久访问
     * @var
     */
    protected $keepCookie;

    /**
     * 错误信息
     * @var
     */
    protected $error;

    /**
     * MIME类型
     * @var array
     */
    protected $mimes = [
        'gif' => 'image/gif',
        'png' => 'image/png',
        'bmp' => 'image/bmp',
        'jpeg' => 'image/jpeg',
        'pjpg' => 'image/pjpg',
        'jpg' => 'image/jpeg',
        'tif' => 'image/tiff',
        'htm' => 'text/html',
        'css' => 'text/css',
        'html' => 'text/html',
        'txt' => 'text/plain',
        'gz' => 'application/x-gzip',
        'tgz' => 'application/x-gzip',
        'tar' => 'application/x-tar',
        'zip' => 'application/zip',
        'hqx' => 'application/mac-binhex40',
        'doc' => 'application/msword',
        'pdf' => 'application/pdf',
        'ps' => 'application/postcript',
        'rtf' => 'application/rtf',
        'dvi' => 'application/x-dvi',
        'latex' => 'application/x-latex',
        'swf' => 'application/x-shockwave-flash',
        'tex' => 'application/x-tex',
        'mid' => 'audio/midi',
        'au' => 'audio/basic',
        'mp3' => 'audio/mpeg',
        'ram' => 'audio/x-pn-realaudio',
        'ra' => 'audio/x-realaudio',
        'rm' => 'audio/x-pn-realaudio',
        'wav' => 'audio/x-wav',
        'wma' => 'audio/x-ms-media',
        'wmv' => 'video/x-ms-media',
        'mpg' => 'video/mpeg',
        'mpga' => 'video/mpeg',
        'wrl' => 'model/vrml',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo'
    ];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->initRequire();
        $this->initResponse();
    }

    /**
     * 设置请求头
     * @param $key
     * @param $value
     */
    public function setRequireHeader($key, $value)
    {
        $this->requireHeader[$key] = $value;
    }

    /**
     * 删除请求头
     * @param $key
     */
    public function removeRequireHeader($key)
    {
        unset($this->requireHeader[$key]);
    }

    /**
     * 设置Cookie
     * @param $key
     * @param $value
     */
    public function setRequireCookie($key, $value)
    {
        $this->requireCookie[$key] = $value;
    }

    /**
     * 批量设置Cookie
     * @param mixed $value Cookie数组或Cookie字符串
     */
    public function setRequireCookies($value)
    {
        $this->requireCookie = array_merge(
            $this->requireCookie,
            is_array($value) ? $value : $this->cookieStr2Ary($value)
        );
    }

    /**
     * 获取响应数据
     * @return array
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * 获取响应头
     * @return array
     */
    public function getResponseHeader()
    {
        return $this->responseHeader;
    }

    /**
     * 获取响应Cookie
     * @return array
     */
    public function getResponseCookie()
    {
        return $this->responseCookie;
    }

    /**
     * 获取响应状态
     * @return mixed
     */
    public function getHttpStatus()
    {
        return $this->responseStatus;
    }

    /**
     * 设置代理
     * @param string $host 代理主机
     * @param int $protocol 代理协议
     * @param string $user 用户名
     * @param string $password 密码
     */
    public function setProxy($host, $protocol = HttpClient::PROXY_HTTP, $user = null, $password = null)
    {
        $this->proxyHost = $host;
        $this->proxyProtocol = $protocol;
        if (!empty($user)) {
            $this->proxyUser = $user;
            $password = empty($password) ? '' : $password;
            $this->proxyPass = $password;
        }
    }

    /**
     * @param $value
     */
    public function setKeepCookie($value)
    {
        $this->keepCookie = $value;
    }

    /**
     * Cookie字符串转数组
     * @param string $str Cookie字符串
     * @return array
     */
    protected function cookieStr2Ary($str)
    {
        $ret = [];
        $cookies = explode(';', $str);
        //$ext = array('path','expires','domain','httponly','');
        if (count($cookies)) {
            foreach ($cookies as $cookie) {
                $cookie = trim($cookie);
                $arr = explode('=', $cookie);
                $value = implode('=', array_slice($arr, 1, count($arr)));
                $ret[trim($arr[0])] = $value;
            }
        }
        return $ret;
    }

    /**
     * 初始化请求
     */
    protected function initRequire()
    {
        $this->requireHeader = [
            'Accept'            =>  'Accept: */*',
            'Accept-Language'   =>  'zh-cn',
            'User-Agent'        =>  'PHPHttpClient/1.0',
            'Connection'        =>  'close'
        ];
        $this->requireCookie = [];
    }

    /**
     * 初始化响应
     */
    protected function initResponse()
    {
        $this->removeRequireHeader('Content-Type');
        $this->responseHeader = [];
        $this->responseBody = '';
        $this->responseStatus = 0;
        if (!$this->keepCookie) {
            $this->responseCookie = [];
        }
    }

    /**
     * 发送请求
     * @param $method
     * @param mixed $data
     * @return bool
     */
    protected function send($method, $data = null)
    {
        $matches = parse_url($this->requireUri);
        !isset($matches['host']) && $matches['host'] = '';
        !isset($matches['path']) && $matches['path'] = '';
        !isset($matches['query']) && $matches['query'] = '';
        !isset($matches['port']) && $matches['port'] = '';
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
        $port = $matches['port'] ? $matches['port']: 80;
        $this->requireHeader['Host'] = $host.($port == 80 ? '' :(':'.$port));
        if (!isset($this->requireHeader['Referer'])) {
            $this->requireHeader['Referer'] = $this->requireUri;
        }
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$sock) {
            $this->error = socket_last_error();
        }
        socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>10, "usec"=>0));
        socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>10, "usec"=>0));
        if (isset($this->_proxy_type) &&  $this->_proxy_type != HttpClient::PROXY_NONE) {
            list($proxy_host, $proxy_port) = explode(':', $this->proxyHost);
            if (!isset($proxy_port)) {
                $proxy_port = 80;
            }
            if (!@socket_connect($sock, $proxy_host, $proxy_port)) {
                $this->error = "Cann't connect to {$host}:{$port}";
                return false;
            }
            $host_ip = gethostbyname($host);
            switch ($this->proxyProtocol) {
                case HttpClient::PROXY_SOCKS4:
                    socket_write($sock, chr(4).chr(1).pack('nN', $port, ip2long($host_ip)).'HttpClient'.chr(0));
                    $buf = socket_read($sock, 2, PHP_BINARY_READ);
                    if (ord(substr($buf, -1)) != 90) {
                        $this->error = "Request to {$host}:{$port} rejected or failed";
                        socket_close($sock);
                        return false;
                    }
                    break;
                case HttpClient::PROXY_SOCKS5:
                    //step1
                    $auth_method = empty($this->proxyUser) ? 1 : 2;
                    socket_write($sock, chr(5).chr(1).chr($auth_method));
                    $buf = socket_read($sock, 2, PHP_BINARY_READ);
                    if (substr($buf, -1) != 0x00) {
                        $this->error ="Request to {$host}:{$port} rejected or failed";
                        socket_close($sock);
                        return false;
                    }
                    //auth
                    if ($auth_method == 2) {
                        socket_write(
                            $sock,
                            chr(1).chr(strlen($this->proxyUser)).$this->proxyUser
                            .chr(strlen($this->proxyPass))
                            .$this->proxyPass
                        );
                        $buf = socket_read($sock, 2, PHP_BINARY_READ);
                        if (substr($buf, -1) != 0x00) {
                            $this->error = "authentication  failed";
                            socket_close($sock);
                            return false;
                        }
                    }
                    //step2
                    //使用代理的dns服务器
                    socket_write($sock, pack("C5", 0x05, 0x01, 0x00, 0x03, strlen($host)).$host.pack("n", $port));
                    $buf = socket_read($sock, 2, PHP_BINARY_READ);
                    $response = unpack("Cversion/Cresult", $buf);
                    if ($response['result'] != 0) {
                        $this->error ="Request to {$host}:{$port} rejected or failed";
                        socket_close($sock);
                        return false;
                    }
                    break;
                case HttpClient::PROXY_HTTP:
                    $path = $this->requireUri;
                    $this->responseHeader['Proxy-Connection'] = 'Close';
                    if (!empty($this->proxyUser)) {
                        $this->requireHeader['Proxy-Authorization']
                            = 'Basic '.base64_encode($this->proxyUser.':'.$this->proxyPass);
                    }
                    break;
            }
        } else {
            if (!socket_connect($sock, $host, $port)) {
                $this->error = "Cann't connect to {$host}:{$port}";
                return false;
            }
        }

        //send data
        $_method = strtoupper($method)." {$path} HTTP/1.0\r\n";
        $data = $_method.$this->createHeader()."\r\n".$data;
        socket_write($sock, $data);
        $this->responseCookie = $this->requireCookie;
        $recv = '';
        while (($line = @socket_read($sock, 1024)) != false) {
            $recv .=  $line;
        }
        switch ($this->proxyProtocol) {
            case HttpClient::PROXY_SOCKS4:
                break;
            case HttpClient::PROXY_SOCKS5:
                if ($recv) {
                    $recv = substr($recv, 8);
                }
                break;
        }
        $arr = explode("\r\n\r\n", $recv);

        //处理报头
        $heads = explode("\r\n", array_shift($arr));
        foreach ($heads as $line) {
            if (trim($line) == '' || $line == "\r\n") {
                continue;
            }
            if (!strncasecmp('HTTP', $line, 4)) {
                //status
                $status = explode(' ', $line);
                $this->responseStatus = intval($status[1]);
            } elseif (!strncasecmp('Set-Cookie: ', $line, 12)) {
                $this->responseCookie = array_merge(
                    $this->responseCookie,
                    $this->cookieStr2Ary(substr($line, 12))
                );
                if ($this->keepCookie) {
                    $this->requireCookie = array_merge($this->requireCookie, $this->responseCookie);
                }
            } else {
                $header = explode(':', $line, 2);
                if (count($header) == 2) {
                    $this->responseHeader[$header[0]] = trim($header[1]);
                }
            }
        }
        //报文
        $this->responseBody = implode("\r\n\r\n", $arr);
        socket_close($sock);
        return true;
    }

    /**
     * 生成头
     * @return string
     */
    protected function createHeader()
    {
        $header = '';
        foreach ($this->requireHeader as $key => $value) {
            $header .= $key . ':' . $value . "\r\n";
        }
        if (count($this->requireCookie)) {
            $cookie = '';
            foreach ($this->requireCookie as $key => $value) {
                $cookie .= $key . '=' . $value . ';';
            }
            if (!empty($cookie)) {
                $header .= "Cookie: $cookie\r\n";
            }
        }
        return $header;
    }

    /**
     * GET请求
     * @param $uri
     */
    public function get($uri)
    {
        $this->requireUri = $uri;
        $this->initResponse();
        $this->send('get');
        $this->initRequire();
    }

    /**
     * POST请求
     * @param $uri
     * @param array $data
     * @param array $files
     */
    public function post($uri, $data = [], $files = [])
    {
        $this->requireUri = $uri;
        $this->initResponse();
        $post = '';
        if (count($files)) {
            $post = $this->postFile($data, $files);
        } else {
            $post = $this->postText($data);
        }
        $this->requireHeader['Content-Length'] = strlen($post);
        $this->send('post', $post);
        $this->initRequire();
    }

    /**
     * @param $data
     * @return string
     */
    protected function postText($data)
    {
        $post = '';
        if (count($data)) {
            foreach ($data as $key => $value) {
                $post .= '&' . $this->formatPost($key, $value);
            }
            $post = substr($post, 1);
        }
        $this->requireHeader['Content-Type'] = 'application/x-www-form-urlencoded';
        return $post;
    }

    /**
     * @param $data
     * @param $files
     * @return string
     */
    protected function postFile($data, $files)
    {
        $boundary = "---------------------------".substr(md5(rand(0, 32000)), 0, 10);
        $this->requireHeader['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;
        $post =  "--$boundary\r\n";
        //附件数据
        foreach ($files as $key => $value) {
            if (is_file($value)) {
                $content = file_get_contents($value);
                $filename = basename($value);
                $file_type = $this->getMime($value);
                $post.="Content-Disposition: form-data; name=\"{$key}\"; filename=\"{$filename}\"\r\n";
                $post.="Content-Type: {$file_type}\r\n\r\n";
                $post.="$content\r\n";
                $post .="--$boundary";
            }
        }
        //附带文本数据
        if (count($data)) {
            foreach ($data as $key => $value) {
                $post .="\r\nContent-Disposition: form-data; name=\"$key\"\r\n\r\n";
                $post .="$value\r\n";
                $post .="--$boundary";
            }
        }
        $post .= "--\r\n\r\n";
        return $post;
    }

    /**
     * @param $key
     * @param $value
     * @return string
     */
    protected function formatPost($key, $value)
    {
        $post = '';
        if (is_array($value)) {
            if (count($value) > 0) {
                foreach ($value as $k => $v) {
                    $post .= ('&' . $this->formatPost($key . '[' . $k . ']', $v));
                }
            }
        } else {
            $post .= ('&' . $key . '=' . rawurlencode($value));
        }
        return substr($post, 1);
    }

    /**
     * MIME
     * @param $file
     * @return string
     */
    protected function getMime($file)
    {
        $ary = explode('.', $file);
        $ext = strtolower($ary[count($ary) - 1]);
        if (isset($this->mimes[$ext])) {
            return $this->mimes[$ext];
        } else {
            return 'image/jpeg';
        }
    }
}
