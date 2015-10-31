<?php
/**
 * ----------------------
 * FriendsController.class.php
 *
 * 华友汇相关接口
 *
 * Date: 2015/4/15
 * Time: 15:36
 * ----------------------
 */

namespace Api\Controller;

/**
 * 爱友汇
 * @package Api\Controller
 */
class FriendsController extends BaseController
{

    /**
     * 好友申请
     * @param string $token 凭证
     * @param int $toid 被申请人ID
     * @return string
     * {"errno":0,"errmsg":"发送申请成功","data":null}
     */
    public function reqFriend()
    {

    }

    /**
     * 申请列表
     * @param string $token 凭证
     * @return string
     * {
     *      "errno" :0,
     *      "errmsg":"获取成功",
     *      "data":[
     *          {
     *              "userid":xxx,//用户ID
     *              "userpic":"xxxx",//头像
     *              "honour":"xxxx",//头衔：公司名+职称
     *              "truename":"xxx",//真实名称
     *          },
     *          …
     *      ]
     * }
     */
    public function reqList()
    {

    }

    /**
     * 同意/拒绝接口
     */
    public function approve()
    {

    }

    /**
     * 关注对方
     */
    public function follow()
    {

    }

    /**
     * 举报不雅
     */
    public function report()
    {

    }

    /**
     * 删除好友
     */
    public function delFriend()
    {

    }
}
