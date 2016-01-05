<?php
namespace Brave;

use Brave\App;

class View
{
    public static function ajaxReturn($data, $status, $type = 'JSON')
    {
        $result = array();
        $result['status'] = $status;
        $result['data'] = $data;

        if (strtoupper($type) == 'JSON') { // 返回JSON数据格式到客户端 包含状态信息
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } elseif (strtoupper($type) == 'XML') { // 返回xml格式数据
            echo(xmlrpc_encode($result));
        } elseif (strtoupper($type) == 'EVAL') { // 返回可执行的js脚本
            echo $data;
        }
        exit;
    }

    public static function render($_viewFile_, $_data_ = [], $_return_ = false)
    {

        if (strpos($_viewFile_, '@') === 0) {
            //在当前模块下找
            $module = App::$request->getModule() ? App::$request->getModule() . '/' : '';
            $viewsDir = App::$app['path'] . DIRECTORY_SEPARATOR . $module;
            return self::renderFile($viewsDir . substr($_viewFile_, 1), $_data_, $_return_);
        } else {
            //在项目中找
            return self::renderFile(App::$app['path'] . DIRECTORY_SEPARATOR . $_viewFile_, $_data_, $_return_);
        }
    }

    public static function renderFile($_viewFile_, $_data_ = [], $_return_ = false)
    {
        if (is_array($_data_)) {
            extract($_data_, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $_data_;
        }
        if ($_return_) {
            ob_start();
            ob_implicit_flush(false);
            require($_viewFile_);
            return ob_get_clean();
        } else {
            require($_viewFile_);
        }
    }

}