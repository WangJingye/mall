<?php

namespace component;

class RestController extends \Controller
{

    public $table = null;

    /**
     * $filter有值时使用当前值进行数据过滤，否则不进行
     * @var bool
     */
    public $filter = false;

    public function init()
    {
        parent::init();
    }

    /**
     * 获取分页
     * @return string
     */
    public function getLimit()
    {
        $get = \App::$request->params;
        $page = 1;
        if (isset($get['page']) && $get['page'] != '') {
            $page = $get['page'];
        }
        $perPage = 10;
        if (isset($get['per-page']) && $get['per-page'] != '') {
            $perPage = $get['per-page'];
        }
        return ($page - 1) * $perPage . ',' . $perPage;
    }
}