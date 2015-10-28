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
use Lib\Util;

/**
 * Class MenugroupController
 * 菜单组管理
 * @package Admin\Controller
 */
class MenugroupController extends AdminController
{
    //成功
    const __OK__ = 0;
    //参数错误
    const __ERROR__0 = 99999;
    //添加菜单分组失败
    const __ERROR__1 = 24101;
    //更新菜单分组失败
    const __ERROR__2 = 24102;
    //删除菜单分组失败
    const __ERROR__3 = 24103;

    public function index()
    {
        $menuid  = intval(Util::getSafeText(I('get.id')));

        //所属菜单名
        $menuModel = D('Menu');
        $titles = $menuModel->where("id={$menuid}")->getField('id,title');
        $this->assign('menuTitle', $titles[$menuid]);

        //菜单分组列表
        $menuGroupModel = D('MenuGroup');
        $map = array("menuid={$menuid}");
        $groups = $menuGroupModel->getRows($map, 'sort desc');

        $this->assign('groups', $groups);
        $this->pageTitle('菜单分组管理');
        $this->display();
    }

    /**
     * 增加菜单分组
     */
    public function add()
    {
        $menuGroupModel = D('MenuGroup');
        if (IS_POST) {
            $menuid = Util::getSafeText(I('post.menuid'));
            $group = Util::getSafeText(I('post.group'));
            $sort = Util::getSafeText(I('post.sort'));
            $icon = Util::getSafeText(I('post.icon'));
            $hide = Util::getSafeText(I('post.hide'));

            if (empty($group) || empty($menuid)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'menuid'=>$menuid,
                'group'=>$group,
                'sort'=>$sort,
                'icon'=>$icon,
                'hide'=>$hide
            );
            $res = $menuGroupModel->addRow($data);
            if ($res) {
                //action_log('update_menu', 'Menu', $id, $this->userId);
                exit(Util::response(self::__OK__, "添加菜单分组成功!"));
            } else {
                exit(Util::response(self::__ERROR__1, "添加菜单分组失败!"));
            }
        } else {
            $menuid = intval(Util::getSafeText(I('get.menuid')));
            //所属菜单名
            $menuModel = D('Menu');
            $titles = $menuModel->where("id={$menuid}")->getField('id,title');
            $this->assign('menuId', $menuid);
            $this->assign('menuTitle', $titles[$menuid]);

            //菜单分组列表
            /*$menuGroupModel = D('MenuGroup');
            $map = array("menuid={$menuid}");
            $group = $menuGroupModel->getRow($map);
            $this->assign('group', $group);*/

            $this->assign('addAction', U('add'));
            $this->pageTitle('添加菜单分组');
            $this->display();
        }
    }

    /**
     * 编辑菜单分组
     * @param int $id
     */
    public function edit($id = 0)
    {
        $menuGroupModel = D('MenuGroup');
        if (IS_POST) {
            $menuid = Util::getSafeText(I('post.menuid'));
            $group = Util::getSafeText(I('post.group'));
            $sort = Util::getSafeText(I('post.sort'));
            $icon = Util::getSafeText(I('post.icon'));
            $hide = intval(Util::getSafeText(I('post.hide')));

            if (empty($group) || empty($menuid)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'menuid'=>$menuid,
                'group'=>$group,
                'sort'=>$sort,
                'icon'=>$icon,
                'hide'=>$hide
            );
            $res = $menuGroupModel->updateRows($data, intval($id));
            if ($res) {
                //action_log('update_menu', 'Menu', $id, $this->userId);
                exit(Util::response(self::__OK__, "更新菜单分组成功!"));
            } else {
                exit(Util::response(self::__ERROR__2, "更新菜单分组失败!"));
            }
        } else {
            $id = intval(Util::getSafeText(I('get.id')));
            //菜单分组列表
            $menuGroupModel = D('MenuGroup');
            $map = array("id={$id}");
            $group = $menuGroupModel->getRow($map);
            $this->assign('group', $group);

            //所属菜单名
            $menuModel = D('Menu');
            $menuid = $group['menuid'];
            $titles = $menuModel->where("id={$menuid}")->getField('id,title');
            $this->assign('menuId', $menuid);
            $this->assign('menuTitle', $titles[$menuid]);

            $this->assign('addAction', U('edit'));
            $this->pageTitle('更新菜单分组');
            $this->display('add');
        }
    }

    /**
     * 删除菜单分组
     */
    public function del()
    {
        $ids = array_unique((array) Util::getSafeText(I('id', 0)));
        $menuGroupModel = D('MenuGroup');
        //TODO:菜单分组被使用的情况下不能删除
        foreach ($ids as $id) {
        }
        $res = $menuGroupModel->delRowsInIds($ids);
        if ($res) {
            exit(Util::response(self::__OK__, "删除成功!"));
        } else {
            exit(Util::response(self::__ERROR__3, "删除菜单分组失败!"));
        }
    }
}
