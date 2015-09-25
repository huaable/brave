<?php
namespace Cms;

use Brave\App;

class Init
{
    public static function run($entryFile)
    {
        //1. todo config
        $config = [];
        $config['path'] = __DIR__;
        $config['entry.file'] = $entryFile;
        $config['db'] = [
            'type'     => 'mysql',
            'host'     => 'localhost',
            'dbname'   => 'test',
            'username' => 'root',
            'password' => 'root',
            'charset'  => 'utf8',
        ];
        $config['module.alias'] = [
            'Modules/Home'  => '',
            'Modules/Admin' => 'admin'
        ];
        $config['module.theme'] = [
            'Modules/Home' => $config['path'] . '/Modules/Home/theme',
        ];

        //2.todo routes
        include 'routes.php';

        //3.todo run app
        App::create($config)->run();

    }
}