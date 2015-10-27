<?php
namespace Admin\Model;

use Think\Model;

class ConfigModel extends BaseModel
{
    protected $_validate = array(
        array('name', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('name', 'strtoupper', self::MODEL_BOTH, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_BOTH),
    );

    public function _initialize()
    {
        parent::_initialize();
        //取缓存的Key前缀
        $this->mcPrefix = 'Config::';
    }

    /**
     * 从数据库获取配置并缓存到Memcache
     * @return array
     */
    public function cacheConfig()
    {
        /* 读取数据库中的配置 */
        $config = $this->mc->get($this->mcPrefix . 'DB_CONFIG_DATA');
        if (!$config) {
            $config = $this->kvlists();
            $this->mc->set($this->mcPrefix . 'DB_CONFIG_DATA', $config);
        }
        return $config;
    }

    /**
     * 添加配置项
     * @param $d
     * @return bool
     */
    public function newConfig($d)
    {
        $data = $this->addRow($d);
        if ($data) {
            $this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新配置项
     * @param $d
     * @param $id
     * @return bool
     */
    public function updateConfig($d, $id)
    {
        $data = $this->updateRows($d, intval($id));
        if ($data) {
            $this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
            return true;
        } else {
            return false;
        }
    }

    /**
     * 保存配置值
     * @param $c
     * @return bool
     */
    public function saveConfigValue($c)
    {
        if ($c && is_array($c)) {
            foreach ($c as $name => $value) {
                $map = array('name' => $name);
                $this->where($map)->setField('value', $value);
            }
        } else {
            return false;
        }
        $this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
        return true;
    }

    /**
     * 删除配置
     * @param $ids
     * @return bool
     */
    public function delConfig($ids)
    {
        $res = $this->delRowsInIds($ids);
        if ($res) {
            $this->mc->delete($this->mcPrefix.'DB_CONFIG_DATA');
            //记录行为
            //action_log('update_config','config',$id,UID);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取配置列表：键值对
     * @return array 配置数组
     */
    public function kvlists()
    {
        $map    = array('status' => 1);
        $data   = $this->where($map)->field('id,name,type,title,group,extra,remark,value')->select();
        $config = array();
        if ($data && is_array($data)) {
            foreach ($data as $value) {
                $config[$value['name']] = $this->parse($value['type'], $value['value']);
            }
        }
        return $config;
    }

    /**
     * 获取配置列表
     * @return mixed
     */
    public function lists()
    {
        $map = array('status' => 1);
        return $this->where($map)->field('id,name,type,title,group,extra,remark,value')->select();
    }

    /**
     * 根据配置类型解析配置
     * @param $type 配置类型
     * @param $value 配置值
     * @return array
     */
    private function parse($type, $value)
    {
        switch ($type) {
            case 3:
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if (strpos($value, ':')) {
                    $value  = array();
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k]   = $v;
                    }
                } else {
                    $value = $array;
                }
                break;
        }
        return $value;
    }
}
