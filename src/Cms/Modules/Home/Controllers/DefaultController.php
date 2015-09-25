<?php


namespace Cms\Modules\Home\Controllers;

use Brave\App;
use Brave\Helpers\View;
use Brave\helpers\FileCache;
use Brave\Helpers\Url;
use Brave\Request;
use Brave\helpers\Curl;
use Brave\Helpers\DB;
use Brave\Helpers\Log;
use Brave\Helpers\String;
use Brave\Helpers\Captcha;
use Brave\helpers\Validator;
use Cms\Extensions\ParseSql;

class DefaultController
{
    public function getIndex()
    {
//        View::render('Modules/Home/views/default/index.php');
        View::render('@views/default/index.php');

//        $request = Request::$request;
//        FileCache::set('xx', App::$config);
//        dump(FileCache::get('xx'));
//        Response::view('views/default/index.php');
//        dump($request);
//        Captcha::create();
//        $data = [
//            'data' => '123456'
//        ];
//        Response::ajaxError($data);
//        Response::file('views/default/index.php', $data);
//        $app = App::$app;
//        echo 'App::$config:<br>';
//        dump(App::$config);
//        echo 'App::$routes:<br>';
//        dump(App::$routes);
//        echo 'App::$request:<br>';
//        dump(App::$request);
//
//        echo '$request->getParam["key"]:<br>';
//        dump($request->getParam('key', 'default'));
//        echo '$request->query["key"]:<br>';
//        dump($request->query('key', 'default'));
//        echo '$request->post["key"]:<br>';
//        dump($request->post('key', 'default'));
//        echo '$request->isGet:<br>';
//        dump($request->isGet());
//        echo '$request->isPost:<br>';
//        dump($request->isPost());
//        echo '$request->isAjax:<br>';
//        dump($request->isAjax());

//        Log::info('内容');
//        Log::error('内容');
//        Log::warning('内容');


        //echo 'mail:Mail::ready()->send($to,$title,$content)<br>';
        //dump(Mail::ready()->send('coolr@foxmail.com', 'title', 'content'));

//        $tableName = 'aa';
//        echo 'DB::connect()->insert()<br>';
//        $insertId = DB::connect()->insert($tableName, ['value' => 11]);
//        dump(DB::connect()->findByPk($tableName,$insertId));
//        $rs = DB::connect()->query('select * from aa where id =' . $insertId)->fetchAll();
//        dump($rs);
//
//        echo 'DB::connect()->select()<br>';
//        $arrayData = DB::connect()->select('select * from ' . $tableName . ' where id=:id', [':id' => $insertId]);
//        dump($arrayData);
//
//        echo 'DB::connect()->update()<br>';
//        $effectRows = DB::connect()->update($tableName, ['value' => 'update'], ['id' => $insertId]);
//        dump($effectRows);
//
//        $effectRows = DB::connect()->delete($tableName, ['id' => $insertId]);
//        echo 'DB::connect()->delete()<br>';
//        dump($effectRows);
//        //*
//        $validator = new Validator();
//        $validator
//            ->data([
//                'id' => '1',
//                'value1' => '',
//                'value2' => '1',
//                'phone' => '你好',
//                'tel' => '12222222222',
//                'url' => 'http://a.b.com',
//                'email' => 'demo@163.com',
//                'content' => 'o',
//            ])
//            ->rules([
//                ['value1 value2 phone tel url email content', 'required'],
//                ['value1', 'int', 'min' => 1, 'max' => 10],
//                ['value2', 'number', 'min' => 1, 'max' => 10],
//                ['tel  phone', 'tel'],
//                ['content', 'pattern', '/^\d+$/'],
//                ['url', 'url'],
//                ['email', 'email'],
//                ['email', $this->validateEmail()],
//                ['content', 'length', 'min' => 10, 'max' => 20],
//                ['content', function (Validator $validator, $name, $value) {
//                    $validator->addError($name, '自定义验证');
//                }]
//
//            ]);
//        dump($validator->validates()->getErrors());

        //*/

//        echo Url::to('dui/index',[
//            'id'=>1,
//        ]);
    }
//
//    public function postIndex(){
//        echo 'postIndex';
//    }
//
//    public function validateEmail()
//    {
//        return function (Validator $validator, $name, $value) {
//
//            $validator->addError($name, '自定义验证');
//        };
//    }

    public function getCtrl()
    {
        View::render('Home/views/default/ctrl.php');
    }

}