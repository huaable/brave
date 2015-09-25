<?php

namespace Brave\Helpers;

class File
{

    static function copyDirectory($src, $dst)
    {
        if (!is_dir($dst))
            self::createDirectory($dst, true);
        self::copyDirectory($src, $dst);
    }

    public static function removeDirectory($directory)
    {
        $items = glob($directory . DIRECTORY_SEPARATOR . '{,.}*', GLOB_MARK | GLOB_BRACE);
        foreach ($items as $item) {
            if (basename($item) == '.' || basename($item) == '..')
                continue;
            if (substr($item, -1) == DIRECTORY_SEPARATOR) {
                if (is_link(rtrim($item, DIRECTORY_SEPARATOR)))
                    unlink(rtrim($item, DIRECTORY_SEPARATOR));
                else
                    self::removeDirectory($item);
            } else
                unlink($item);
        }
        if (is_dir($directory = rtrim($directory, '\\/'))) {
            if (is_link($directory))
                unlink($directory);
            else
                rmdir($directory);
        }
    }

    public static function createDirectory($dst, $mode = 0777)
    {
        $prevDir = dirname($dst);
        if (!is_dir($dst) && !is_dir($prevDir))
            self::createDirectory(dirname($dst), $mode);
        $res = @mkdir($dst, $mode);
        @chmod($dst, $mode);
        return $res;
    }

}