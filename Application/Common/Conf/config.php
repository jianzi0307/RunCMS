<?php
// +----------------------------------------------------------------------
//	公共配置
// +----------------------------------------------------------------------
// 
return array(
    //主域名
    'SITE_DOMAIN'   => 'hyh.cc',

    //是否开启令牌验证 默认关闭
    'TOKEN_ON'      => true,
    //令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_NAME'    => '__hash__',
    //令牌哈希验证规则 默认为MD5
    'TOKEN_TYPE'    => 'md5',
    //令牌验证出错后是否重置令牌 默认为true
    'TOKEN_RESET'   => true,

    //URL模式
    //普通模式：0，PATHINFO模式：1，REWRITE模式：2，兼容模式：3
    'URL_MODEL'     => 2,

    //伪静态后缀
    'URL_HTML_SUFFIX'=>'',

    //URL不区分大小写
    'URL_CASE_INSENSITIVE' => true,

    //密码干扰码，跟用户密码一起md5后存入库
    'PASSWORD_MASK'     =>  "*&^^|%s|&$",

    //是否允许多模块
    'MULTI_MODULE'      =>  true,

    // 禁止访问的模块列表
    'MODULE_DENY_LIST'  =>  array('Common','Runtime'),

    //公共数据库配置
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    //'DB_HOST'             =>  PUBLIC_CONNECT ? '124.207.68.95' : '192.168.1.11', // 服务器地址
    'DB_NAME'               =>  'db_tpsite',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    //'DB_PWD'              =>  'rndchina123.',          // 密码
    'DB_PWD'                =>  '111',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'app_',    // 数据库表前缀
    'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查 3.2.3版本废弃
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8

    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  0, // 指定从服务器序号


    //'DB_BIND_PARAM'       =>  true, // 数据库写入数据自动参数绑定
    'DB_DEBUG'              =>  true,  // 数据库调试模式 3.2.3新增
    'DB_LITE'               =>  false,  // 数据库Lite模式 3.2.3新增

    'AUTOLOAD_NAMESPACE'    => array(
        'Lib' => APP_PATH.'Common/Lib'
        //'Data' => APP_PATH.'Admin/Data'
    ),

    //Memcache集群配置
    'MEMCACHE_SERVERS' => array(
        //mcMain服务器组
        'mcMain' => array(
//            array('host' => '127.0.0.1', 'port' => '20000', 'weight' => 2),
//            array('host' => '127.0.0.1', 'port' => '20001', 'weight' => 2),
//            array('host' => '127.0.0.1', 'port' => '20002', 'weight' => 2)
//            array('host' => PUBLIC_CONNECT ? '124.207.68.95' : '192.168.1.11', 'port' => '11211', 'weight' => 2)
        ),
        //其他服务器组
        //'other' => array(
        //		array('host' => 'xxx.xxx.xxx.xxx', 'port' => 'xxx', 'weight' => 1),
        //		array('host' => 'xxx.xxx.xxx.xxx', 'port' => 'xxx', 'weight' => 2),
        //		array('host' => 'xxx.xxx.xxx.xxx', 'port' => 'xxx', 'weight' => 3),
        //	),
    ),

    //Redis配置
    'REDIS_SERVERS' => array(),

    //MongoDB配置
    'MONGO_SERVERS' => array()
);
