<?php

/**
 * PHP函数扩展
 */

//打印变量
if (!function_exists('dump')) {
    function dump($arg, $return = false, $layer = 1)
    {
        $html = '';

        //字符串
        if (is_string($arg)) {
            $len = strlen($arg);
            $html .= "<small>string</small> <font color='#cc0000'>'{$arg}'</font>(length={$len})";
        } else if (is_float($arg)) {
            $html .= "<small>float</small> <font color='#f57900'>{$arg}</font>";
        } //布尔
        else if (is_bool($arg)) {
            $html .= "<small>boolean</small> <font color='#75507b'>" . ($arg ? 'true' : 'false') . "</font>";
        } //null
        else if (is_null($arg)) {
            $html .= "<font color='#3465a4'>null</font>";
        } //资源
        else if (is_resource($arg)) {
            $type = get_resource_type($arg);
            $html .= "<small>resource</small>(<i>{$type}</i>)";
        } //整型
        else if (is_int($arg)) {
            $html .= "<small>int</small> <font color='#4e9a06'>" . $arg . "</font>";
        } //数组
        else if (is_array($arg)) {
            $count = count($arg);
            $html .= "<b>array</b> (size={$count})";
            if (count($arg) == 0) {
                $html .= "\n" . str_pad(' ', $layer * 4) . "empty";
            }

            foreach ($arg as $key => $value) {
                $html .= "\n" . str_pad(' ', $layer * 4) . "'{$key}' => ";
                $html .= dump($value, true, $layer + 1);
            }
        } //对象
        else if (is_object($arg)) {
            ob_start();
            var_dump($arg);
            $html .= ob_get_clean();
        } //未知
        else {
            ob_start();
            var_dump($arg);
            $html .= ob_get_clean();
        }

        if ($return === true) {
            return $html;
        } else {
            echo '<pre>';
            echo $html;
            echo '</pre>';
        }
    }
}

function bundle($resources)
{

}