<?php

namespace Brave\helpers;
class Html
{
    /**
     * 把特殊的字符编码为HTML实体
     * @param $text
     * @param string $charset
     * @return string
     */
    public static function encode($text, $charset = 'UTF-8')
    {
        return htmlspecialchars($text, ENT_QUOTES, $charset);
    }

}