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
use Admin\Model\LogsModel;
use Lib\Util;

/**
 * Class ConfsettingController
 * 系统设置-配置管理
 * @package Admin\Controller
 */
class ConfsettingController extends AdminController
{
    //成功
    const __OK__ = 0;
    //参数错误
    const __ERROR__0 = 99999;
    //添加配置失败
    const __ERROR__1 = 23001;
    //更新配置失败
    const __ERROR__2 = 23002;
    //删除配置失败
    const __ERROR__3 = 23003;

    public function index()
    {
        /* 查询条件初始化 */
        $configModel = D('Config');
        $map  = array('status' => 1);
        if (isset($_GET['group'])) {
            $map['group'] = Util::getSafeText(I('group', 0));
        }
        if (isset($_GET['name'])) {
            $map['name'] = array('like', '%'.(string) Util::getSafeText(I('name')).'%');
        }
        $list = $configModel->lists();
        $this->assign('list', $list);
        $this->assign('editAction', U('edit'));
        $this->assign('delAction', U('del'));
        $this->display();
    }

    /**
     * 添加配置项
     */
    public function add()
    {
        if (IS_POST) {
            $name = Util::getSafeText(I('post.name'));
            $title = Util::getSafeText(I('post.title'));
            $type = Util::getSafeText(I('post.type'));
            $group = Util::getSafeText(I('post.group'));
            $extra = Util::getSafeText(I('post.extra'));
            $remark = Util::getSafeText(I('post.remark'));
            $value = Util::getSafeText(I('post.value'));
            $sort = Util::getSafeText(I('post.sort'));

            if (empty($name) || empty($title)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'name'=>$name,
                'type'=>$type,
                'group'=>$group,
                'extra'=>$extra,
                'title'=>$title,
                'remark'=>$remark,
                'value'=>$value,
                'sort'=>$sort,
                'create_time'=>time(),
                'update_time'=>time(),
                'status'=>1
            );
            $configModel = D('Config');
            $res = $configModel->newConfig($data);

            $this->logWriter = $this->logWriter
                ->action(LogsModel::ACT_ADD)
                ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
                ->exec($configModel->_sql());

            if ($res) {
                //$this->success('添加配置成功');
                $this->logWriter->ok();
                exit(Util::response(self::__OK__, "添加配置成功!"));
            } else {
                //$this->error('添加配置失败');
                $this->logWriter->fail();
                exit(Util::response(self::__ERROR__1, "添加配置失败!"));
            }
        } else {
            $this->assign('addAction', U('add'));
            $this->pageTitle('新增配置');
            $this->display();
        }
    }

    /**
     * 编辑
     * @param int $id
     */
    public function edit($id = 0)
    {
        $configModel = D('Config');
        if (IS_POST) {
            $name = Util::getSafeText(I('post.name'));
            $title = Util::getSafeText(I('post.title'));
            $type = Util::getSafeText(I('post.type'));
            $group = Util::getSafeText(I('post.group'));
            $extra = Util::getSafeText(I('post.extra'));
            $remark = Util::getSafeText(I('post.remark'));
            $value = Util::getSafeText(I('post.value'));
            $sort = Util::getSafeText(I('post.sort'));

            if (empty($name) || empty($title)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'name'=>$name,
                'type'=>$type,
                'group'=>$group,
                'extra'=>$extra,
                'title'=>$title,
                'remark'=>$remark,
                'value'=>$value,
                'sort'=>$sort,
                'create_time'=>time(),
                'update_time'=>time(),
                'status'=>1
            );
            $res = $configModel->updateConfig($data, $id);

            $this->logWriter = $this->logWriter
                ->action(LogsModel::ACT_UPDATE)
                ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
                ->exec($configModel->_sql());

            if ($res) {
                $this->logWriter->ok();
                exit(Util::response(self::__OK__, "更新配置成功!"));
            } else {
                $this->logWriter->fail();
                exit(Util::response(self::__ERROR__2, "更新配置失败!"));
            }
        } else {
            $info = array();
            $info = $configModel->getRow(intval($id));
            if (false === $info) {
                $this->error('获取配置信息错误');
                exit;
            }
            $this->assign('addAction', U('edit'));
            $this->assign('info', $info);
            $this->pageTitle('更新配置');
            $this->display('add');
        }
    }

    /**
     * 删除配置
     */
    public function del()
    {
        $ids = array_unique((array) Util::getSafeText(I('id', 0)));
        $configModel = D('Config');
        $res = $configModel->delConfig($ids);

        $this->logWriter = $this->logWriter
            ->action(LogsModel::ACT_DELETE)
            ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
            ->exec($configModel->_sql());

        if ($res) {
            $this->logWriter->ok();
            exit(Util::response(self::__OK__, "删除成功!"));
        } else {
            $this->logWriter->fail();
            exit(Util::response(self::__ERROR__3, "删除配置失败!"));
        }
    }
}
