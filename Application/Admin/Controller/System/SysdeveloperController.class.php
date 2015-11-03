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
namespace Admin\Controller\System;

use Admin\Model\FileModel;
use Admin\Model\DbModel;

/**
 * Class SysdeveloperController
 *
 * 开发者面板
 * @package Admin\Controller
 */
class SysdeveloperController extends AdminController
{

    const __OK      = 0;
    const __ERROR_1 = 10001;
    const __ERROR_2 = 10002;

    protected $file = null;
    private $db = null;

    public function _initialize()
    {
        parent::_initialize();

        $this->file = new FileModel();
        $this->db = new DbModel();
    }

    /**
     * 生成模块
     */
    public function genModule()
    {
        $this->display();
    }

    /**
     * 生成模型
     */

    public function genModel()
    {
        $modules = $this->file->getModules();
        $tables = $this->db->getTables();
        $this->assign('modules', $modules);
        $this->assign('tables', $tables);
        $this->display();
    }

    /**
     * 生成控制器
     */
    public function genCtl()
    {
        $this->display();
    }

    /**
     * 生成模块处理
     */
    public function genModuleService()
    {
        $module = ucwords(Util::getSafeText(I('param.module')));
        if (!$module) {
            return;
        }
        $res = $this->file->createModuleDir($module);
        if ($res == 1) {
            $this->createDefaultClassFile($module);
            $this->response($res, self::__OK, "创建模块成功");
        }
        if ($res == -1) {
            $this->response($res, self::__ERROR_1, "模块已存在");
        }
        if ($res == -2) {
            $this->response($res, self::__ERROR_2, "应用目录不可写");
        }
    }
}
