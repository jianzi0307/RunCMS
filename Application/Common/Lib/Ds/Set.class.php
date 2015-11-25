<?php
/**
 * Created by PhpStorm.
 * User: jianzi
 * Date: 15-11-17
 * Time: 下午4:24
 */
namespace Lib\Ds;

class Set implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Key-value 数组
     * @var array
     */
    protected $data = array();

    /**
     * 构造函数
     * @param array
     */
    public function __construct($items = array())
    {
        $this->replace($items);
    }
    /**
     * 标准化索引
     * 子类重写
     * @param  string $key 索引
     * @return mixed 标准化后的索引
     */
    protected function normalizeKey($key)
    {
        return $key;
    }

    /**
     * 设置数据
     * @param string $key   索引
     * @param mixed  $value 数据
     */
    public function set($key, $value)
    {
        $this->data[$this->normalizeKey($key)] = $value;
    }
    /**
     * 获取值
     * @param  string $key     索引
     * @param  mixed  $default 如果key不存在返回的值
     * @return mixed           数据
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            $isInvokable = is_object($this->data[$this->normalizeKey($key)]) && method_exists($this->data[$this->normalizeKey($key)], '__invoke');
            return $isInvokable ? $this->data[$this->normalizeKey($key)]($this) : $this->data[$this->normalizeKey($key)];
        }
        return $default;
    }

    /**
     * 添加数据到Set
     * @param array $items 追加数据到Key-value数组
     */
    public function replace($items)
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value); // Ensure keys are normalized
        }
    }
    /**
     * 取数据
     * @return array 数组
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * 获取所有的key数组
     * @return array 数组
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * 是否包含key
     * @param  string  $key 索引
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($this->normalizeKey($key), $this->data);
    }

    /**
     * 删除值
     * @param  string $key 索引
     */
    public function remove($key)
    {
        unset($this->data[$this->normalizeKey($key)]);
    }

    /**
     * 属性重载
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

    public function __unset($key)
    {
        $this->remove($key);
    }

    /**
     * 清除
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * Array Access
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
    /**
     * Countable
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * IteratorAggregate
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * 单例
     * @param  string   $key 索引
     * @param  \Closure $value 闭包
     * @return mixed
     */
    public function singleton($key, $value)
    {
        $this->set($key, function ($c) use ($value) {
            static $object;
            if (null === $object) {
                $object = $value($c);
            }
            return $object;
        });
    }
    /**
     * @param  \Closure $callable
     * @return \Closure
     */
    public function protect(\Closure $callable)
    {
        return function () use ($callable) {
            return $callable;
        };
    }
}

