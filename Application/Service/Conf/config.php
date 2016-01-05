<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/14
 * Time: 11:47
 */

return array(
    /** API文档标题 */
    'API_DOCUMENT_TITLE' => 'JDY平台API及设计说明',

    /** API网关地址 */
    'API_GATEWAY'       => 'http://jdy.t.com/service/',

    /** TOKEN过期时间,一周失效,单位秒 */
    'TOKEN_EXPIRES_IN'     => 604800,

    /** TOKEN 24小时续期一次, 单位秒 */
    'TOKEN_REFRESH_TIME'  => 86400,

    /** TOKEN干扰码 */
    'TOKEN_DISTURB_CODE' => "YKL*&2^KLJ12G%KPi&$",

    /** AES加密设置 */
    'AES_KEY'           =>  '57256ec1110fed1a20a9e9a8663d3752'//AES密钥
);