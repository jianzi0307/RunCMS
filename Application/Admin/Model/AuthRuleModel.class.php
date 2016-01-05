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
 * Class AuthRuleModel
 * 权限规则模型
 * @package Admin\Model
 */
class AuthRuleModel extends BaseModel
{
    const RULE_URL = 1;
    const RULE_MAIN = 2;
}
