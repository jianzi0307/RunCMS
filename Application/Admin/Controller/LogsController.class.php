<?php
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
        $logs = $this->db->table('app_logs')->select();
        $this->assign('logs', $logs);
        $this->display();
    }
}
