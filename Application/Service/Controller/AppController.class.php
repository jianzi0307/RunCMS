<?php
/**
 * ----------------------
 * AppController.class.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/11/2
 * Time: 20:39
 * ----------------------
 */
namespace Service\Controller;

/**
 * 二、APP模块接口
 * @package Service\Controller
 * @author jian0307@icloud.com
 * @date 2015/11/2
 */
class AppController extends BaseController
{
    /**
     * 2.1、 注册JDY用户
     * 用户在使用JDY云平台服务之前，必须先进行注册。可以使用手机号，邮箱，自定义用户名。
    在用户注册到JDY用户后，需要提示用户绑定Gizwits/海尔/美的用户。
    返回的token，用在后续和云端的交互中，和登录接口返回的token功能一致。
     *
     * @httpmethod POST
     * @request string $body 请求数据
     *  {
     *      "username":"River",
     *      "password":"123456"
     *  }
     * @response string 响应数据
     * {
     *      "token":"ntmoeunsoeut",
     *      "expired-at":1782660536
     * }
     */
    public function reg()
    {

    }

    /**
     * 2.2、 JDY用户修改登录密码
     * 用户使用登录后，可以修改登录的密码。
     *
     * @httpmethod POST
     * @request string $body 请求体
     * {
     *      "token":"ntmoeunsoeut",
     *      "old_password":"123456",
     *      "new_password":"123456"
     * }
     */
    public function resetpassword()
    {

    }

    /**
     * 2.3、绑定JDY用户ID和Gizwits/海尔/美的用户ID
     * 用户已有海尔和美的用户的情况下，可以和JDY用户绑定，以通过JDY用户账户来使用海尔/美的的产品。
    海尔/美的的用户注册接口及设备的绑定，均使用海尔/美的的系统。
    需要修改绑定信息时，可重新调用此接口，填入新的信息即可。
    如果要解除绑定，在name和password字段留空。
     *
     * @httpmethod POST
     * @request string $body 请求体
     * {
     *      "token":"ntmoeunsoeut",
     *      "bind_user":[{
     *      "platform":"Gizwits",
     *      "bind_account_name":"Song",
     *      "bind_account_password":"123456"
     *      }]
     * }
     */
    public function binduser()
    {

    }

    /**
     * 2.4、登录JDY云平台
     * 从云平台获取后续访问使用的token。
    后续使用此token与服务器交互，等同于使用用户名和密码。
    token存在有效期，在失效后，必须重新从云端获取
     *
     * @httpmethod POST
     * @request string $body 请求体
     * {
     *      "username":"River",
     *      "password":"123456"
     * }
     * @response string 响应数据
     * {
     *      "token":"ntmoeunsoeut",
     *      "expired-at":1782660536
     * }
     */
    public function login()
    {

    }

    /**
     * 2.5、 获取JDY用户ID相关联的Gizwits/海尔/美的用户ID
     *
     * 用户登录服务器后，获取JDY用户ID相关联的Gizwits/海尔/美的用户ID。
     * 返回结果取决于用户是否绑定过其他平台。
     *
     * @httpmethod POST
     * @request string $body 请求数据
     * {
     *      "token":"ntmoeunsoeut",
     *      "username":"River"
     * }
     * @response string 响应数据
     * {
     *      "bind_user":[{
     *      "platform":"Gizwits",
     *      "bind_account_name":"Song",
     *      "bind_account_password":"123456"}]
     * }
     */
    public function getbindinfo()
    {

    }

    /**
     * 2.6、查询设备列表
     * app获取JDY用户所关联的设备，包括所绑定的海尔/美的云用户的设备。<br/>
     * Gizwits的设备，通过Gizwits的系统获得。<br/>
     * 海尔/美的云的设备，由于虚拟设备的存在，所有信息均为虚拟设备的，由"platform"字段标明从属的私有云平台。
     *
     * @httpmethod POST
     * @param string $productkey 设备类型标识
     * @param string $did 设备标识，用来标识单个设备
     * @param string $mac 设备的mac地址，如果是GPRS模块，则为模块串号。
     * @param string $isonline 设备是否在线。
     * @param string $passcode 设备上电会自己生成访问密码，需使用正确的访问密码，才能对设备进行操作。
     * @param string $host 设备连接的云服务器地址。
     * @param string $port 设备连接的云服务器端口。
     * @param string $remark 用来给设备起一个比较容易记住的名字。
     * @param string $isdisable 设备不再使用时，会通知云端。此类设备不再可以控制，仅保留原有的数据。
     * @param string $type 设备的属性，目前有normal和centercontrol两种。用来区别设备的控制方式。对于JDY的所有设备来说，都为normal。
     * @request string 请求数据
     * {
    "token":"ntmoeunsoeut"
    }
     * @response string 响应数据
     * {
    "devices":[{
    "platform":"haier",
    "product_key":"soeutaosoen",
    "did":"wmaottaoeeaoeao",
    "mac":"123456789012",
    "is_online":false,
    "passcode":"123456",
    "host":"m2m.gizwits.com",
    "port":3128,
    "remark":"",
    "is_disable":false,
    "type":"normal",
    },{
    "platform":"meidi",
    "product_key":"aoeatsoeut",
    "did":"wmaottaoeeaoeao",
    "mac":"123456789012",
    "is_online":false,
    "passcode":"123456",
    "host":"m2m.gizwits.com",
    "port":3128,
    "remark":"",
    "is_disable":false,
    "type":"normal",
    }]
    }
     */
    public function devlist()
    {

    }

    /**
     * 2.7、用户通过JDY云平台登录到私有云
     * JDY云平台和私有云交互时，需要提供私有云的token，APP通过此接口来获取。
     * 在接口中通过platform字段区别需要连接的云平台。用户名和密码字段，由API模块通过按照platform查询用户所绑定的信息获得。
     * APP登录JDY云平台后，对每个私有云均需要调用此接口。并且由APP维护超时时间。 后续操作中云平台检测到token失效后，通过返回错误通知APP。APP需要再次获取。
     * token不在JDY云平台保存，通过在APP端，将token放入控制数据字段内存储，由协议转换模块进行解析。 私有云上报的设备状态不需要token字段，在协议转换层，此字段可以填充无效内容。
     *
     * @httpmethod POST
     * @request string $body　请求数据
     * {
    "platform":"haier",
    "token":"wmaottaoeeaoeao"
    }
     * @response string 响应数据
     * {
    "platform":"haier",
    "token":"wmaottaoeeaoeao",
    "expired-at":1782660536
    }
     */
    public function passlogin()
    {

    }
}
