<?php
/**
 * ----------------------
 * SearchController.class.php
 *
 * 搜索接口
 * 搜好友、商品、项目
 *
 * Date: 2015/4/15
 * Time: 15:12
 * ----------------------
 */

namespace Api\Controller;

/**
 * 搜索相关接口
 *
 * 提供查询好友信息、商品信息、爱融汇等信息相关接口
 *
 * @package Api\Controller
 * @author jianzi0307@icloud.com
 * @date 2015/4/15
 */
class SearchController extends BaseController
{

    /**
     * 查询用户
     * 查询华友相关信息，如：好友名片等
     * @param string $token 认证票据
     * @param string $key 搜索关键字
     * @return string
     */
    public function user()
    {

    }

    /**
     * 查询商品
     * 提供爱品汇栏目商品相关查询
     * @param string $token 认证票据
     * @param string $key 搜索关键字
     * @return string
     */
    public function goods()
    {

    }
}
