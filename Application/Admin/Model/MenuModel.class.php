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

/**
 * 菜单模型
 */
class MenuModel extends BaseModel
{
    public function _initialize()
    {
        parent::_initialize();
        $this->mcPrefix = 'Menu::';
        $this->mcEnable = false;
    }

    protected $_validate = array(
        array('url', 'require', 'url必须填写'), //默认情况下用正则进行验证
    );

    /**
     * 获取树的根到子节点的路径
     * @param $id
     * @return array
     */
    public function getPath($id)
    {
        $path = array();
        $nav = $this
            ->where("id={$id}")
            ->field('id,pid,title')
            ->find();
        $path[] = $nav;

        //20151021 修改 > 1为 >= 1
        if ($nav['pid'] >= 1) {
            $path = array_merge(
                $this->getPath($nav['pid']),
                $path
            );
        }
        return $path;
    }

    /**
     * 获取导航路径
     * @param mixed|string $controller
     * @return array
     */
    public function getNav($controller = CONTROLLER_NAME)
    {
        //高亮主菜单
        $current = $this
            ->where("url like '%{$controller}/" . ACTION_NAME . "%'")
            ->field('id')
            ->find();

        return $this->getPath($current['id']);
    }

    /**
     * 判断是否存在子菜单
     * @param $ids
     * @return bool
     */
    public function hasChild($ids)
    {
        $rows = M('Menu')
            ->where(array('pid' => $ids[0]))
            ->count();
        return $rows > 0;
    }


    /**
     * 添加菜单，清除缓存
     * @param $data
     * @return bool
     */
    public function addMenu($data)
    {
        $res = $this->addRow($data);
        if ($res) {
            $this->mc->delete($this->mcPrefix.'ADMIN_MENU_LIST');
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新菜单，清除缓存
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateMenu($data, $id)
    {
        $res = $this->updateRows($data, $id);
        if ($res) {
            $this->mc->delete($this->mcPrefix.'ADMIN_MENU_LIST');
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除菜单，清除缓存
     * @param array $ids
     * @return bool
     */
    public function delMenus($ids)
    {
        $res = $this->delRowsInIds($ids);
        if ($res) {
            $this->mc->delete($this->mcPrefix.'ADMIN_MENU_LIST');
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取菜单
     * @param mixed|string $controller
     * @return int|array
     */
    public function getMenus($controller = CONTROLLER_NAME)
    {
        $userAdminModel = D('Useradmin');
        $cacheId = $this->mcPrefix . "ADMIN_MENU_LIST::".$userAdminModel->isLogin();
        if ($this->mcEnable && false != ($menus = $this->mc->get($cacheId))) {
            ;
        } else {
            if (empty($menus)) {
                // 获取主菜单
                $where['pid'] = 0;
                $where['hide'] = 0;
                if (!C('DEVELOP_MODE')) { // 是否开发者模式
                    $where['is_dev'] = 0;
                }
                $menus['main'] = $this
                    ->where($where)
                    ->order('sort asc')
                    ->select();

                $menus['child'] = array(); //设置子节点
                //高亮主菜单
                $current = $this
                    ->where("url like '%{$controller}/" . ACTION_NAME . "%'")
                    ->field('id')
                    ->find();
                if ($current) {
                    $nav = $this->getPath($current['id']);
                    $nav_first_title = $nav[0]['title'];

                    foreach ($menus['main'] as $key => $item) {
                        if (!is_array($item) || empty($item['title']) || empty($item['url'])) {
                            return -1;
                        }
                        if (stripos($item['url'], MODULE_NAME) !== 0) {
                            $item['url'] = MODULE_NAME . '/' . $item['url'];
                        }
                        // 判断主菜单权限
                        if (!$this->isAdmin() && !$this->checkRule($item['url'], AuthRuleModel::RULE_MAIN, null)) {
                            unset($menus['main'][$key]);
                            continue;//继续循环
                        }
                        // 获取当前主菜单的子菜单项
                        if ($item['title'] == $nav_first_title) {
                            $menus['main'][$key]['class'] = 'current';
                            //生成child树
                            //获取分组
                            $groups = $this
                                ->where("pid = {$item['id']}")
                                ->distinct(true)
                                ->field("`groupid`")
                                ->select();

                            if ($groups) {
                                $groups = array_column($groups, 'groupid');
                            } else {
                                $groups = array();
                            }

                            //获取二级分类的合法url
                            $where = array();
                            $where['pid'] = $item['id'];
                            $where['hide'] = 0;
                            if (!C('DEVELOP_MODE')) { // 是否开发者模式
                                $where['is_dev'] = 0;
                            }
                            $second_urls = $this
                                ->where($where)
                                ->getField('id,url');
                            if (!$this->isAdmin()) {
                                // 检测菜单权限
                                $to_check_urls = array();
                                foreach ($second_urls as $key => $to_check_url) {
                                    if (stripos($to_check_url, MODULE_NAME) !== 0) {
                                        $rule = MODULE_NAME . '/' . $to_check_url;
                                    } else {
                                        $rule = $to_check_url;
                                    }
                                    if ($this->checkRule($rule, AuthRuleModel::RULE_URL, null)) {
                                        $to_check_urls[] = $to_check_url;
                                    }
                                }
                            }
                            $menuGroupModel = D('MenuGroup');
                            // 按照分组生成子菜单树
                            foreach ($groups as $g) {
                                //只获取非隐藏的组
                                $menuGroup = $menuGroupModel->getRow(array("id={$g} and hide = 0"));
                                if (!$menuGroup) {
                                    continue;
                                }
                                $map = array('groupid' => $g);
                                if (isset($to_check_urls)) {
                                    if (empty($to_check_urls)) {
                                        // 没有任何权限
                                        continue;
                                    } else {
                                        $map['url'] = array('in', $to_check_urls);
                                    }
                                }
                                $map['pid'] = $item['id'];
                                $map['hide'] = 0;
                                // 是否开发者模式
                                if (!C('DEVELOP_MODE')) {
                                    $map['is_dev'] = 0;
                                }
                                $menuList = $this
                                    ->where($map)
                                    ->field('id,pid,title,url,groupid,tip,icon')
                                    ->order('sort asc')
                                    ->select();
                                $list = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
                                //过滤掉没有子菜单的分组，即，分组如果没有子菜单则不显示
                                if (count($list) == 0) {
                                    continue;
                                }
                                $menus['child'][$g] = array("group" => $menuGroup, "subMenu" => $list);
                            }
                            if (empty($menus['child'])) {
                                return -2;
                                //$this->error('主菜单下缺少子菜单，请去系统=》后台菜单管理里添加');
                            }
                        }
                    }
                }
                $this->mc->set($cacheId, $menus);
            }
        }
        return $menus;
    }

    /**
     * 判断是否为超管身份
     */
    public function isAdmin()
    {
        $user = D('Useradmin');
        return $user->isAdmin($user->isLogin());
    }

    /**
     * 权限检测
     * @param $rule
     * @param $type
     * @param string $mode
     * @return bool
     */
    final public function checkRule($rule, $type = AuthRuleModel::RULE_URL, $mode = 'url')
    {
        $user = D('Useradmin');
        if ($this->isAdmin()) {
            return true;//管理员允许访问任何页面
        }
        static $auth    =   null;
        if (!$auth) {
            $auth       =   new \Think\Auth();
        }
        if (!$auth->check($rule, $user->isLogin(), $type, $mode)) {
            return false;
        }
        return true;
    }
}
