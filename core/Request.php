<?php

class Request extends ObjectAccess
{
    public $action;
    public $controller;
    public $module;
    public $config;

    /** @var \Params $params */
    public $params;

    public $header;

    public $controllerNamespace;//控制器命名空间
    public $uri;
    public $defaultUri;

    private $_port;
    private $_scriptUrl;
    private $_baseUrl;
    public static $instance;

    /**
     * Db constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct()
    {
        $this->getHead();
    }

    /**
     * @throws \Exception
     */
    public function parseParams()
    {
        $res = $this->parseUri(isset($_GET['s']) ? $_GET['s'] : '');
        $this->module = $res['module'];
        $this->action = $res['action'];
        $this->controller = $res['controller'];
        $this->uri = $this->module . '/' . $this->controller . '/' . $this->action;
        //获取controller命名空间
        foreach ($res['params'] as $key => $v) {
            $_GET[$key] = $v;
            $_REQUEST[$key] = $v;
        }
        unset($_GET['s']);
        unset($_REQUEST['s']);
        if ($this->uri != $this->defaultUri) {
            if (in_array($this->module, $this->config['deny_module_list'])) {
                throw new \Exception('module is deny', 404);
            }
        }
        $params = new \Params();
        $params->load($_REQUEST);
        $payload = json_decode(file_get_contents('php://input'), true);
        $params->load($payload);
        $this->params = $this->trimString($params);
    }

    /**
     * @return Request
     * @throws \Exception
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param $uri
     * @return array
     * @throws \Exception
     */
    public function parseUri($uri)
    {
        $route = $uri ? explode('/', trim($uri, '/')) : [];
        $config = $this->config;
        if (!$config) {
            $config = \App::$config->instance;
            $this->defaultUri = $config['default_module'] . '/' . $config['default_controller'] . '/' . $config['default_action'];
            $this->config = $config;
        }
        $params = [];
        if (count($route) == 0) {
            $action = $config['default_action'];
            $controller = $config['default_controller'];
            $module = $config['default_module'];
        } else if (count($route) == 1 && $route[0] == 'generate') {
            $action = 'generate';
            $controller = null;
            $module = null;
        } else if (count($route) < 3) {
            throw new Exception('Page not found', 404);
        } else {
            $module = $route[0];
            $controller = $route[1];
            $action = $route[2];
        }
        $this->controllerNamespace = $this->getControllerNamespace($controller, $module);
        for ($i = 3; $i < count($route); $i += 2) {
            $params[$route[$i]] = isset($route[$i + 1]) ? $route[$i + 1] : '';
        }
        return [
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
            'params' => $params,
        ];
    }

    public function method()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function contentType()
    {
        $contentType = $_SERVER['CONTENT_TYPE'];
        if ($contentType) {
            if (strpos($contentType, ';')) {
                list($type) = explode(';', $contentType);
            } else {
                $type = $contentType;
            }
            return trim($type);
        }
        return '';
    }

    /**
     * 是否为GET请求
     * @access public
     * @return bool
     */
    public function isGet()
    {
        return $this->method() == 'GET';
    }

    /**
     * 是否为Ajax请求
     * @access public
     * @return bool
     */
    public function isAjax()
    {
        return ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false;
    }

    /**
     * 是否为POST请求
     * @access public
     * @return bool
     */
    public function isPost()
    {
        return $this->method() == 'POST';
    }

    /**
     * 是否为PUT请求
     * @access public
     * @return bool
     */
    public function isPut()
    {
        return $this->method() == 'PUT';
    }

    /**
     * 是否为DELTE请求
     * @access public
     * @return bool
     */
    public function isDelete()
    {
        return $this->method() == 'DELETE';
    }

    /**
     * 是否为HEAD请求
     * @access public
     * @return bool
     */
    public function isHead()
    {
        return $this->method() == 'HEAD';
    }

    /**
     * 是否为PATCH请求
     * @access public
     * @return bool
     */
    public function isPatch()
    {
        return $this->method() == 'PATCH';
    }

    /**
     * 是否为OPTIONS请求
     * @access public
     * @return bool
     */
    public function isOptions()
    {
        return $this->method() == 'OPTIONS';
    }

    /**
     * @param $name
     * @param null $default
     * @return null|string
     */
    public function getParams($name, $default = null)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        if ($default !== null) {
            return $default;
        }
        return '';
    }

    /**
     * @return array
     */
    private function getHead()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if ('HTTP_' == substr($key, 0, 5)) {
                $headers[strtolower(str_replace('_', '-', substr($key, 5)))] = $value;
            }
        }
        $this->header = $headers;

    }

    protected function trimString($params)
    {
        foreach ($params as $k => $param) {
            if (is_array($param)) {
                $params[$k] = $this->trimString($param);
            } else {
                $_str = trim($param);
                //过滤用户输入的bom
                $params[$k] = str_replace(chr(239) . chr(187) . chr(191), '', $_str);
            }
        }
        return $params;
    }

    protected function getControllerNamespace($controller, $module)
    {
        $arr = explode('-', $controller);
        foreach ($arr as $key => $v) {
            $arr[$key] = ucfirst($v);
        }
        $controller = implode('', $arr) . 'Controller';
        return (APP . '\\' . $module . '\\controller' . '\\' . $controller);
    }


    public function getIsSecureConnection()
    {
        if (isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)) {
            return true;
        }
        return false;
    }

    public function getHostInfo()
    {
        if ($this->_hostInfo === null) {
            $secure = $this->getIsSecureConnection();
            $http = $secure ? 'https' : 'http';
            if (isset($_SERVER['SERVER_NAME'])) {
                $this->_hostInfo = $http . '://' . $_SERVER['SERVER_NAME'];
                $port = $secure ? $this->getSecurePort() : $this->getPort();
                if (($port !== 80 && !$secure) || ($port !== 443 && $secure)) {
                    $this->_hostInfo .= ':' . $port;
                }
            }
        }

        return $this->_hostInfo;
    }

    public function getSecurePort()
    {
        if ($this->_securePort === null) {
            $serverPort = $this->getServerPort();
            $this->_securePort = $this->getIsSecureConnection() && $serverPort !== null ? $serverPort : 443;
        }

        return $this->_securePort;
    }

    public function getPort()
    {
        if ($this->_port === null) {
            $serverPort = $this->getServerPort();
            $this->_port = !$this->getIsSecureConnection() && $serverPort !== null ? $serverPort : 80;
        }

        return $this->_port;
    }

    public function getServerPort()
    {
        return isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : null;
    }

    public function getScriptFile()
    {
        if (isset($this->_scriptFile)) {
            return $this->_scriptFile;
        }

        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            return $_SERVER['SCRIPT_FILENAME'];
        }

        throw new \Exception('Unable to determine the entry script file path.');
    }

    public function getScriptUrl()
    {
        if ($this->_scriptUrl === null) {
            $scriptFile = $this->getScriptFile();
            $scriptName = basename($scriptFile);
            if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } elseif (!empty($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace([$_SERVER['DOCUMENT_ROOT'], '\\'], ['', '/'], $scriptFile);
            } else {
                throw new \Exception('Unable to determine the entry script URL.');
            }
        }

        return $this->_scriptUrl;
    }

    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }

        return $this->_baseUrl;
    }

}