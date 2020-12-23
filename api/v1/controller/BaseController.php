<?php

namespace api\v1\controller;


class BaseController extends \api\common\controller\BaseController
{
    public function init()
    {
        parent::init();
    }

    public function getLeftTime($second)
    {
        if ($second <= 0) {
            return '已结束';
        }
        $days = floor($second / (24 * 3600));
        $second = $second % (24 * 3600);
        $hours = floor($second / 3600);
        $second = $second % 3600;
        $minutes = floor($second / 60);
        $seconds = $second % 60;
        if ($days > 0) {
            $days = $days . '天';
        } else {
            $days = '';
        }
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);
        return $days . $hours . ':' . $minutes . ':' . $seconds;
    }

}