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

use Admin\Model\LogsModel;
use Lib\Util;

/**
 * Class MenusettingController
 * 系统设置-菜单管理
 * @package Admin\Controller
 */
class MenusettingController extends AdminController
{
    //成功
    const __OK__ = 0;
    //参数错误
    const __ERROR__0 = 99999;
    //添加菜单失败
    const __ERROR__1 = 24001;
    //更新菜单失败
    const __ERROR__2 = 24002;
    //删除菜单失败
    const __ERROR__3 = 24003;
    //存在子菜单，不允许删除
    const __ERROR__4 = 24004;

    /**
     * 菜单列表
     */
    public function index()
    {
        $pid  = Util::getSafeText(I('get.pid', 0));
        $menuModel = D('Menu');
        if ($pid) {
            $data = $menuModel->getRow(intval($pid));
            $this->assign('data', $data);
        }
        $title      =   Util::getSafeText(trim(I('get.title')));
        $all_menu   =   M('Menu')->getField('id,title');
        $map['pid'] =   $pid;
        if ($title) {
            $map['title'] = array('like', "%{$title}%");
        }
        $list       =   $menuModel->getRows($map, 'sort asc,id asc');
        $menuGroupModel = D('MenuGroup');
        foreach ($list as &$item) {
            $group = $menuGroupModel->getRow(array("id=".$item['groupid']));
            $item['group'] = $group['group'];
        }
        //int_to_string($list, array('hide'=>array(1=>'是',0=>'否'), 'is_dev'=>array(1=>'是',0=>'否')));
        int_to_icon($list, array('hide'=>array(1=>'icon-eye-close',0=>'icon-eye-open')));
        int_to_string($list, array('is_dev'=>array(1=>'是',0=>'否')));
        if ($list) {
            foreach ($list as &$key) {
                if ($key['pid']) {
                    $key['up_title'] = $all_menu[$key['pid']];
                }
            }
            $this->assign('list', $list);
        }
        $this->display();
    }

    /**
     * 添加菜单
     */
    public function add()
    {
        $menuModel = D('Menu');
        if (IS_POST) {
            $title = Util::getSafeText(I('post.title'));
            $url = Util::getSafeText(I('post.url'));
            $pid = Util::getSafeText(I('post.pid'));
            $groupid = Util::getSafeText(I('post.groupid'));
            $hide = Util::getSafeText(I('post.hide'));
            $is_dev = Util::getSafeText(I('post.is_dev'));
            $tip = Util::getSafeText(I('post.tip'));
            $sort = Util::getSafeText(I('post.sort'));
            $icon = Util::getSafeText(I('post.icon'));

            if (empty($url) || empty($title)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'url'=>$url,
                'pid'=>$pid,
                'groupid'=>$groupid,
                'hide'=>$hide,
                'title'=>$title,
                'is_dev'=>$is_dev,
                'tip'=>$tip,
                'sort'=>$sort,
                'icon'=>$icon
            );
            $res = $menuModel->addMenu($data);

            $this->logWriter = $this->logWriter
                ->action(LogsModel::ACT_ADD)
                ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
                ->exec($menuModel->_sql());

            if ($res) {
                $this->logWriter->add();
                //action_log('update_menu', 'Menu', $id, $this->userId);
                exit(Util::response(self::__OK__, "添加菜单成功!"));
            } else {
                $this->logWriter->fail();
                exit(Util::response(self::__ERROR__1, "添加菜单失败!"));
            }
        } else {
            $pid = intval(Util::getSafeText(I('pid')));
            $this->assign('info', array('pid'=>$pid));

            $menus = $menuModel->getAll();
            $treeModel = D('Common/Tree');
            $menus = $treeModel->toFormatTree($menus);
            $menus = array_merge(
                array(
                    0 => array(
                        'id'=>0,
                        'title_show'=>'顶级菜单'
                    )
                ),
                $menus
            );
            $menuGroupModel = D('MenuGroup');
            $groups = $menuGroupModel->getRows(array("menuid={$pid}"));
            $this->assign('groups', $groups);
            $this->assign('menus', $menus);
            $this->assign('addAction', U('add'));
            $this->pageTitle('添加菜单');
            $this->display();
        }
    }

    /**
     * 编辑菜单
     * @param int $id
     */
    public function edit($id = 0)
    {
        $menuModel = D('Menu');
        if (IS_POST) {
            $title = Util::getSafeText(I('post.title'));
            $url = Util::getSafeText(I('post.url'));
            $pid = Util::getSafeText(I('post.pid'));
            $groupid = Util::getSafeText(I('post.groupid'));
            $hide = Util::getSafeText(I('post.hide'));
            $is_dev = Util::getSafeText(I('post.is_dev'));
            $tip = Util::getSafeText(I('post.tip'));
            $sort = Util::getSafeText(I('post.sort'));
            $icon = Util::getSafeText(I('post.icon'));

            if (empty($url) || empty($title)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'url'=>$url,
                'pid'=>$pid,
                'groupid'=>$groupid,
                'hide'=>$hide,
                'title'=>$title,
                'is_dev'=>$is_dev,
                'tip'=>$tip,
                'sort'=>$sort,
                'icon'=>$icon
            );
            $res = $menuModel->updateMenu($data, intval($id));

            $this->logWriter = $this->logWriter
                ->action(LogsModel::ACT_UPDATE)
                ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
                ->exec($menuModel->_sql());

            if ($res) {
                $this->logWriter->ok();
                exit(Util::response(self::__OK__, "更新菜单成功!"));
            } else {
                $this->logWriter->fail();
                exit(Util::response(self::__ERROR__2, "更新菜单失败!"));
            }
        } else {
            $info = $menuModel->getRow(array("id={$id}"));
            $menus = $menuModel->getAll();
            $treeModel = D('Common/Tree');
            $menus = $treeModel->toFormatTree($menus);
            $menus = array_merge(
                array(
                    0=>array(
                        'id'=>0,
                        'title_show'=>'顶级菜单'
                    )
                ),
                $menus
            );
            $this->assign('menus', $menus);
            if (false === $info) {
                $this->error('获取后台菜单信息错误');
            }

            $pid = $info['pid'];
            $menuGroupModel = D('MenuGroup');
            $groups = $menuGroupModel->getRows(array("menuid={$pid}"));
            $this->assign('groups', $groups);
            $this->assign('info', $info);
            $this->pageTitle('编辑菜单');
            $this->display('add');
        }
    }

    /**
     * 删除菜单
     */
    public function del()
    {
        $ids = array_unique((array) Util::getSafeText(I('id', 0)));
        $menuModel = D('Menu');
        //检查是否存在子菜单，存在子菜单则不允许删除
        $hasChild = $menuModel->hasChild($ids);
        if ($hasChild) {
            exit(Util::response(self::__ERROR__4, "该菜单包含子菜单，不允许删除!"));
        }
        $res = $menuModel->delMenus($ids);

        $this->logWriter = $this->logWriter
            ->action(LogsModel::ACT_DELETE)
            ->called(ltrim(__CLASS__, __NAMESPACE__).'::'.__FUNCTION__)
            ->exec($menuModel->_sql());

        if ($res) {
            $this->logWriter->ok();
            exit(Util::response(self::__OK__, "删除菜单成功!"));
        } else {
            $this->logWriter->fail();
            exit(Util::response(self::__ERROR__3, "删除菜单失败!"));
        }
    }
}
