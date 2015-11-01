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
namespace Api\Controller;

use Think\Controller;

/**
 * 主题活动
 * 主题活动。。。。。。。。。。
 * @package Api\Controller
 * @author jian0307@icloud.com
 * @date 2015/4/15
 */
class DemoController extends Controller
{
    /**
     * 获取数据接口
     *
     * ------------------------
     * AAAasfdadsfalsdfjlkj
     * asdfsadflsakj
     * asdfsadfBBB
     * ------------------------
     * @httpmethod get
     * @param   string    $token 验证票据
     * @param   int $typeid  参数签名，详见下说明。<br/>
     * <b>1、签名规则：</b>将以下“签名参数”按照key进行字典升序后，以“key1=value1&key2=value2…”的形式拼接起来，依次进行HMAC-SHA1和BASE64两步加密后得出的密文；<br/>
     * <b>2、签名参数：</b>该接口所有请求参数当中，除了“sign”本身和已标明不参与签名的特殊参数（如图片）外，均为参与签名的参数，即签名参数；<br/>
     * <b>3、HMAC-SHA1加密密钥：</b>即应用密钥（App Secret），开发者创建应用时，天翼开放平台会为应用自动分配，开发者进入"管理中心"，在"应用详情"中可查看App Secret；<br/>
     * <b>4、数据编码：</b>HMAC-SHA1和BASE64加密过程中，均采用utf-8编码方式；<br/>
     * <b>5、举例说明：</b>假设应用密钥为“appSecret”，签名参数列表“foo=1,bar=2,baz=3”，拼接排序后为“bar=2&baz=3&foo=1”，那么，sign的值为BASE64(HMAC-SHA1("bar=2&baz=3&foo=1","appSecret"))。<br/>
     * @return string 商品信息
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
    public function getDataDemo()
    {

    }

    /**
     * 获取数据接口2
     * @param string $token 参数1
     * @param string $typeid 擦书
     * @return string 测试
     */
    public function getDataDemo2()
    {

    }
}
