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

use Admin\Model\AuthGroupModel;
use Admin\Model\AuthRuleModel;
use Lib\Util;

class UsergroupController extends AdminController
{
    //成功
    const __OK__ = 0;
    //参数错误
    const __ERROR__0 = 99999;
    //添加组失败
    const __ERROR__1 = 25001;
    //修改组失败
    const __ERROR__2 = 25002;
    //删除组失败
    const __ERROR__3 = 25003;
    const __ERROR__4 = 25004;
    const __ERROR__5 = 25005;

    protected $db = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = M();
    }

    public function index()
    {
        $groupList =  $this->db->table('app_auth_group')->select();
        $this->assign('groupList', $groupList);
        $this->pageTitle("用户组管理");
        $this->display();
    }

    /**
     * 添加用户组
     */
    public function add()
    {
        if (IS_POST) {
            $title = Util::getSafeText(trim(I('param.title')));
            $status = Util::getSafeText(I('param.status'));
            $description = Util::getSafeText(trim(I('param.description')));

            if (empty($title)|| empty($description)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'title' => $title,
                'status' => $status,
                'description' => $description,
                'module'=> 'admin',
                'type' => AuthGroupModel::TYPE_ADMIN
            );
            $authGroupModel = D('AuthGroup');
            $res = $authGroupModel->addRow($data);//$this->db->table('app_auth_group')->data($sysAuthGroup)->add();
            if ($res) {
                exit(Util::response(self::__OK__, "添加组成功!"));
            } else {
                exit(Util::response(self::__ERROR__1, "添加组失败!"));
            }
        } else {
            $this->assign('addAction', U('add'));
            $this->pageTitle("添加用户组");
            $this->display();
        }
    }

    /**
     * 编辑组
     * @param $id
     */
    public function edit($id = 0)
    {
        $authGroupModel = D('AuthGroup');
        if (IS_POST) {
            $title = Util::getSafeText(trim(I('param.title')));
            $status = Util::getSafeText(I('param.status'));
            $description = Util::getSafeText(trim(I('param.description')));

            if (empty($title)|| empty($description)) {
                exit(Util::response(self::__ERROR__0, "参数错误!"));
            }

            $data = array(
                'title' => $title,
                'status' => $status,
                'description' => $description,
                'module'=> 'admin',
                'type' => AuthGroupModel::TYPE_ADMIN
            );
            $res = $authGroupModel->updateRows($data, intval($id));
            if ($res) {
                exit(Util::response(self::__OK__, "修改组成功!"));
            } else {
                exit(Util::response(self::__ERROR__2, "修改组失败!"));
            }
        } else {
            $id = intval(Util::getSafeText(I('get.id')));
            $map = array("id={$id}");
            $group = $authGroupModel->getRow($map);
            $this->assign('group', $group);
            $this->pageTitle("修改用户组");
            $this->display('add');
        }
    }

    /**
     * 删除组
     */
    public function del()
    {
        $ids = array_unique((array) Util::getSafeText(I('id', 0)));
        $authGroupModel = D('AuthGroup');
        $res = $authGroupModel->delRowsInIds($ids);
        if ($res) {
            exit(Util::response(self::__OK__, "删除组成功!"));
        } else {
            exit(Util::response(self::__ERROR__3, "删除组失败!"));
        }
    }

    /**
     * 权限
     */
    public function priv()
    {
        $authGroupModel = D('AuthGroup');
        $this->updateRules();
        $auth_group = $authGroupModel
            ->where(
                array(
                    'status'=>array('egt',0),
                    'module'=>'admin',
                    'type'=>AuthGroupModel::TYPE_ADMIN
                )
            )
            ->getfield('id,id,title,rules');
        $node_list = $this->returnNodes();

        $authRuleModel = D('AuthRule');
        $map = array(
            'module'=>'admin',
            'type'=>AuthRuleModel::RULE_MAIN,
            'status'=>1
        );
        $main_rules  = $authRuleModel
            ->where($map)
            ->getField('name,id');
        $map = array(
            'module'=>'admin',
            'type'=>AuthRuleModel::RULE_URL,
            'status'=>1
        );
        $child_rules = $authRuleModel
            ->where($map)
            ->getField('name,id');

        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list', $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int) $_GET['id']]);

        $this->pageTitle("权限管理");
        $this->display();
    }

    /**
     * 写入组权限
     */
    public function writeGroup()
    {
        if (isset($_POST['rules'])) {
            sort($_POST['rules']);
            $_POST['rules'] = implode(',', array_unique($_POST['rules']));
        }
        $_POST['module'] = 'admin';
        $_POST['type'] =  AuthGroupModel::TYPE_ADMIN;
        $authGroupModel =  M('AuthGroup');
        $data = $authGroupModel->create();
        if ($data) {
            if (empty($data['id'])) {
                $r = $authGroupModel->add();
            } else {
                $r = $authGroupModel->save();
            }
            if ($r === false) {
                exit(Util::response(self::__ERROR__4, "操作失败!"));
                //$this->error('操作失败'.$authGroupModel->getError());
            } else {
                exit(Util::response(self::__OK__, "操作成功!"));
            }
        } else {
            exit(Util::response(self::__ERROR__5, "操作失败!"));
        }
    }

    /**
     * 成员管理
     */
    public function user()
    {
        $this->pageTitle("成员管理");
        $this->display();
    }

    /**
     * 根据菜单更新规则库
     * @return bool
     */
    public function updateRules()
    {
        //需要新增的节点必然位于$nodes
        $nodes = $this->returnNodes(false);
        $authRuleModel = M('AuthRule');
        $map = array(
            'module'=>'admin',
            'type'=>array('in','1,2')
        );//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules
        $rules = $authRuleModel
            ->where($map)
            ->order('name')
            ->select();

        //构建insert数据
        $data = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value) {
            $temp['name'] = $value['url'];
            $temp['title'] = $value['title'];
            $temp['module'] = 'admin';
            if ($value['pid'] >0) {
                $temp['type'] = AuthRuleModel::RULE_URL;
            } else {
                $temp['type'] = AuthRuleModel::RULE_MAIN;
            }
            $temp['status'] = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;//去除重复项
        }

        $update = array();//保存需要更新的节点
        $ids    = array();//保存需要删除的节点的id
        foreach ($rules as $index => $rule) {
            $key = strtolower($rule['name'].$rule['module'].$rule['type']);
            if (isset($data[$key])) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']]=$rule;
            } elseif ($rule['status'] == 1) {
                $ids[] = $rule['id'];
            }
        }
        if (count($update)) {
            foreach ($update as $k => $row) {
                if ($row!=$diff[$row['id']]) {
                    $authRuleModel
                        ->where(array('id'=>$row['id']))
                        ->save($row);
                }
            }
        }
        if (count($ids)) {
            $authRuleModel
                ->where(array('id'=>array('IN',implode(',', $ids))))
                ->save(array('status'=>-1));
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if (count($data)) {
            $res = $authRuleModel->addAll(array_values($data));
        }
        if ($authRuleModel->getDbError()) {
            //trace('['.__METHOD__.']:'.$AuthRule->getDbError());
            return false;
        } else {
            return true;
        }
    }
}
