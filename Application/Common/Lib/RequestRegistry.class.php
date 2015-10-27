<?php
namespace Lib;

/**
 * Class RequestRegistry
 *
 * 注册器
 * @package Admin\Controller
 */
class RequestRegistry implements IRegistry
{
    private static $_ins = null;
    private $_pool = array();

    public static function getInstance()
    {
        if (!self::$_ins instanceof self) {
            self::$_ins = new self();
        }
        return self::$_ins;
    }

    public function get($key)
    {
        if (isset($this->_pool[$key])) {
            return $this->_pool[$key];
        }
        return null;
    }

    public function set($key, $value)
    {
        $this->_pool[$key] = $value;
    }

    private function __clone()
    {

    }
}
