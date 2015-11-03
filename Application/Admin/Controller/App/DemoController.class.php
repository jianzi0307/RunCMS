<?php
/**
 * ----------------------
 * DemoController.class.php
 * 
 * User: jian0307@icloud.com
 * Date: 2015/11/3
 * Time: 20:10
 * ----------------------
 */
namespace Admin\Controller\App;

use Admin\Controller\System\AdminController;

class DemoController extends AdminController
{
    public function index()
    {
        $this->display();
    }
}
