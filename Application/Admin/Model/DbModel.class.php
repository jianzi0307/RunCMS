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

use Think\Model;

class DbModel
{
    //数据库句柄
    protected $db;

    //过滤掉系统表
    private $tableFilter = array(
            'app_useradmin' => 1,
            'app_auth_group' => 1,
            'app_auth_group_access' => 1,
            'app_auth_rule' => 1
        );

    public function __construct()
    {
        $this->db = M();
    }

    /**
     * 获取所有表名
     * @return array
     */
    public function getTables()
    {
        $tbls = $this->db->query($sql = 'show tables;');
        return $this->arrayFb($tbls);
    }

    public function getTableFields($tb)
    {
    }

    /**
     * 二维数组降维
     *
     * @param array $ary2d
     * @return array
     */
    private function arrayFb(Array $ary2d)
    {
        foreach ($ary2d as $value) {
            foreach ($value as $value) {
                if ($this->tableFilter[$value] > 0) {
                    continue;
                }
                $t[] = $value;
            }
        }
        return $t;
    }
}
