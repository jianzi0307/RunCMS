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
namespace Lib;

use Think\Log;
use Memcache as MemcachedResource;

/**
 * 根据PHP自带得Memcache类来扩展的Memcached操作类
 * ThinkPHP自带的CacheMemcache.class.php只支持单Memcached服务器，不支持分布式Memcached
 * 使用此类代替
 */
class ExtMemcache extends MemcachedResource
{
	private $_expire = 0;         //默认所有缓存均不过期
    //private $_debug = false;
    //private $_traced = false;
    private $_serverAry = array();

    /**
     * 构造函数
     * @param Array $serverAry MC服务器集群
     */
    public function __construct(Array $serverAry)
    {
        $this->_serverAry = $serverAry;
        //if( APP_DEBUG ) {
        //	$this->_debug = true;
        //}

        foreach ($this->_serverAry as $server) {
            if (! isset($server['host'],$server['port'])) {
                die('MC Server Param Error' . var_export($server, true));
            }
            $server['persistent'] = false;
            $server['timeout'] = 1;
            $server['retry'] = -1;
            $server['status'] = 1;
            //添加MC服务器到连接池，官方文档：addServer并不会去立即连接服务器
            $this->addServer(
                $server['host'],
                $server['port'],
                $_persistent = false,
                $server['weight'],
                $_timeout = 1,
                $_retry = -1,
                $_status = 1
            );
        }
    }

    /**
     * 获取缓存
     * @param  string $cacheId 缓存key
     * @return mixed
     */
    public function get($cacheId)
    {
        $ret = parent::get($cacheId);
        return $ret;
    }

    /**
     * 存储缓存
     * @param $cacheId 缓存Key
     * @param $value 值
     * @param int $expire 过期时间
     * @param bool $needCompress 是否压缩
     * @return bool|\boolen
     */
    public function set($cacheId, $value, $expire = 0, $needCompress = false)
    {
        //三次尝试
        $ret = parent::set($cacheId, $value, $needCompress, $expire);
        if (!$ret) {
            $ret = parent::set($cacheId, $value, $needCompress, $expire);
        }
        if (!$ret) {
            $ret = parent::set($cacheId, $value, $needCompress, $expire);
        }
        if ($ret) {
            return $ret;
        } else {
            //log error.
            Log::record('set to mc failed! server is:'. var_export($this->_serverAry, true));
        }
        return $ret;
    }

    /**
     * 新增incrementEx方法
     * 支持如下两个变化
     * 1. 如果cacheId不存在，自动重新创建
     * 2. 支持负数和正数，自动根据符号判断
     *
     * @param string $cacheId
     * @param int $value
     * @return int
     */
    public function incrementEx($cacheId, $value = 1)
    {
        //如果cacheId不存在，直接设置
        if (false === parent::get($cacheId)) {
            return parent::set($cacheId, $value);
        }
        //判断value的正负分别处理
        if ($value > 0) {
            return parent::increment($cacheId, $value);
        } else {
            return parent::decrement($cacheId, -$value);
        }
    }
}
