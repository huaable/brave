<?php

/**
 * 默认全局配置数组 Array
 * 读取配置 \Brave\App::$config
 */
return [

    //路径 项目目录 __DIR__
    'path'         => null,

    //路径 入口文件
    'entry.file'   => null,

    //开启本地调试
    'debug'        => false,

    //时区
    'timezone'     => 'Asia/Chongqing',

    //日志目录
    'log.path'     => $config['path'] . '/runtime/logs',

    //缓存目录
    'cache.path'   => $config['path'] . '/runtime/cache',

    //模块别名
    //(什么是模块?决定在哪个模块的 Controllers目录 下搜索控制器)
    //(默认模块是什么?项目本身就是一个模块,模块名为 空字符 '')
    'module.alias' => [
        /*
        // 模块/控制器/动作

        // 原来这样访问 http://www.domain.com/Modules/Home/default/index
        'Modules/Home' => '',
        // 现在这样访问 http://www.domain.com/default/index

        // 原来这样访问 http://www.domain.com/Modules/Admin/default/index
        'Modules/Admin' => 'admin',
        // 现在这样访问 http://www.domain.com/admin/default/index

        // 原来这样访问 http://www.domain.com/Modules/Admin/Xxx/Xxx.../default/index
        'Modules/Admin/Xxx/Xxx...' => 'xxx',
        // 现在这样访问 http://www.domain.com/xxx/default/index

        */
    ],

    // 指定主题静态资源目录 实现静态资源版本控制
    // 依赖指定项目入口文件 $config['entry.file']
    'module.theme' => [
        /*
         // '模块'=>''
        'Modules/Home' => $config['path'] . '/www/',
        'Modules/Admin' => $config['path'] . '/Modules/Admin/sources/',
        */
    ],

    /*

    // 默认数据库配置
    // \Brave\DB::connect()->
   'db' => [
       'type' => 'mysql',
       'host' => 'localhost',
       'dbname' => 'test',
       'username' => 'root',
       'password' => 'root',
       'charset' => 'utf8',
   ],

    // 其他数据库配置
    // \Brave\DB::connect(\Brave\App::$config['db.xxx'])->

   'db.xxx' => [
       'type' => 'mysql',
       'host' => 'localhost',
       'dbname' => 'test',
       'username' => 'root',
       'password' => 'root',
       'charset' => 'utf8',
   ],

    */

    /*
    'mail'=>[
        'host' => 'smtp.qq.com',
        'username' => '******@qq.com',
        'password' => '******',
        'name' => 'Mac',
        'port' => '465',
        'encryption' => 'ssl', //ssl、tls
    ]
    */
];