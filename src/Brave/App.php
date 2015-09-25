<?php

namespace Brave;

use Brave\Error;
use Brave\Helpers\File;

header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files/functions.php';

/**
 * Class App
 * @package Brave
 */
class App
{
    /**
     * @var array
     */
    public static $config = [];

    /**
     * @var Request
     */
    public static $request;

    /**
     * @var array
     */
    public static $routes = [];

    /**
     * @var array
     */
    public static $events = [];

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
    protected static function getBaseUrl()
    {
        $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        return rtrim($baseUrl, '/') . '/';
    }

    /**
     * @return string
     */
    protected static function getSiteUrl()
    {
        $absUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $absUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $absUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        return substr($absUrl, 0, strrpos($absUrl, '/') + 1);
    }


    /**
     * @param array $config
     * @return App
     */
    public static function create(Array $config = [])
    {
        if (false == self::$app) {
            self::$app = new self();
            self::extendConfig(include 'files/config.php');
            self::extendConfig($config);
            self::$app->baseUrl = self::getBaseUrl();
            self::$app->siteUrl = self::getSiteUrl();
        }
        return self::$app;
    }

    public static function extendConfig(Array $config = [])
    {
        self::$config = array_replace_recursive(self::$config, $config);
        ksort(self::$config);
    }

    /**
     * @param Request $request
     */
    public function run(Request $request = null)
    {

        session_start();
        date_default_timezone_set(self::$config['timezone']);

        //开启错误报告
        ini_set('display_errors', 'Off');
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
            $controller = basename(self::$config['path']) . '\\' . $module . 'Controllers\\' . $request->getController() . 'Controller';
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
     * @param $path
     * @param $callback
     */
    public static function get($path, $callback)
    {
        Router::get($path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public static function post($path, $callback)
    {
        Router::post($path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public static function any($path, $callback)
    {
        Router::any($path, $callback);
    }

    /**
     * @param  string $className
     * @param  string $method
     * @return callable
     */
    public static function controller($className, $method)
    {
        return function () use ($className, $method) {
            $model = new $className();
            $model->$method(App::$request);
        };
    }

    /**
     * @param string   $eventName
     * @param callable $callback
     */
    public static function on($eventName, $callback)
    {
        if (!isset(self::$events[$eventName])) {
            self::$events[$eventName] = [];
        }

        if (is_callable($callback)) {
            self::$events[$eventName][] = $callback;
        }
    }

    /**
     * @param string $eventName
     */
    public static function off($eventName)
    {
        unset(self::$events[$eventName]);
    }

    /**
     * @param string $eventName
     * @param array  $data
     */
    public static function trigger($eventName, $data = [])
    {

        if ($eventName != 'event') {
            self::trigger('event', $data);
        }
        if (isset(self::$events[$eventName])) {
            foreach (self::$events[$eventName] as $callback) {
                if (is_callable($callback)) {
                    $callback($data);
                }
            }
        }
    }

    /**
     * \Brave\App::themeUrl('styles/main.css');
     * @param $file
     * @return string
     */
    public static function themeUrl($file)
    {

        $moduleName = self::$request->getModule();
        $themePath = isset(self::$config['module.theme'][$moduleName]) ? self::$config['module.theme'][$moduleName] : null;
        if ($themePath != null) {
            $themeSrcFile = $themePath . DIRECTORY_SEPARATOR . $file;
            if (file_exists($themeSrcFile) && !empty(self::$config['entry.file'])) {
                $versionDir = 'asset' . DIRECTORY_SEPARATOR . md5($moduleName . filectime($themeSrcFile));
                $versionPath = dirname(self::$config['entry.file']) . DIRECTORY_SEPARATOR . $versionDir;
                $versionFile = $versionPath . DIRECTORY_SEPARATOR . $file;
                if (!file_exists($versionFile)) {
                    File::createDirectory(dirname($versionFile), 0777);
                }
                if (file_exists($themeSrcFile) && !file_exists($versionFile)) {
                    copy($themeSrcFile, $versionFile);
                }
                return self::$app->siteUrl . $versionDir . '/' . $file;
            }
        }

        return self::$app->siteUrl . $file;
    }

}