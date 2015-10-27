<?php
namespace Lib;

interface IRegistry
{
    public function get($key);
    public function set($key, $vlaue);
}
