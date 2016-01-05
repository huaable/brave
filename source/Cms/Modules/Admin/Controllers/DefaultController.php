<?php


namespace Cms\Modules\Admin\Controllers;

use Brave\View;

class DefaultController
{
    public function getIndex()
    {
        View::render('@views/default/index.php');
    }
}