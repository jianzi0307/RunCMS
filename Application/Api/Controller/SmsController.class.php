<?php
/**
 * ----------------------
 * SmsController.class.php
 * 
 * User: jian0307@icloud.com
 * Date: 2015/4/19
 * Time: 23:16
 * ----------------------
 */

namespace Api\Controller;

/**
 * 验证码相关接口
 *
 * 提供短信验证相关接口
 *
 * @package Api\Controller
 * @author jianzi0307@icloud.com
 * @date 2015/4/15
 */
class SmsController extends BaseController
{

    /**
     * 下发验证码
     * @param string $mobile 手机号
     * @return string
     * {
     *   "errno":0,
     *   "errmsg":"发送成功"，
     *   "token":"xxxx"
     * }
     */
    public function sendSms()
    {

    }

    /**
     * 校验验证码
     * @param string $mobile 手机号
     * @param string $smscode 验证码
     * @return string
     * {
     *      "errno":0,
     *      "errmsg":"验证成功",
     *      "token" : "xxx"
     * }
     */
    public function smsVerify()
    {

    }
}
