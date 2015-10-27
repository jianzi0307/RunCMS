<?php
namespace Lib;

/**
 * 扩展功能函数类
 */
class Util
{
    /**
     * 服务器响应
     *
     * @param  integer $errno  错误码
     * @param  string  $errmsg 提示信息
     * @param  mixed $data   数据
     * @param  string  $type   格式类型 (json,xml)
     * @return int
     */
    public static function response($errno = 0, $errmsg = 'ok', $data = null, $type = 'json')
    {
        $res = array("errno"=>$errno, 'errmsg' => $errmsg);
        $data = array_merge($res, array('data'=>$data));
        switch ($type) {
            case 'xml':
                echo xml_encode($data, 'root');
                break;
            default:
                echo json_encode($data);
                break;
        }
        return $errno;
    }

    /**
     * 为密码添加干扰码
     * @param  string $pwd 明文密码
     * @return string      加密后的密码
     */
    public static function genMd5Pwd($pwd)
    {
        $pwd = trim($pwd);
        $md5hash = sprintf(C('PASSWORD_MASK'), $pwd);
        return md5($md5hash);
    }

    /**
     * 能自动去除空元素explode
     *
     * @param $seperator
     * @param $str
     * @return array
     */
    public static function explode($seperator, $str)
    {
        if (!$str) {
            return array();
        } else {
            $result = explode($seperator, $str);
            if (!$result) {
                return $result;
            }
            $temp=array();
            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]=='') {
                    continue;
                }
                $temp[] = $result[$i];
            }
            return $temp;
        }
    }

    /**
     * 获取文件扩展名
     *
     * @param string $fn
     * @return string
     */
    public static function getFileExtName($fn)
    {
        return substr(strrchr($fn, '.'), 1);
    }

    /**
     * 字符串截取
     *
     * @param string $str
     * @param int $strlen
     * @param int $other
     * @return string
     */
    public static function doStrOut($str, $strlen = 10, $other = 0)
    {
        if (empty($str)) {
            return $str;
        }
        $str=@iconv('UTF-8', 'GBK', $str);
        $j=0;
        for ($i = 0; $i < $strlen; $i++) {
            if (ord(substr($str, $i, 1))>0xa0) {
                $j++;
            }
        }
        if ($j%2 != 0) {
            $strlen++;
        }
        $rstr=@substr($str, 0, $strlen);
        $rstr=@iconv('GBK', 'UTF-8', $rstr);
        if (strlen($str) > $strlen && $other) {
            $rstr.='…';
        }
        return $rstr;
    }

    /**
     * 获取文件第一个. 之后的名字
     * @param string $fn
     * @return string
     */
    public static function getFileFirstExtName($fn)
    {
        return substr($fn, strpos($fn, '.') + 1);
    }

    /**
     * 得到当前用户Ip地址
     *
     * @return ip地址
     */
    public static function getRealIp()
    {
        $pattern = '/(\d{1,3}\.){3}\d{1,3}/';
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])
            && preg_match_all($pattern, $_SERVER['HTTP_X_FORWARDED_FOR'], $mat)) {
            foreach ($mat[0] as $ip) {
                //得到第一个非内网的IP地址
                if ((0 != strpos($ip, '192.168.')) && (0 != strpos($ip, '10.')) && (0 != strpos($ip, '172.16.'))) {
                    return  $ip;
                }
            }
            return $ip;
        } else {
            if (isset($_SERVER["HTTP_CLIENT_IP"]) && preg_match($pattern, $_SERVER["HTTP_CLIENT_IP"])) {
                return $_SERVER["HTTP_CLIENT_IP"];
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
        }
    }

    /**
     * 得到无符号整数表示的ip地址
     */
    public static function getIntIp()
    {
        return sprintf('%u', ip2long(self::getRealIp()));
    }

    /**
     * 过滤逗号分割的 id 字符串，转换全角字符为半角，去除可能的手误字符
     * @param string $idStr
     * @return string
     */
    public static function filterIdStr($idStr)
    {
        $idStr = trim(self::qj2bj($idStr));
        $intAry = str_split($idStr);
        $ret = array();
        foreach ($intAry as $char) {
            if (is_numeric($char) || $char === ',') {
                $ret[] = $char;
            }
        }
        return trim(join('', $ret), ',');
    }

    /**
     * 取合法的 id 字符串或 id 数组
     * @param $oIdStr
     * @param string $resultType
     * @return array|string
     */
    public static function getValidId($oIdStr, $resultType = 'string')
    {
        //排除部分手误影响
        $oIdStr = self::filterIdStr($oIdStr);
        while (false !== strpos($oIdStr, ',,')) {
            $oIdStr = str_replace(',,', ',', $oIdStr);
        }
        $idAry = array_unique(self::explode(',', $oIdStr));
        return ($resultType == 'string') ? join(',', $idAry) : $idAry;
    }

    /**
     * @param $basedir
     * @param int $num
     * @return int
     */
    public static function mkHashDir($basedir, $num = 100)
    {
        $l = 0;
        for ($i=0; $i<$num; $i++) {
            for ($j = 0; $j < $num; $j++) {
                $dir = $basedir . $i .'/' . $j . '/' ;
                mkdir($dir, 0777, true);
                $l++;
            }
        }
        return $l;
    }

    /**
     * 生成一个17字节长唯一随机文件名
     * @return string
     */
    public static function getRandFileName()
    {
        return chr(mt_rand(97, 122)) . mt_rand(10000, 99999) . time();
    }

    /**
     * 输入一个秒数，返回时分秒格式的字符串
     *
     * @param int $secs
     * @return string
     */
    public static function secToTime($secs)
    {
        if ($secs < 3600) {
            return sprintf("%02d:%02d", floor($secs / 60), $secs % 60);
        }
        $h = floor($secs / 3600);
        $m = floor(($secs % 3600) / 60);
        $s = $secs % 60;
        return sprintf("%02d:%02d:%02d", $h, $m, $s);
    }

    /**
     * 全角 => 半角
     *
     * @param string $string
     * @return string
     */
    public static function qj2bj($string)
    {
        $convert_table = array(
        '０' => '0','１' => '1','２' => '2','３' => '3','４' => '4',
        '５' => '5','６' => '6','７' => '7','８' => '8','９' => '9',
        'Ａ' => 'A','Ｂ' => 'B','Ｃ' => 'C','Ｄ' => 'D','Ｅ' => 'E',
        'Ｆ' => 'F','Ｇ' => 'G','Ｈ' => 'H','Ｉ' => 'I','Ｊ' => 'J',
        'Ｋ' => 'K','Ｌ' => 'L','Ｍ' => 'M','Ｎ' => 'N','Ｏ' => 'O',
        'Ｐ' => 'P','Ｑ' => 'Q','Ｒ' => 'R','Ｓ' => 'S','Ｔ' => 'T',
        'Ｕ' => 'U','Ｖ' => 'V','Ｗ' => 'W','Ｘ' => 'X','Ｙ' => 'Y',
        'Ｚ' => 'Z','ａ' => 'a','ｂ' => 'b','ｃ' => 'c','ｄ' => 'd',
        'ｅ' => 'e','ｆ' => 'f','ｇ' => 'g','ｈ' => 'h','ｉ' => 'i',
        'ｊ' => 'j','ｋ' => 'k','ｌ' => 'l','ｍ' => 'm','ｎ' => 'n',
        'ｏ' => 'o','ｐ' => 'p','ｑ' => 'q','ｒ' => 'r','ｓ' => 's',
        'ｔ' => 't','ｕ' => 'u','ｖ' => 'v','ｗ' => 'w','ｘ' => 'x',
        'ｙ' => 'y','ｚ' => 'z','　' => ' ','：' => ':','。' => '.',
        '？' => '?','，' => ',','／' => '/','；' => ';','［' => '[',
        '］' => ']','｜' => '|','＃' => '#',
        );
        return strtr($string, $convert_table);
    }

    /**
     * 对一个二维数组自定义排序
     *
     * @param array $ary
     * @param string $compareField
     * @param string $seq = 'DESC'|'ASC'
     * @param int $sortFlag = SORT_NUMERIC | SORT_REGULAR | SORT_STRING
     * @return array
     */
    public static function sort(&$ary, $compareField, $seq = 'DESC', $sortFlag = SORT_NUMERIC)
    {
        $sortData = array();
        foreach ($ary as $key => $value) {
            $sortData[$key] = $value[$compareField];
        }
        ($seq == 'DESC') ? arsort($sortData, $sortFlag) : asort($sortData, $sortFlag);

        $ret = array();
        foreach ($sortData as $key => $value) {
            $ret[$key] = $ary[$key];
        }
        $ary = $ret;
        return $ary;
    }

    /**
     * @param $ary
     * @param $compareField
     * @return array
     */
    public static function natsort(&$ary, $compareField)
    {
        $sortData = array();
        foreach ($ary as $key => $value) {
            $sortData[$key] = $value[$compareField];
        }
        natcasesort($sortData);

        $ret = array();
        foreach ($sortData as $key => $value) {
            $ret[$key] = $ary[$key];
        }
        $ary = $ret;
        return $ary;
    }

    /**
     * @param string $domain
     * @return string
     */
    public static function getCookieMsg($domain = 'rndchina.com')
    {
        if (isset($_COOKIE['json'])) {
            $jAry = json_decode($_COOKIE['json'], true) ;
            $msg = (isset($jAry['msg'])) ? $jAry['msg'] : '' ;
        } else {
            $msg = '';
        }
        $jAry['msg'] = '' ;
        $jsonStr = json_encode($jAry) ;
        setcookie('json', $jsonStr, time()+3600*24, '/', '.' . $domain);
        setcookie('json', $jsonStr, time()+3600*24, '/');
        return $msg;
    }

    /**
     * 跳转
     * @param string $url
     */
    public static function redirect($url = '')
    {
        if (!$url) {
            if (isset($_SERVER['HTTP_REFERFER']) && $_SERVER['HTTP_REFERER']) {
                $url = $_SERVER['HTTP_REFERER'];
            } else {
                $url = '/';
            }
        }
        header("Location: " . $url);
        exit;
    }

    /**
     * 跳转
     * @param $msg
     * @param string $url
     */
    public static function cookieMsgRedirect($msg, $url = '')
    {
        self::setCookieMsg($msg);
        if (!$url) {
            if (isset($_SERVER['HTTP_REFERFER']) && $_SERVER['HTTP_REFERER']) {
                $url = $_SERVER['HTTP_REFERER'];
            } else {
                $url = '/';
            }
        }
        header("Location: $url ");
        exit();
    }

    /**
     * @param $msg
     * @param string $domain
     */
    public static function setCookieMsg($msg, $domain = 'rndchina.com')
    {
        if (isset($_COOKIE['json'])) {
            $jAry = json_decode($_COOKIE['json'], true) ;
        }
        $jAry['msg'] = $msg ;
        $jsonStr = json_encode($jAry) ;
        setcookie("json", $jsonStr, time()+3600*24, '/', '.' . $domain);
        setcookie("json", $jsonStr, time()+3600*24, '/');
    }

    /**
     * 设置原始Cookie，不进行Url编码
     *
     * @param string $name 名称
     * @param string $value  值
     * @param int $life 过期时间
     * @param string $path 路径
     * @param string $domain
     * @return bool 域
     */
    public static function setRawCookie($name, $value, $life, $path = '/', $domain = 'rndchina.com')
    {
        if ($life == 0 || $life == '') {
            return setrawcookie($name, $value, time(), $path, '.' . $domain);
        } else {
            return setrawcookie($name, $value, time()+$life, $path, '.' . $domain);
        }
        #return setrawcookie($name, $value, time()+$life, $path);
    }

    /**
     * 设置Cookie
     * @param string $name 名称
     * @param string $value 值
     * @param int $life 过期时间
     * @param string $path 路径
     * @param string $domain
     * @return bool
     */
    public static function setCookie($name, $value, $life = 0, $path = '/', $domain = 'rndchina.com')
    {
        if ($life == 0) {
            return setcookie($name, $value, 0, $path, '.' . $domain);
        }
        //$res = setcookie($name, $value, time()+$life, $path, '.' . $domain);
        //return $res;
        return setcookie($name, $value, time()+$life, $path);
    }

    /**
     * 文本入库前的过滤工作
     *
     * @param $textString
     * @param bool $htmlspecialchars
     * @return string
     */
    public static function getSafeText($textString, $htmlspecialchars = true)
    {
        return $htmlspecialchars
        ? htmlspecialchars(trim(strip_tags(self::qj2bj($textString))))
        : trim(strip_tags(self::qj2bj($textString)))
        ;
    }

    /**
     * @param $string
     * @return mixed|string
     */
    public static function getSafeXml($string)
    {
        return self::getSafeUtf8(self::getSafeText($string), $_htmlspecialchars = true);
    }

    /**
     * @param $content
     * @return mixed|string
     */
    public static function getSafeUtf8($content)
    {
        $content = mb_convert_encoding($content, 'gbk', 'utf-8');
        $content = mb_convert_encoding($content, 'utf-8', 'gbk');
        $content = preg_replace('/[\x00-\x08\x0b\x0c\x0e-\x1f]/', '', $content);
        return $content;
    }

    /**
     * @param $content
     * @return mixed|string
     */
    public static function getSafeGbk($content)
    {
        $content = mb_convert_encoding($content, 'utf-8', 'gbk');
        $content = preg_replace('/[\x00-\x08\x0b\x0c\x0e-\x1f]/', '', $content);
        return $content;
    }

    /**
     * @param string $logFile
     */
    public static function debug($logFile = '')
    {
        if (!$logFile) {
            $logFile = '/tmp/debug.log';
        }
        $fp = fopen('php://stdout', 'w');

        static $__start_time = null;
        static $__start_code_line = 0;
        $dtrace = debug_backtrace();
        $call_info = array_shift($dtrace);
        $code_line = $call_info['line'];
        $fileAry = explode('/', $call_info['file']);
        $file = array_pop($fileAry);

        if ($__start_time === null) {
            $str = "debug ".$file."> initialize\n";
            fputs($fp, $str);
            self::log($str, $logFile);
            $__start_time = microtime(true);
            $__start_code_line = $code_line;
            fclose($fp);
        } else {
            $str = sprintf("debug %s> code-lines: %d-%d time: %.4f mem: %d KB\n", $file, $__start_code_line, $code_line, (microtime(true) - $__start_time), /*ceil( memory_get_usage()/1024)*/0);
            fputs($fp, $str);
            fclose($fp);
            self::log($str, $logFile);
            $__start_time = microtime(true);
            $__start_code_line = $code_line;
        }
    }

    /**
     * @param $msg
     * @param string $file
     */
    public static function log($msg, $file = '')
    {
        if (!$file && defined('GENERAL_LOG')) {
            $file = GENERAL_LOG;
        }
        $date = date("Y-m-d");
        $m = '[' . date('Y-m-d H:i', time()) . "]\n";
        $d = debug_backtrace();
        foreach ($d as $trace) {
            $m .= $trace['file'] . ' : ' . $trace['line'] . "\n";
        }
        $m .= $msg . "\n";
        if (stripos($file, '/tmp/') !== false) {
            $file = $file ?  $file . '.' . $date : '/tmp/tmp.log.' . $date;
            $size = file_exists($file) ? filesize($file) : 0;
            $fp = $size < 1024 * 1024 * 500 ? fopen($file, "a+") : false;
        } else {
            $file = $file ? $file : '/tmp/tmp.log.' . $date;
            $size = file_exists($file) ? filesize($file) : 0;
            $fp = $size < 1024 * 1024 * 500 ? fopen($file, "a+") : false;
        }
        if ($fp) {
            fputs($fp, $m);
            fclose($fp);
        }
        if (defined('DEBUG') || isset($_GET['debug'])) {
            self::printR($msg);
            echo nl2br($m);
            ob_flush();
            flush();
        }
    }

    public static function printR($var)
    {
        echo '<pre>' . print_r($var, true) . '</pre>';
    }

    public static function varExport($var)
    {
        echo '<pre>' . var_export($var, true) . '</pre>';
    }

    /**
     * @param $txt
     * @return string
     */
    public static function text2html($txt)
    {
        return  nl2br(str_replace(" ", "&nbsp;", htmlspecialchars($txt, ENT_QUOTES)));
    }

    /**
     * @param $loginLast
     * @return string
     */
    public static function getHumanReadableLastTime($loginLast)
    {
        $period = time() - ((is_numeric($loginLast)) ? $loginLast : strtotime($loginLast));
        if ($period < 0) {
            return "1秒前";
        } elseif ($period < 60) {
            return $period . "秒前";
        } elseif ($period < 3600) {
            return round($period / 60, 0) . "分钟前";
        } elseif ($period < 86400) {
            return round($period / 3600, 0) . "小时前";
        } else {
            return round($period / 86400, 0) . "天前";
        }
    }

    public static function outputExpireHeader($lifetime = 300)
    {
        header("Expires: ".gmdate("D, d M Y H:i:s", time()+$lifetime)." GMT");
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
        header("Cache-Control: max-age=$lifetime");
    }

    /**
     * @param $idStr
     * @param $id
     * @param bool $reverse
     * @return string
     */
    public static function addIdToIdStr($idStr, $id, $reverse = false)
    {
        if ($reverse) {
            return $idStr ? $idStr . ',' . $id  : $id;
        }
        return $idStr ? $id . ',' . $idStr : $id;
    }

    /**
     * @param $idStr
     * @param $id
     * @return string
     */
    public static function removeIdFromIdStr($idStr, $id)
    {
        $idStr = ',' . $idStr . ',';
        return trim(str_replace(",{$id},", ',', $idStr), ',');
    }

    /**
     * @param $idStr
     * @param $oldId
     * @param $newId
     * @return string
     */
    public static function replaceIdFromIdStr($idStr, $oldId, $newId)
    {
        $idStr = ',' . $idStr . ',';
        return trim(str_replace(",{$oldId},", ",{$newId},", $idStr), ',');
    }

    /**
     * @param $idStr
     * @param $id
     * @return bool
     */
    public static function isExistInIdStr($idStr, $id)
    {
        $idStr = ',' . $idStr . ',';
        return (strpos($idStr, ",{$id},") !== false);
    }

    /**
     * 仅适用于 $dec <= 255 的场合
     * @param $dec
     * @return string
     */
    public static function dec2bin($dec)
    {
        $r = decbin((int)$dec);
        $n = 8 - strlen($r);
        for ($i = 0; $i < $n; $i++) {
            $r = '0' . $r;
        }
        return $r;
    }

    /**
     * 得到有效的搜索词
     * @param $keyword
     * @param string $method
     * @return string
     */
    public static function getValidSearchKeyword($keyword, $method = 'AND')
    {
        $sep = (strtoupper($method) === 'AND') ? ' AND ' : ' ';
        return $keyword ? urlencode(join($sep, array_unique(Util::explode(' ', str_replace('　', ' ', $keyword))))) : '';
    }

    /**
     * 精确截取字符串
     * @param $Str 截取字符串
     * @param $Length 需要截取的长度
     * @return string
     */
    public static function ourSubstr($Str, $Length)
    {
        //global $s;
        $i = 0;
        $l = 0;
        $ll = strlen($Str);
        $s = $Str;
        $f = true;

        while ($i <= $ll) {
            if (ord($Str{$i}) < 0x80) {
                $l++;
                $i++;
            } elseif (ord($Str{$i}) < 0xe0) {
                $l++;
                $i += 2;
            } elseif (ord($Str{$i}) < 0xf0) {
                $l += 2;
                $i += 3;
            } elseif (ord($Str{$i}) < 0xf8) {
                $l += 1;
                $i += 4;
            } elseif (ord($Str{$i}) < 0xfc) {
                $l += 1;
                $i += 5;
            } elseif (ord($Str{$i}) < 0xfe) {
                $l += 1;
                $i += 6;
            }

            if (($l >= $Length - 1) && $f) {
                $s = substr($Str, 0, $i);
                $f = false;
            }

            /* if (($l > $Length) && ($i < $ll)) {
            $s = $s . '...'; break; //如果进行了截取，字符串末尾加省略符号“...”
            } */
        }
        return $s;
    }

    /**
     * @param $string
     * @return int
     */
    public static function isUtf8($string)
    {
        $pattern = '%(?:
            [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
            |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
            |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
            |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
            |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
            |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
            |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
            )+%xs';
        return preg_match($pattern, $string);
    }

    /**
     * @return mixed
     */
    public static function getDocumentRoot()
    {
        return isset($_SERVER['DOCUMENT_ROOT'])
        ? $_SERVER['DOCUMENT_ROOT']
        : str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['PHP_SELF'])));
    }

    /**
     * @param $str
     * @return number
     */
    public static function APHash($str)
    {
        $hash = 0;
        $n = strlen($str);
        for ($i = 0; $i < $n; $i++) {
            if (($i & 1 ) == 0) {
                $hash ^= (($hash <<7 ) ^ ord($str[$i]) ^ ($hash>> 3 ));
            } else {
                $hash ^= ( ~ (($hash <<11 ) ^ ord($str[$i]) ^ ($hash>> 5)));
            }
        }
        return abs($hash % 701819);
    }

    /**
     * @param $timeLast
     * @param int $timeNext
     * @return bool|string
     */
    public static function getTimeOver($timeLast, $timeNext = 0)
    {
        if (!$timeNext) {
            $timeNext = time();
        }
        if ($timeLast === false || $timeNext === false || $timeLast > $timeNext) {
            return "时间异常";
        }

        $iAll = (int)(($timeNext - $timeLast) / 60);

        if ($iAll < 60) {
            $iAll = ($iAll == 0) ? 1 : $iAll;
            return "{$iAll} 分钟前";
        }
        $hAll = (int)($iAll / 60);
        if ($hAll < 24) {
            return "{$hAll} 小时前";
        }
        $dAll = (int)($hAll / 24);
        if ($dAll < 30) {
            return "{$dAll} 天前";
        }
        if ($dAll < 365) {
            $m = (int)($dAll / 30);
            return "{$m} 月前";
        }
        return date('Y-m-d', $timeLast);
    }

    /**
     * 创建目录
     * @param $dirPath
     */
    public static function makeDir($dirPath)
    {
        $dirArr = (split("/", $dirPath));
        $path = "/";
        foreach ($dirArr as $dir) {
            if ("" != $dir) {
                $path .= "$dir/";
                if (!file_exists($path)) {
                    mkdir($path);
                    chmod($path, 0777);
                }
            }
        }
    }

    /**
     * 写入文件
     *
     * @param string $fileName
     * @param string $content
     * return null
     */
    public function writeFile($fileName, $content)
    {
        $fileHandle = fopen($fileName, "w");
        fwrite($fileHandle, $content);
        fclose($fileHandle);
        @chmod($fileName, 0777);
    }

    /**
     * 追加写入文件
     *
     * @param string $fileName
     * @param string $content
     * return null
     */
    public function appendFile($fileName, $content)
    {
        $fileHandle = fopen($fileName, "a+");
        fwrite($fileHandle, $content);
        fclose($fileHandle);
        @chmod($fileName, 0777);
    }

    /**
     * 利用file_get_contents获取url文件内容
     *
     * @param string $url
     * @param int $timeout
     * @param string $logfile
     * @return string or array
     */
    public static function getFileContent($url, $timeout = 2, $logfile = '')
    {
        if (!empty($url)) {
            ini_set('default_socket_timeout', $timeout);
            $result     = @file_get_contents($url);
            if (empty($result)) {
                $result = @file_get_contents($url);
            }

            if (!empty($logfile) || empty($result)) {
                @file_put_contents("/tmp/".$logfile.'_search_time.log.'
                    . date('Y-m-d'), "{$url} " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            }

            return $result;
        }
        return false;
    }
}
