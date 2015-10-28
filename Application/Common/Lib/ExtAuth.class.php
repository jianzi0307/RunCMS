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

use Think\Auth;

/**
 * 扩展认证类
 */
class ExtAuth extends Auth
{

    public function __construct()
    {
        //修改认证表前缀为app_
        $prefix = 'app_';
        $this->_config['AUTH_GROUP'] = $prefix.$this->_config['AUTH_GROUP'];
        $this->_config['AUTH_RULE'] = $prefix.$this->_config['AUTH_RULE'];
        $this->_config['AUTH_USER'] = $prefix.$this->_config['AUTH_USER'];
        $this->_config['AUTH_GROUP_ACCESS'] = $prefix.$this->_config['AUTH_GROUP_ACCESS'];
        if (C('AUTH_CONFIG')) {
            //可设置配置项 AUTH_CONFIG, 此配置项为数组。
            $this->_config = array_merge($this->_config, C('AUTH_CONFIG'));
        }
    }
}
