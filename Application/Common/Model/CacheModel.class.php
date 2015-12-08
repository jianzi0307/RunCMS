<?php
/**
 * ----------------------
 * MemcacheModel.class.php
 * 
 * User: jian0307@icloud.com
 * Date: 2015/11/5
 * Time: 16:05
 * ----------------------
 */
namespace Common\Model;

use Lib\ExtMemcache;
use Think\Model;

class CacheModel extends Model
{
    protected static $mcPool = array();     //memcache对象池
    protected static $objPool = array();    //obj对象池

    /**
     * 指定 mcString 得到对应的 ExtMemcache 对象
     * 极其常用
     *
     * @param string $mcString 服务器组名
     * @return object of ExtMemcache
     */
    public static function getMc($mcString = 'mcMain')
    {
        self::$mcPool = array();
        if (isset(self::$mcPool[$mcString])) {
            return self::$mcPool[$mcString];
        }
        $serversConf = C('MEMCACHE_SERVERS');
        return self::$mcPool[$mcString] = new ExtMemcache($serversConf[$mcString]);
    }

    /**
     * 指定 clsName 得到对应的对象实例，
     * ThinkPHP对应的M,D等方法都做了对象池缓存，此函数用于创建自定义类对象
     * 极为常用
     * @param string $clsName
     * @return object of $clsName
     */
    public static function getObj($clsName)
    {
        if (isset(self::$objPool[$clsName])) {
            return self::$objPool[$clsName];
        }
        return self::$objPool[$clsName] = new $clsName();
    }
}
