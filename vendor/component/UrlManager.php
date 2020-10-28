<?php

namespace component;

class UrlManager extends \ObjectAccess
{
    private $_hostInfo;

    public function createUrl($url, $option = [])
    {
        $route = trim($url, '/');

        $baseUrl = $this->showScriptName || !$this->enablePrettyUrl ? \App::$request->getScriptUrl() : \App::$request->getBaseUrl();
        $p = [];
        foreach ($option as $key => $value) {
            $p[] = $key . '=' . $value;
        }
        //可以确认前面是host
        if ($baseUrl == '/index.php') {
            return '/' . trim($route . '?' . implode('&', $p), '?');
        }
        $route = trim($route . '&' . implode('&', $p), '&');
        $route = $route !== '' ? '?s=' . $route : '';
        return $baseUrl . $route;
    }

    public function staticUrl($url)
    {
        $route = trim($url, '/');
        $baseUrl = $this->showScriptName || !$this->enablePrettyUrl ? \App::$request->getScriptUrl() : \App::$request->getBaseUrl();
        return str_replace('/index.php', '/' . $route, $baseUrl);
    }

    public function getHostInfo()
    {
        if ($this->_hostInfo === null) {
            $request = \App::$request;
            $this->_hostInfo = $request->getHostInfo();
        }
        return $this->_hostInfo;
    }

}