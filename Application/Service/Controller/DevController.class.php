<?php
/**
 * ----------------------
 * DevController.class.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/11/2
 * Time: 20:30
 * ----------------------
 */
namespace Service\Controller;

/**
 * 三、DEV模块接口
 * @package Service\Controller
 * @author jian0307@icloud.com
 * @date 2015/11/2
 */
class DevController extends BaseController
{

    /**
     * 3.1、注册新的JDY设备
     * 此接口用来在获取到用户绑定的新设备后，进行虚拟化的准备工作。
     * 私有云的设备，需要在服务器端映射为虚拟设备，用来衔接私有云和JDY云平台，使用虚拟设备来代替私有云设备进行操作。
     * 非开放接口，在以下情况下被API模块调用：
     * JDY用户绑定了私有云账号，JDY云平台从私有云获取绑定用户的设备列表，对所有设备进行注册。
     * 私有云平台用户增加绑定设备后，通知JDY云平台，JDY云平台仅对新设备进行注册。
     * 如果私有云的设备已经注册过，此接口行为未定义。
     * 调用后，DEV模块按照JDY平台协议，向JDY云平台注册此设备，保存返回的设备信息到设备信息表，并向调用者返回结果。
     * 问题：私有云用户删除设备后，是否需要注销？
     * @httpmethod POST
     * @request string $body 请求数据<br/>
     * {
     * "platform":"haier",
     * "product_type":"ntmoeunsoeut",
     * "company_did":"ntmoeunsoeut",
     * "mac":"abcdefabedef"
     * }
     */
    public function reg()
    {

    }

    /**
     * 3.2、设备登录/离线
     * 非开放接口，由API模块调用。
     *   私有云设备的上下线状态，通知到API模块后，由API模块通过此接口通知到DEV模块，DEV模块启动/停止虚拟化设备的运行。关于虚拟节点的启动/停止，加入设备活跃的检测，避免频繁的开启/关闭虚拟设备节点。
     *   DEV模块根据platform和companydid信息，检索对应的虚拟设备节点信息。
     * @httpmethod POST
     * @request string $body 请求数据<br/>
     * {
     *  "platform":"haier",
     *  "company_did":"wmaottaoeeaoeao",
     *  "onoffline":"online"
     *  }
     */
    public function onoffline()
    {

    }

    /**
     * 3.3、推送设备状态到虚拟设备
     * 非开放接口，由API模块调用。
     * 私有云设备的状态，由私有云推送到API模块后，API模块调用此接口，推送到DEV模块，再由虚拟设备推送到APP端。
     * 此接口仅作为参考，所选用字段包括两方面，设备标识，设备数据。
     * @httpmethod POST
     * @request string $body 请求数据<br/>
     * {
     *  "platform":"haier",
     *  "company_did":"wmaottaoeeaoeao",
     *  "product_type":"aircondition",
     *  "dev_status":["自定义P0"]
     * }
     */
    public function pushstatus()
    {

    }

    /**
     * 3.4、 推送控制指令到设备
     * 非开放接口，由虚拟设备调用。
     * 用户在APP端的控制指令，实际上会先到达虚拟设备，虚拟设备再将数据通过此接口，转发到API模块。再由API模块推送到私有云平台。
     * 此接口仅作为参考，包括两部分内容，设备标识，设备数据。
     * @httpmethod POST
     * @request string $body 请求数据
     * {
     * "platform":"haier",
     * "product_type":"aircondition",
     * "company_did":"wmaottaoeeaoeao",
     * "data":["binary"]
     * }
     */
    public function pushcontrol()
    {

    }

}
