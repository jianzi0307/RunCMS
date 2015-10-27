<?php
/**
 * ----------------------
 * Captcha.class.php
 * 
 * User: jian0307@icloud.com
 * Date: 2015/7/22
 * Time: 13:08
 * ----------------------
 */

namespace Lib;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

/**
 * Google风格的图片验证码单例类
 * Class Captcha
 * @package Lib
 */
class Captcha
{
    //use SingletonTrait;
    private $captchaBuilder;
    private $phraseBuilder;

    public function __construct()
    {
        $this->phraseBuilder = new PhraseBuilder();
        $phrase = $this->phraseBuilder->build(4, '123456789');
        $this->captchaBuilder = new CaptchaBuilder($phrase, $this->phraseBuilder);//CaptchaBuilder::create();
    }

    /**
     * 保存图片
     * 用法：
     *      $captcha->save('out.jpg');
     * @param $filePath 图片路径
     * @param int $quality 质量
     */
    public function save($filePath, $quality = 80)
    {
        $this->captchaBuilder->save($filePath, $quality);
    }

    /**
     * 获取HTML内嵌Base64图像编码
     * 用法：
     *     <img src="<?php echo $captcha->inline();?>" />
     * @param int $width
     * @param int $height
     * @param int $quality 质量
     * @return string
     */
    public function inline($width = 128, $height = 34, $quality = 80)
    {
        return $this->captchaBuilder->build($width, $height)->inline($quality);
    }

    /**
     * 输出图像
     * 用法：
     * <?php
     *      header('Content-type: image/jpeg');
     *      $captcha->output();
     * @param int $width 宽度
     * @param int $height 高度
     * @param int $quality 质量
     */
    public function output($width = 128, $height = 34, $quality = 80)
    {
        $this->captchaBuilder->build($width, $height)->output($quality);
    }

    /**
     * 获取验证码
     *
     * 用法：
     *  <?php
     *      $_SESSION['phrase'] = $captcha->getPhrase();
     * @return null|string
     */
    public function getPhrase()
    {
        return $this->captchaBuilder->getPhrase();
    }

    /**
     * 设置验证码
     * 用法：
     *  <?php
     *      $captcha->setPhrase($_SESSION['phrase']);
     * @param $phrase
     */
    public function setPhrase($phrase)
    {
        $this->captchaBuilder->setPhrase($phrase);
    }

    /**
     * 检验验证码是否正确
     * 用法:
     *   <?php
     *      if ($captcha->testPhrase('xxxxx')) {
     *      } else {
     *      }
     * @param string $phrase 验证码
     * @return bool
     */
    public function testPhrase($phrase)
    {
        return $this->captchaBuilder->testPhrase($phrase);
    }
}
