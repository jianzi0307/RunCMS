<?php
/**
 * Created by PhpStorm.
 * User: jianzi0307
 * Date: 15/10/29
 * Time: 上午12:01
 */
namespace Admin\Model;
use Common\Model\BaseModel;

/**
 * Class LogsModel
 * 后台操作日志模型
 *
 * 用法：
 * $logModel->action(ActionConf::ADD)
 *          ->query(M()->_sql())
 *          ->ok();
 * @package Admin\Model
 */
class LogsModel extends BaseModel
{
    private $sqlMap;

    const ACT_ADD = 'add';
    const ACT_UPDATE = 'update';
    const ACT_DELETE = 'delete';
    const ACT_LOGIN = 'login';
    const ACT_LOGOUT = 'logout';

    public function _initialize()
    {
        parent::_initialize();

        $this->sqlMap = array(
            'ip' => get_client_ip(),
            'createtime' => time()
        );
    }

    /**
     * 记录行为
     * @param $act
     * @see ActionConf.class.php
     * @return $this
     */
    public function action($act)
    {
        $this->mergeMap(array(
            'action' => $act
        ));
        return $this;
    }


    /**
     * 被调用的类和方法
     * @param $method
     * @return $this|mixed
     */
    public function called($method)
    {
        $this->mergeMap(array(
            'class' => $method
        ));
        return $this;
    }

    /**
     * 执行的操作语句
     * @param string $sql
     * @return $this|mixed
     */
    public function exec($sql)
    {
        $this->mergeMap(array(
            'sql' => $sql
        ));
        return $this;
    }

    /**
     * 失败
     * @return $this
     */
    public function fail()
    {
        $this->mergeMap(array(
            'status' => 0
        ));
        $this->mergeUser();
        $this->add($this->sqlMap);
    }

    /**
     * 成功
     * @return $this
     */
    public function ok()
    {
        $this->mergeMap(array(
            'status' => 1
        ));
        $this->mergeUser();
        $this->add($this->sqlMap);
    }

    /**
     * 设置用户
     */
    public function setUser($uname)
    {
        $this->mergeMap(array(
            'uname' => $uname
        ));
        return $this;
    }

    /**
     * 当前登录用户
     */
    private function mergeUser()
    {
        if (!array_key_exists('uname', $this->sqlMap)) {
            $userAdmin = D('Useradmin');
            $uname = $userAdmin->getUnameFromCookie();
            $uname = $uname ? $uname : '-1';
            $this->mergeMap(array(
                'uname' => $uname
            ));
        }
        return $this;
    }

    /**
     * 合并
     * @param $ary
     */
    private function mergeMap($ary)
    {
        $this->sqlMap = array_merge($this->sqlMap, $ary);
    }
}
