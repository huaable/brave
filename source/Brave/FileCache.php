<?php
namespace Brave;

use Brave\App;

class FileCache
{
    protected static function getFile($name)
    {
        return App::$config['cache.path'] . DIRECTORY_SEPARATOR . md5($name);
    }

    public static function  set($name, $value, $expire = 0, $dependency = 0)
    {
        $cacheFile = self::getFile($name);
        if (!file_exists(dirname($cacheFile))) {
            if (!@mkdir(dirname($cacheFile), 0777, true)) {
                return null;
            }
        }

        if ($fp = @fopen($cacheFile, 'wb')) {
            fwrite($fp, serialize($value));
            fclose($fp);
            return true;
        } else {
            throw  new \ErrorException('Can not write to cache files, please check cache directory');
        }
    }

    public static function  get($name)
    {
        $cacheFile = self::getFile($name);
        if (file_exists($cacheFile)) {
            $value = unserialize(@file_get_contents($cacheFile));
        } else
            $value = null;
        return $value;
    }

}