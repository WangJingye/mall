<?php

use component\Config;
use component\Session;
use component\UrlManager;

class App extends ObjectAccess
{
    /** @var \Request $request */
    public static $request;

    /** @var array $user */
    public static $user;

    /** @var UrlManager $urlManager */
    public static $urlManager;

    /** @var Config $config */
    public static $config;

    /** @var Session $session */
    public static $session;

    public static function init()
    {
        //未定义APP_DEBUG，默认为debug模式
        if (!defined('APP_DEBUG')) {
            define('APP_DEBUG', true);
        }
        static::$config = new Config();
        static::$urlManager = new UrlManager();
        static::$session = new Session();
    }

    /**
     * @throws Exception
     */
    public static function run()
    {
        try {
            //初始化配置文件
            self::init();
            //处理请求
            $request = Request::instance();
            $request->parseParams();
            self::$request = $request;

            if ($request->action == 'generate' && $request->controller == null) {
                self::generateHtml();
                exit;
            }
            $action = $request->action;
            $arr = explode('-', $action);
            $i = 0;
            foreach ($arr as $key => $v) {
                if ($i != 0) {
                    $arr[$key] = ucfirst($v);
                }
                $i++;
            }
            $action = implode('', $arr) . 'Action';
            $controller = $request->controllerNamespace;
            if (!file_exists(str_replace('\\', '/', BASE_PATH . $controller . '.php'))) {
                $msg = APP_DEBUG ? 'Controller is not exist' : 'Page not found';
                throw new \Exception($msg, 404);
            }

            /** @var Controller $controller */
            $controller = new $controller();
            if (!in_array($action, get_class_methods($controller))) {
                $msg = APP_DEBUG ? 'Action is not exist' : 'Page not found';
                throw new \Exception($msg, 404);
            }
            //执行action
            $controller->before();
            $ret = $controller->$action();
            $controller->after($ret);

        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            if ($errorCode == 0) {
                $errorCode = 400;
            }
            if (APP == 'api' || (APP == 'admin' && $request->isAjax())) {
                exit(json_encode(['code' => $errorCode, 'message' => $e->getMessage(), 'data' => null]));
            } else if (APP == 'console') {
                throw $e;
            }
            header('status:' . $errorCode);
            $view = APP_PATH . 'common/' . 'layout/error.php';
            if (!APP_DEBUG && file_exists($view)) {
                include $view;
                exit();
            } else {
                throw $e;
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function generateHtml()
    {
        if (self::$request->isPost()) {
            $data = self::$request->params;
            if ($data['type'] == 'show-table') {
                try {
                    $fields = Db::table($data['table'])->getFields();
                    echo json_encode(['code' => 200, 'data' => array_keys($fields)]);
                } catch (Exception $e) {
                    echo json_encode(['code' => 400, 'message' => $e->getMessage()]);
                }
                die;
            } else {
                if (isset($data['fcomment'])) {
                    generate\Generate::instance($data)->run();
                }
            }
        }
        $view = BASE_PATH . 'core/generate/view.php';
        include $view;
    }

}