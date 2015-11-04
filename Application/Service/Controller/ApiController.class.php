<?php
/**
 * ----------------------
 * ActivityController.class.php
 *
 * 主题活动相关接口
 *
 * Date: 2015/4/15
 * Time: 15:48
 * ----------------------
 */
namespace Service\Controller;

use Think\Controller;

/**
 * 一、API模块接口
 * 不提供单独的设备注册接口，设备均从属于私有云用户，由私有云推送设备列表更新
 * @package Service\Controller
 * @author jian0307@icloud.com
 * @date 2015/11/2
 */
class ApiController extends BaseController
{
    /**
     * 1.1、设备登录/离线
     * 私有云设备的上下线状态，由私有云通过此接口通知到JDY云平台。
     * API模块获取后，通知到DEV模块，启动/关闭设备的虚拟化。
     *
     * @httpmethod POST
     * @request string $body 请求数据<br/>
     * {
     *       "platform":"haier",
     *       "company_did":"wmaottaoeeaoeao",
     *       "onoffline":"online"
     * }
     * @response string 响应数据
     * {
     *      "errno":0,
     *      "errmsg":"获取数据成功",
     *      "data": {
     *          "goods_id":100,
     *          "goods_name":"测试",
     *          "goods_pic":"http://www.xxx.com/xx.png",
     *      }
     * }
     * @author       作者 &lt;author@email&gt;
     * @deprecated   废弃
     * @see          指向其他元素的名字，如函数名，类方法等
     * @version      版本0.1
     * @example
     * $doc->parse($d);
     */
    public function onoffline()
    {

    }

    /**
     * 1.2、私有云推送设备状态
     * 私有云设备的状态，会先上报到私有云平台。私有云平台将注册到JDY的设备状态数据，通过此接口推送到JDY云平台API模块，由API模块转发到后续模块。
     * 由于私有云的标准不统一，字段和格式均作为参考。主要包含两部分，设备标识和设备状态数据。
     *
     * @httpmethod POST
     * @request string $body 请求数据<br/>
     * <code class="lang-json">{
     *   "platform":"haier",
     *   "company_did":"wmaottaoeeaoeao",
     *   "product_type":"aircondition",
     *   "dev_status":["binary"]
     * }</code>
     * @response string 测试
     */
    public function send()
    {

    }

    /**
     * 1.3、私有云数据转换为JDY国标
     * 用户在APP端的控制，通过JDY云平台的一系列转发，最后交给API模块，推送到对应的私有云。
     * 同私有云推送设备状态接口一样，字段和格式仅作为参考。
     * 此接口非开放，提供给DEV模块调用。API模块到私有云的部分，按照私有云的格式推送。
     * 关于私有云token的描述：包含在数据中，由协议转换模块解出。详见"用户通过JDY云平台登录到私有云"中解释。
     *
     * @httpmethod POST
     * @request string $body 请求数据<br/>
     * 用户在APP端的控制，通过JDY云平台的一系列转发，最后交给API模块，推送到对应的私有云。<br/>
     * 同私有云推送设备状态接口一样，字段和格式仅作为参考。<br/>
     * 此接口非开放，提供给DEV模块调用。API模块到私有云的部分，按照私有云的格式推送。<br/>
     * 关于私有云token的描述：包含在数据中，由协议转换模块解出。详见"用户通过JDY云平台登录到私有云"中解释。<br/>
     * <code class="lang-json">{
     *      "platform":"haier",
     *      "token":"ntmoeunsoeut",
     *      "company_did":"wmaottaoeeaoeao",
     *      "product_type":"aircondition",
     *      "dev_status":["binary"]
     *  }</code>
     * @response string 测试
     */
    public function devpushcontrol()
    {

    }

    /**
     * 1.4、推送控制指令到私有云
     * 私有云设备所使用的指令格式，和JDY国标不一致，需要进行转换。此过程在私有云推送设备上报的数据到API模块后， 进行转换后，发送到虚拟设备，然后由虚拟设备推送到APP端。
     * 此接口目前仅作为参考。传入和返回的数据，必须能够满足转换需要。
     * 另外，在"APP=>设备"的控制链路上，由于token的原因，需要APP将token放入控制指令中，由此模块一并解出。
     *
     * @httpmethod POST
     * @request string $body 请求数据
     * {
     *  "platform":"haier",
     *  "product_type":"aircondition",
     *  "company_did":"wmaottaoeeaoeao",
     *  "data":["binary"]
     * }
     * @response string 响应数据
     * {
     *   "platform":"haier",
     *   "token":"wmaottaoeeaoeao",
     *   "product_type":"aircondition",
     *   "company_did":"wmaottaoeeaoeao",
     *   "data":["binary"]
     * }
     */
    public function transfromjdy()
    {

    }

    /**
     * 1.5、JDY国标转换为私有云数据
     * JDY国标数据格式，和私有云数据不同，需要进行转换。此过程在虚拟设备转发APP控制数据到API模块后， 进行转换后，由API模块发送转换后的结果，到私有云。
     * 此接口目前仅作为参考。传入和返回的数据，必须能够满足转换需要。
     * 在"设备=>APP"的控制链路中，私有云不会推送token且token对APP没有影响，故转换后的数据中，token段可以忽略（填充0）。
     *
     * @httpmethod POST
     * @request string $body 请求体
     * {
     *  "platform":"haier",
     *  "product_type":"aircondition",
     *  "company_did":"wmaottaoeeaoeao",
     *  "data":['binary'],
     * }
     * @response string 响应数据
     * {
     *  "platform":"haier",
     *  "product_type":"aircondition",
     *  "company_did":"wmaottaoeeaoeao",
     *  "data":["binary"]
     * }
     */
    public function transtojdy()
    {

    }
}
