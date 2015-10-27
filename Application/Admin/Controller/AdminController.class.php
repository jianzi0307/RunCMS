<?php
namespace Admin\Controller;

use Lib\ExtAuth;
use Think\Controller;
use Think\Model;

/**
 * Class BaseController
 *
 * Controller基类
 * @package Admin\Controller
 */
class AdminController extends BaseController
{
    protected $loginUser = array();
    protected $userId = 0;
    protected $isAdmin = false;

    /**
     * 登录验证和权限验证
     */
    public function _initialize()
    {
        parent::_initialize();

        //检查用户是否登录
        $user = D('Useradmin');
        $this->userId = $user->isLogin();
        if (!$this->userId) {
            //exit($this->error('你还没有登录!', U('/Admin/Login')));
            $this->redirect(U('/Admin/Login', null, 0, '你还没有登录!'));
            exit;
        }
        if (!($this->loginUser = $user->getUserById($this->userId))) {
            exit($this->error('用户信息有误!', U('/Admin/Login')));
            $this->redirect(U('/Admin/Login', null, 0, '用户信息有误!'));
            exit;
        }
        //是否管理员
        $this->isAdmin = $user->isAdmin($this->userId);

        //echo MODULE_NAME,' - ',CONTROLLER_NAME,' - ',ACTION_NAME;exit;

        //检查用户权限
        //$auth = new ExtAuth();
        //if (!in_array($this->userId, C('ADMINISTRATOR'))) {
            //if (!$auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, $this->userId)) {
                //exit($this->show('没有权限访问,请联系管理员!'));
            //}
        //}

        //检查用户权限
        $menuModel = D('Menu');
        $access =   $this->accessControl();
        if ($access === false) {
            $this->error('403:禁止访问');
        } elseif ($access === null) {
            $dynamic = $this->checkDynamic();//检测分类栏目有关的各项动态权限
            if ($dynamic === null) {
                //检测非动态权限
                $rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
                if (!$menuModel->checkRule($rule, array('in','1,2'))) {
                    $this->error('未授权访问!');
                }
            } elseif ($dynamic === false) {
                $this->error('未授权访问!');
            }
        }

        //用户名首字母大写，用于显示
        $this->assign('uname', ucwords($this->loginUser['uname']));

        //生成系统菜单
        $this->assign('__MENU__', $this->getMenus());

        //----------->>> 没有子菜单的分组单独做处理 <<<---------------------------
        //获取专家系统权限
        $dataModelMenu = $menuModel->checkRule('Admin/Datamodel/index');
        $this->assign('showDataModelMenu', $this->isAdmin || $dataModelMenu);

        //获取专家系统权限
        $showExpsysMenu = $menuModel->checkRule('Admin/Faultdiagnosis/index');
        $this->assign('showExpsysMenu', $this->isAdmin || $showExpsysMenu);

        //获取案例法规库权限
        $showCaseLibrary = $menuModel->checkRule('Admin/Caselibrary/index');
        $this->assign('showCaseLibrary', $this->isAdmin || $showCaseLibrary);
        //----------->>> 没有子菜单的分组单独做处理 END <<<---------------------------

        //生成面包屑
        $this->nav($this->getNav());
    }

    /**
     * 检测是否是需要动态判断的权限
     * @return boolean|null
     *      返回true则表示当前访问有权限
     *      返回false则表示当前访问无权限
     *      返回null，则会进入checkRule根据节点授权判断权限
     */
    protected function checkDynamic()
    {
        if ($this->isAdmin) {
            return true;//管理员允许访问任何页面
        }
        return null;//不明,需checkRule
    }

    protected function checkFormToken()
    {
    }

    /**
     * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
     * @param mixed|string $controller
     * @return mixed
     */
    final public function getMenus($controller = CONTROLLER_NAME)
    {

        $menuModel = D('Menu');
        $res = $menuModel->getMenus($controller);
        if ($res == -1) {
            $this->error('控制器基类$menus属性元素配置有误');
        } elseif ($res == -2) {
            $this->error('主菜单下缺少子菜单，请去[系统后台/菜单管理]里添加');
        } else {
            return $res;
        }
    }

