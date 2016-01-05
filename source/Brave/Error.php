<?php

namespace Brave;

class Error
{
    protected $_error = array();

    function handleError($num, $message, $file, $line)
    {

        $this->render();
    }

    public function handleException(\Exception $ex)
    {

    }

    protected function render()
    {

    }


}