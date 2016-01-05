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
namespace Lib;

class BankCardUtil
{
    /**
     * 通过luhn算法验证银行卡 
     * @param $card
     * @return bool
     */
    public static function luhn($s)
    {
        $n = 0;
        for ($i = strlen($s); $i >= 1; $i--) {
            $index=$i-1;
            //偶数位
            if ($i % 2==0) {
                $n += $s{$index};
            } else {//奇数位
                $t = $s{$index} * 2;
                if ($t > 9) {
                    $t = (int)($t/10)+ $t%10;
                }
                $n += $t;
            }
        }
        return ($n % 10) == 0;
    }
}

