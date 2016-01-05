<?php
namespace Brave;

use Brave\App;

class Url
{
    public static function to($pathinfo, $param = [])
    {
        return rtrim(App::$app->baseUrl . $pathinfo . '?' . http_build_query($param), '?');
    }

    public static function siteTo($pathinfo, $param = [])
    {
        return rtrim(App::$app->siteUrl . $pathinfo . '?' . http_build_query($param), '?');
    }
}