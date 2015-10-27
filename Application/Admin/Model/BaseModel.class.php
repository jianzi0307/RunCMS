<?php
namespace Admin\Model;

use Think\Model;
use Lib\ExtMemcache;

/**
 * Class BaseModel
 *
 * 模型基类
 * @package Admin\Model
 */
class BaseModel extends Model
{
    protected $mcEnable = true;    //是否开启memcache缓存
    protected static $mcPool = array();     //memcache对象池
    protected static $objPool = array();    //obj对象池

    //ExtMemcache对象
    protected $mc = null;

    //取缓存的Key前缀
    protected $mcPrefix = 'Base::';

    public function __construct()
    {
        parent::__construct();

        //获取mc对象
        $this->mc = $this->getMc('mcMain');
    }

    public function _initialize()
    {
        //为便于调试, 提供一种便利的禁用 mc 的方式
        if (isset($_GET['mcDisable']) || isset($_POST['mcDisable'])) {
            $this->mcEnable = false;
        }
    }

    /**
     * 添加记录
     * @param array $data
     * @return bool
     */
    public function addRow($data)
    {
        if (!empty($data) && is_array($data)) {
            $res = $this->add($data);
            if ($res) {
                //$this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
                return $res;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 获取单条数据
     * @param int|array $map 条件，当$map为整数时，视为根据ID获取
     * @return mixed
     */
    public function getRow($map)
    {
        $res = null;
        if (!empty($map) && is_array($map)) {
             $res = $this->field(true)->where($map)->find();
        }
        if (is_integer($map)) {
            $res = $this->field(true)->find($map);
        }
        return $res;
    }

    /**
     * 获取多条数据
     * @param array $map
     * @param string|null $order 例如：'sort asc,id asc'
     * @return mixed
     */
    public function getRows($map, $order = null)
    {
        if ($order !== null && is_string($order)) {
            return $this->field(true)->where($map)->order($order)->select();
        } else {
            return $this->field(true)->where($map)->select();
        }
    }

    /**
     * 获取所有记录
     * @param string $order 排序 例如：'sort asc,id asc'
     * @param int $offset
     * @param int|null $len
     * @return mixed
     */
    public function getAll($order = null, $offset = null, $len = null)
    {
        if ($order !== null) {
            if ($offset) {
                return $this->field(true)->order($order)->limit($offset, $len)->select();
            } else {
                return $this->field(true)->order($order)->select();
            }
        } else {
            if ($offset) {
                return $this->field(true)->limit($offset, $len)->select();
            } else {
                return $this->field(true)->select();
            }
        }
    }

    /**
     * 更新记录
     * @param array $data 更新的数据
     * @param int|array $map 条件，当$map为整数时，视为根据ID更新
     * @return bool
     */
    public function updateRows($data, $map)
    {
        if (!empty($data) && is_array($data)) {
            $res = null;
            if ($map && is_array($map)) {
                $res = $this->where($map)->save($data);
            }
            if (is_integer($map)) {
                $res = $this->where("id={$map}")->save($data);
            }
            if ($res !== false && $res) {
                //$this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 删除记录
     * @param int|array $map 条件，当$map为整数时，视为根据ID删除
     * @return bool
     */
    public function delRows($map)
    {
        if (!empty($map) && is_array($map)) {
            if ($this->where($map)->delete()) {
                //$this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
                //记录行为
                //action_log('update_config','config',$id,UID);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 根据ID数组删除多条记录
     * @param array $ids
     * @return bool
     */
    public function delRowsInIds($ids)
    {
        if (!empty($ids) && is_array($ids)) {
            $map = array('id' => array('in', $ids));
            $res = $this->where($map)->delete();
            if ($res !== false) {
                //$this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
                //记录行为
                //action_log('update_config','config',$id,UID);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 指定 clsName 得到对应的对象实例，
     * ThinkPHP对应的M,D等方法都做了对象池缓存，此函数用于创建自定义类对象
     * 极为常用
     * @param string $clsName
     * @return object of $clsName
     */
    public function getObj($clsName)
    {
        if (isset(self::$objPool[$clsName])) {
            return self::$objPool[$clsName];
        }
        return self::$objPool[$clsName] = new $clsName();
    }

    /**
     * 指定 mcString 得到对应的 ExtMemcache 对象
     * 极其常用
     *
     * @param string $mcString 服务器组名
     * @return object of ExtMemcache
     */
    public function getMc($mcString = 'mcMain')
    {
        self::$mcPool = array();
        if (isset(self::$mcPool[$mcString])) {
            return self::$mcPool[$mcString];
        }
        $serversConf = C('MEMCACHE_SERVERS');
        return self::$mcPool[$mcString] = new ExtMemcache($serversConf[$mcString]);
    }

    /**
     * 开启Memcache
     */
    public function setMcEnable()
    {
        $this->mcEnable = true;
    }

    /**
     * 关闭Memcache
     */
    public function setMcDisable()
    {
        $this->mcEnable = false;
    }
}
