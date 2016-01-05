<?php
/**
 * ----------------------
 * SingletonTrait.class.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/7/22
 * Time: 13:22
 * ----------------------
 */

namespace Lib;

trait SingletonTrait
{
    protected static $instances = array();

    final public static function getInstance()
    {
        $calledClass = get_called_class();
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new $calledClass();
        }
        return self::$instances[$calledClass];
    }

    final private function __construct()
    {
    }

    final private function __wakeup()
    {
    }

    final private function __clone()
    {
        throw new Exception('You can not clone a singleton.');
    }
}
