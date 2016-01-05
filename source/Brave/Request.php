<?php
namespace Brave;

class Request
{

    protected $ip;
    protected $method;
    protected $url;
    protected $pathInfo = [
        0            => '',
        'module'     => '',
        'controller' => '',
        'action'     => ''
    ];
    public $query;
    public $post;
    public $files;
    public $cookie;
    public $session;
    public $server;
    /**
     * @var $request Request
     */
    public static $request;

    public static function init()
    {
        if (false == self::$request) {
            self::$request = new self();

            $request = self::$request;
            $request->server = $_SERVER;
            $request->ip = $request->getIP();
            $request->method = $request->getMethod();
            $request->url = $request->getUrl();
            $request->query = $_GET;
            $request->post = $_POST;
            $request->files = $_FILES;
            $request->cookie = $_COOKIE;
            $request->session = $_SESSION;
            $request->parsePathInfo();

        }
        return self::$request;
    }


    protected function parsePathInfo()
    {
        $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        self::$request->pathInfo[0] = $pathInfo;
        $pathInfo = explode('/', trim($pathInfo, '/'));
        $action = null;
        $controller = null;
        if (count($pathInfo) >= 2) {
            $action = array_pop($pathInfo);
            $controller = array_pop($pathInfo);
        }

        if ($action == null) {
            $controller = 'Default';
            $action = 'Index';
        }
        if ($controller == null) {
            $controller = $action;
            $action = 'Index';
        }

        self::$request->pathInfo['action'] = ucwords($action);
        self::$request->pathInfo['controller'] = ucwords($controller);
        $module = '';
        if (!empty($pathInfo)) {
            foreach ($pathInfo as $key => $part) {
                $pathInfo[$key] = ucwords($part);
            }
            $module = implode('/', $pathInfo);
        }

        foreach (App::$config['module.alias'] as $realModule => $alias) {
            if (strtolower($module) == strtolower($alias)) {
                $module = rtrim($realModule, '/\\');
            }
        }

        self::$request->pathInfo['module'] = $module;
    }

    public function getParam($key, $default = null)
    {
        $value = self::query($key);
        if ($value == null) {
            $value = self::post($key, $default);
        }
        return $value;
    }

    public function query($key, $default = null)
    {
        return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
    }

    public function post($key, $default = null)
    {
        return array_key_exists($key, $_POST) ? $_POST[$key] : $default;
    }

    public function files($key)
    {
        return array_key_exists($key, $_FILES) ? $_FILES[$key] : null;
    }

    public function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        }
        return false;
    }

    public function isPost()
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    public function isGet()
    {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }

    public function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getUri()
    {
        return $uri = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
    }

    public function getUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function getIP()
    {
        $ip = 'unknow';
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        return $ip;
    }


    public function getPathInfo()
    {
        return self::$request->pathInfo[0];
    }

    public function getModule()
    {
        return self::$request->pathInfo['module'];
    }

    public function getController()
    {
        return self::$request->pathInfo['controller'];
    }

    public function getAction()
    {
        return self::$request->pathInfo['action'];
    }

}
