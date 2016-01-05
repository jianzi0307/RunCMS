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
namespace Admin\Model;

use Common\Model\BaseModel;
use Think\Model;
use Lib\Util;

/**
 * Class UseradminModel
 *
 * 系统用户表
 * @package Admin\Model
 */
class UseradminModel extends BaseModel
{

    public function _initialize()
    {
        parent::_initialize();
        $this->mcPrefix = 'Useradmin::';
    }

    /**
     * 若用户已登录 返回用户 id, 否则返回false;
     */
    public function isLogin()
    {
        $u = $this->getUserInfoFromCookie();
        if ($u) {
            return $u['uid'];
        } else {
             return 0;
        }
    }

    /**
     * 检测当前用户是否为管理员
     * @param int $uid
     * @return bool
     */
    public function isAdmin($uid = null)
    {
        $uid = is_null($uid) ? $this->isLogin() : $uid;
        return $uid && (in_array(intval($uid), C('ADMINISTRATOR')));
    }

    /**
     * 用cookie的方式判断用户登录状态(只判断cookie)
     *
     * @version $Id$
     * @return array 用户会话信息; false 用户未登录
     */
    public function getUserInfoFromCookie()
    {
        if (isset($_COOKIE['u'])) {
            $str = base64_decode($_COOKIE['u']);
            return (array)json_decode($str, true);
        } else {
            return array();
        }
    }

    /**
     *
     * @param $uname 用户登录名可能为用户名、手机号或邮件地址。
     * @param $upasswd 密码
     * @param int $time Cookie过期时间
     * @return bool|int 成功:array,cookies; 密码错误, 用户名错误:1; 数据库查询错误2;
     */
    public function userLogin($uname, $upasswd, $time = 86400000)
    {
        $user = $this->getUserByName($uname);
        if ($user) {
            if (Util::genMd5Pwd($upasswd) == $user['passwd']) {
                //检查账号是否禁用或者过期
                if ($user['blocked'] == 1) {
                    return -3;
                }
                //echo $user['expirtime'],'===',time();exit;
                //账户已过期
                if ($user['expirtime'] != 0 && (time() - $user['expirtime']) > 0) {
                    return -4;
                }
                //纪录登录IP,纪录登录积分等处理
                //$loginip = Util::getIntIp();
                //$this->changeUser($user['id'],array('ltime'=>date('Y-m-d H:i:s'),'loginip'=>$loginip,));
                $_user = array();
                $_user['uid'] = $user['id'];
                $_user['uname'] = $user['uname'];
                $_user['nickname'] = $user['nickname'];
                $_user['email'] = $user['email'];
                $u = base64_encode(json_encode($_user));
                $res = Util::setCookie('u', $u, $time);
                if ($res) {
                    return $user['id'];
                }
            } else {
                return -2;
            }
        } else {
            return -1;
        }
        return false;
    }

    /**
     * 根据用户名获取用户信息
     *
     * @param string $uname 用户名
     * @return array|bool|mixed
     */
    public function getUserByName($uname)
    {
        $uid = $this->getUidByName($uname);
        if ($uid) {
            return $this->getUserById($uid);
        } else {
            return false;
        }
    }

