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

interface IRegistry
{
    public function get($key);
    public function set($key, $vlaue);
}
