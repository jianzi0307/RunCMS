<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/14
 * Time: 11:47
 */

return array(
    /** TOKEN过期时间，最长30分钟，最短20分钟无操作过期,单位秒 */
    'TOKEN_TIMEOUT'     => 1800,

    /** TOKEN 10分钟续期一次 */
    'TOKEN_RENEW_TIME'  => 600,

    /* TOKEN干扰码 */
    'TOKEN_DISTURB_KEY' => "Hello, I am verification code",

    /* AES加密设置 */
    'AES_KEY'           =>  '57256ec1110fed1a20a9e9a8663d3752'//AES密钥
);