    /**
     * 更改当前用户的密码
     * @param string $newpwd 新密码（明码）
     * @return bool
     */
    public function updatePwd($newpwd)
    {
        $uid = $this->isLogin();
        $user = $this->getUserById($uid);
        //$cacheId = $this->mcPrefix . "uid:{$user['uname']}";
        $uid = $this->isLogin();
        $md5pwd = Util::genMd5Pwd(trim($newpwd));
        $data = array(
            'passwd'=>$md5pwd
        );
        $res = $this->updateRows($data, intval($uid));
        if ($res) {
            $this->mc->delete($this->mcPrefix . $uid);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新memcached
     * @param $id
     */
    public function updateUserInfo($id)
    {
        $this->mc->delete($this->mcPrefix . $id);
    }

    /**
     * 根据用户名获取用户ID
     *
     * @param string $uname 用户名
     * @return int
     */
    public function getUidByName($uname)
    {
        //UserAdmin::uid:username
        $cacheId = $this->mcPrefix . "uid:{$uname}";
        if ($this->mcEnable && false != ($uid = $this->mc->get($cacheId))) {
            ;
        } else {
            //TODO:读写分离
            $uid = $this
                ->field('id')
                ->where("uname='".$uname."'")
                ->find();
            if (!empty($uid)) {
                $this->mc->set($cacheId, $uid, $_expire = 0, $_compress = 0);
            }
        }
        return $uid['id'] ? $uid['id'] : 0;
    }

    /**
     * 取用户基本信息
     *
     * @param int $id 用户id
     * @return array|mixed 返回用户资料
     */
    public function getUserById($id)
    {
        //UserAdmin::userId
        $cacheId = $this->mcPrefix . $id;
        if ($this->mcEnable && false !== ($v = $this->mc->get($cacheId))) {
            ;
        } else {
            $v = $this->find($id);
            if ($v) {
                $this->mc->set($cacheId, $v);
            }
            /*else {
                $this->mc->set($cacheId, '', $_expire=10, $_compress=0);
            }*/
        }
        if ($v) {
            //.....
            return $v;
        }
        return array();
    }

    /**
     * 获取用户名
     * @return null
     */
    public function getUnameFromCookie()
    {
        $user = $this->getUserInfoFromCookie();
        if ($user) {
            return $user['uname'];
        } else {
            return null;
        }
    }

    /**
     * 改变cookie auth数组中某个值
     * @version $Id$
     * @param array(
     *  $key        键名
     *  $value
     * )
     * @return array 用户会话信息;
    */
    public function setAuth($array)
    {
        $u = $this->getUserInfoFromCookie();
        if ($u) {
            foreach ($array as $key => $value) {
                $u[$key] = $value;
            }
            $ustr = Util::encrypt(json_encode($u));
            Util::setRawCookie('u', $ustr, 86400);
            return $u;
        }
        return false;
    }

    /**
     *  更新用户信息  //适用于user  等 uid为唯一的表 。
     *  @param $id 用户id
     *  @param $row 要更新的内容  数据库字段名为索引,用户值为value
     */
    /*public function changeUser($id, $row)
    {
        try {
        	//$this->where('id='.$id)->save();
            $where = $dbw->quoteInto('id=?', $id);
            $dbw->update('user', $row, $where);
            if (isset($row['domain'])) {
                $ip = Util::getRealIp();
                $login = $this->isLogin();
                $this->log("changeDomain:{$id}:{$domain}:" . ($ip ? $ip : 'noip') . ($login ? $login : '匿名') . date('Y-m-d H:i:s'));
            }
            $cacheId = $this->mcPrefix . $id;
            if( $this->mcEnable && ($v = $this->mc->get($cacheId)) ) {
                foreach($row as $key => $value) {
                    $v[$key] = $value;
                }
                $this->mc->set($cacheId, $v);
            }

            //更新缓存表时间
            $count = array();
            $utime = date('Y-m-d H:i:s');
            $db = $this->getDb('dbmemo');
            $sql = "UPDATE user_count_memory SET utime = '{$utime}' WHERE id = ?";
            $db->query($sql, $id);
            $cacheId = $this->mcPrefix . "count:{$id}";
            if($this->mcEnable && false !== ($count = $this->mc->get($cacheId))) {
                $count['utime'] = $utime;
                $this->mc->set($cacheId, $count);
            }
            return true;
        }
        catch (Exception $e) {
            $this->log('file:' . $e->getFile() . ' line:' . $e->getLine()
            . $e->getMessage() . $e->getTraceAsString());
        }

        return false;
    }*/

    /**
     * 用户退出登录
     */
    public function userLogout()
    {
        Util::setRawCookie('u', '', -1);
    }
}
