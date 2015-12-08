<?php
/**
 * Created by PhpStorm.
 * User: jianzi0307
 * Date: 15/12/8
 * Time: 上午10:22
 */

namespace Lib;

class StringUtil
{
    /**
     * 用户名是否符合格式
     * 用户名为大写字母小写字母或下划线,并以字母开头,长度为6-20.
     * ^[a-zA-Z][\w\d_]{5,19}
     * @param $username
     * @return bool
     */
    public static function isUsername($username)
    {
        $ruler = '/^[a-zA-Z][\w\d_]{5,19}/';
        if (preg_match($ruler, $username)) {
            return true;
        } else {
            return false;
        }
    }

}