    /**
     * 获取导航路径
     * @return mixed
     */
    final public function getNav()
    {
        $menuModel = D('Menu');
        $nav = $menuModel->getNav(CONTROLLER_NAME);
        if (count($nav) > 0) {
            //删除第一项：系统
            array_shift($nav);

            //获取菜单URL
            foreach ($nav as $key => &$value) {
                $m =$menuModel->getRow(intval($value['id']));
                $value['url'] = '/'.MODULE_NAME.'/'.$m['url'];
            }

            //将分组放进面包屑
            if (count($nav) > 0) {
                //获取组名
                $menu = $menuModel->getRow(intval($nav[0]['id']));
                $menuGroupModel = D('MenuGroup');
                $group = $menuGroupModel->getRow(intval($menu['groupid']));
                $group['group'];
                //组没有URL
                $sec = array(
                    'id' => $group['id'],
                    'pid' => $group['menuid'],
                    'title' => $group['group'],
                );
                $nav = array_merge([$sec], $nav);
            }

            $fir = array(
                'id'=>0,
                'pid'=>0,
                'title'=>'首页',
                'url'=>'/'.MODULE_NAME
            );
            $nav = array_merge([$fir], $nav);
        }
        return $nav;
    }

    /**
     * 返回后台节点数据
     * @param bool $tree
     * @return array
     */
    final protected function returnNodes($tree = true)
    {
        static $tree_nodes = array();
        if ($tree && !empty($tree_nodes[(int)$tree])) {
            return $tree_nodes[$tree];
        }
        $menuModel = D('Menu');
        if ((int)$tree) {
            $list = $menuModel->getAll('sort asc');
            //M('Menu')->field('id,pid,title,url,tip,hide')->order('sort asc')->select();
            foreach ($list as $key => $value) {
                if (stripos($value['url'], MODULE_NAME) !== 0) {
                    $list[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
            $nodes = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'operator', $root = 0);
            foreach ($nodes as $key => $value) {
                if (!empty($value['operator'])) {
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        } else {
            $nodes = $menuModel->getAll('sort asc');
            //M('Menu')->field('title,url,tip,pid')->order('sort asc')->select();
            foreach ($nodes as $key => $value) {
                if (stripos($value['url'], MODULE_NAME)!==0) {
                    $nodes[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }

    /**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     */
    final protected function accessControl()
    {
        if ($this->isAdmin) {
            return true;//管理员允许访问任何页面
        }
        $allow = C('ALLOW_VISIT');
        $deny  = C('DENY_VISIT');
        $check = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
        if (!empty($deny) && in_array_case($check, $deny)) {
            return false;//非超管禁止访问deny中的方法
        }
        if (!empty($allow) && in_array_case($check, $allow)) {
            return true;
        }
        return null;//需要检测节点权限
    }

    /**
     * 生成默认类文件
     * @param string $module 模块名
     */
    public function createDefaultClassFile($module)
    {
        $this->compile($module, 'Base', 'base_controller', true);
        $this->compile($module, 'Index', 'controller');
    }

    /**
     * 编译模板生产对应类
     *
     * @param string $module 模块名
     * @param string $clsName 类名
     * @param string $tpl 模板名
     * @param bool $isBase 是否基类
     * @param string $type 类型(controller|model)
     */
    public function compile($module, $clsName, $tpl, $isBase = false, $type = 'controller')
    {
        $module = ucwords($module);
        $clsName = ucwords($clsName);
        $type = ucwords($type);

        $this->assign('__G_MODULE__', $module);
        $this->assign('__G_TYPE__', $type);
        $this->assign('__G_FILE__', $clsName.$type.".class.php");
        $this->assign('__G_DATE__', date('Y-m-d'));
        $this->assign('__G_TIME__', date('H:i:s'));
        $this->assign('__G_CLASS__', $clsName);
        $this->assign('__G_BASECLASS__', $isBase ? $type : 'Base'.$type);
        file_put_contents(APP_PATH.'/'.$module.'/'.$type.'/'.$clsName.$type.'.class.php', "<?php".PHP_EOL.$this->fetch(MODULE_PATH.'/Templates/'.$tpl.'.tpl'));
    }
}
