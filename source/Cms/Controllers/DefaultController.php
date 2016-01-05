<?php


namespace Cms\Controllers;

use Brave\App;
use Brave\View;
use Brave\FileCache;
use Brave\Url;
use Brave\Request;
use Brave\Curl;
use Brave\DB;
use Brave\Log;

//use Brave\String;
//use Brave\Captcha;
//use Brave\Validator;
//use Cms\Extensions\ParseSql;

class DefaultController
{
    public function getIndex(Request $request)
    {
        //View::render('Modules/Home/views/default/index.php');
        View::render('@views/default/index.php');

        /*
                $data = [
                    'data' => '123456'
                ];
                View::ajaxReturn($data, true);
                View::ajaxReturn($data, false);
        */
    }

    public function getTest0(Request $request)
    {
        echo Url::to('dui/index', [
            'id' => 1,
        ]);
        echo '<hr>';
        echo Url::siteTo('dui/index', [
            'id' => 1,
        ]);
    }

    public function getTest1(Request $request)
    {
        dump(App::$app);
    }

    public function getTest2(Request $request)
    {
        dump(App::$routes);
    }

    //http://cms.x/default/test2?key[]=1&key[]=2
    public function getTest3(Request $request)
    {
        dump($request);
        echo '<hr>';
        echo '$request->get(\'key\'):';
        dump($request->get('key', 'default'));
        echo '<hr>';

        echo '$request->query(\'key\'):';
        dump($request->query('key', 'default'));
        echo '<hr>';

        echo '$request->post(\'key\'):';
        dump($request->post('key', 'default'));
        echo '<hr>';

        echo '$request->isGet():';
        dump($request->isGet());
        echo '<hr>';

        echo '$request->isPost():';
        dump($request->isPost());
        echo '<hr>';

        echo '$request->isAjax():';
        dump($request->isAjax());
        echo '<hr>';
    }

    public function getTest4(Request $request)
    {

        /*
         CREATE DATABASE test;
         use test;
          CREATE TABLE `aa` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `value` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
       */

        $tableName = 'aa';
        $insertId = DB::connect()->insert($tableName, ['value' => 11]);

        dump(DB::connect()->findByPk($tableName, $insertId));
        $rs = DB::connect()->query('SELECT * FROM aa WHERE id =' . $insertId)->fetchAll();
        dump($rs);

        $arrayData = DB::connect()->select('SELECT * FROM ' . $tableName . ' WHERE id=:id', [':id' => $insertId]);
        dump($arrayData);

        $effectRows = DB::connect()->update($tableName, ['value' => 'update'], ['id' => $insertId]);
        dump($effectRows);

        echo 'DB::connect()->delete()<br>';
        dump($effectRows);
    }

    public function getTest5(Request $request)
    {
        App::$app['mail']->send('coolr@foxmail.com', 'title', 'content');
    }

    public function getTest6(Request $request)
    {
        $html = Curl::get('http://www.baidu.com');
        echo $html;
    }

    public function getTest7(Request $request)
    {
        Log::info('内容');
        Log::error('内容');
        Log::warning('内容');
    }

    public function getTest8(Request $request)
    {
        FileCache::set('xx', App::$app);
        dump(FileCache::get('xx'));
        echo '<hr>';
    }

}