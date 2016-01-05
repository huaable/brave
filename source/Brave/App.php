<?php

namespace Brave;

use Pimple\Container;

header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files/functions.php';

/**
 * Class App
 * @package Brave
 */
class App extends Container
{
    /**
     * @var Request
     */
    public static $request;

    /**
     * @var array
     */
    public static $routes = [];

    /**
     * @var App $app
     */
    public static $app;

    /**
     * @var
     */
    public $baseUrl;

    /**
     * @var
     */
    public $siteUrl;

    /**
     * @return string
     */
    private static function getBaseUrl()
    {
        $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        return rtrim($baseUrl, '/') . '/';
    }

    /**
     * @return string
     */
    private static function getSiteUrl()
    {
        $absUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $absUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $absUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        return $absUrl;//substr($absUrl, 0, strrpos($absUrl, '/') + 1);
    }


    /**
     * @param array $config
     * @return App
     */
    public function __construct(Array $config = [])
    {
        $config = array_replace_recursive(include 'files/config.php', $config);
        ksort($config);
        parent::__construct($config);
        static::$app = $this;
        self::$app->baseUrl = self::getBaseUrl();
        self::$app->siteUrl = self::getSiteUrl();
        return self::$app;
    }

    /**
     * @param Request $request
     */
    public function run(Request $request = null)
    {

        session_start();
        date_default_timezone_set(self::$app['timezone']);

        if (self::$app['debug'] == false) {
            //开启错误报告
            ini_set('display_errors', 'Off');
        } else {
            ini_set('display_errors', 'On');

        }
        error_reporting(E_ALL);

        //错误处理
        $errorHandler = new Error();
        set_error_handler(array($errorHandler, 'handleError'));
        set_exception_handler(array($errorHandler, 'handleException'));

        if ($request == null) {
            $request = Request::init();
        }

        self::$request = $request;

        $matchCallback = null;
        //找路由
        foreach (self::$routes as $route) {
            if ($matchCallback = Router::matchRoute($request, $route)) {
                break;
            }
        }
        //has router
        if ($matchCallback != null && is_callable($matchCallback)) {
            call_user_func($matchCallback);
        } else {

            $module = $request->getModule() ? str_replace(['/', '\\'], '\\', $request->getModule() . DIRECTORY_SEPARATOR) : '';
            $controller = basename(self::$app['path']) . '\\' . $module . 'Controllers\\' . $request->getController() . 'Controller';
            $controller = str_replace(['/', '\\'], '\\', $controller);
            $action = $request->getAction();

            if (class_exists($controller)) {
                $controller = new $controller();
            }

            if ($request->isGet() && method_exists($controller, 'get' . $action)) {
                $method = 'get' . $action;
                $controller->$method($request);
            } else if ($request->isPost() && method_exists($controller, 'post' . $action)) {
                $method = 'post' . $action;
                $controller->$method($request);
            } else if (method_exists($controller, 'any' . $action)) {
                $method = 'any' . $action;
                $controller->$method($request);
            } else if (method_exists($controller, 'error404')) {
                $method = 'error404';
                $controller->$method($request);
            } else {
                header('HTTP/1.1 404 Not Found');
            }
        }

    }

    /**
     * \Brave\App::themeUrl('styles/main.css');
     * @param      $file
     * @param null $version
     * @return string
     */
    public static function themeUrl($file, $version = null)
    {
        $wwwDir = 'www';
        $src = self::$app['path'] . '/' . $wwwDir . '/' . $file;
        $dstDir = 'assets/' . md5(self::$request->getModule() . dirname($file));
        $dstFile = $dstDir . '/' . basename($file);
        if (file_exists($src)) {
            if (!is_dir($dstDir)) {
                File::createDirectory($dstDir, 0777);
            }
            if (@filemtime($dstFile) < @filemtime($src)) {
                copy($src, $dstFile);
                @chmod($dstFile, 0777);
            }
        }

        return self::$app->siteUrl . '/' . $dstFile;
    }

    /**
     * @param  string $className
     * @param  string $method
     * @return callable
     */
//    public static function controller($className, $method)
//    {
//        return function () use ($className, $method) {
//            $model = new $className();
//            $model->$method(App::$request);
//        };
//    }
}