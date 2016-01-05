<?php
namespace Cms;

use Brave\App;
use Cms\Extensions\Mail;

class Init
{
    public static function run($entry)
    {
        //1. todo config
        $config = [];
        $config['path'] = __DIR__;
        $config['entry.file'] = $entry;
        //开启本地调试
        $config['debug'] = true;

        $config['db'] = [
            'type'     => 'mysql',
            'host'     => 'localhost',
            'dbname'   => 'test',
            'username' => 'root',
            'password' => 'root',
            'charset'  => 'utf8',
        ];
        $config['module.alias'] = [
            //'Modules/Home'  => '',
            'Modules/Admin' => 'admin'
        ];
        $config['mail'] = function () {
            return new Mail([
                'host'       => 'smtp.qq.com',
                'username'   => 'test@qq.com',
                'password'   => '123456',
                'name'       => 'Brave',
                'port'       => '465',
                'encryption' => 'ssl', //ssl、tls
            ]);
        };

        //2.todo include routes
        include 'routes.php';

        //3.todo run app
        $app = new App($config);
        $app->run();
    }
}