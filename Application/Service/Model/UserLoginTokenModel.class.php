<?php
/**
 * Created by PhpStorm.
 * User: jianzi0307
 * Date: 15/12/14
 * Time: 下午10:42
 */

namespace Service\Model;


use Common\Model\BaseModel;

class UserLoginToken extends BaseModel
{

    /**
     * 检查token
     * @param $username
     * @param $token
     * @return bool
     */
    public function checkToken($username, $token)
    {
        $row = $this->getRow(array(
            'username'=>$username,
            'token'=>$token
        ));
        if ($row) {
            return true;
        } else {
            return false;
        }
    }
}
