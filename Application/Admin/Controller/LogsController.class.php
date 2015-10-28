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
namespace Admin\Controller;

class LogsController extends AdminController
{
    protected $db = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = M();
    }

    public function index()
    {
        $logsModel = D('Logs');
        $logs = $logsModel->order('createtime desc')->select();
        $this->assign('logs', $logs);
        $this->display();
    }
}
