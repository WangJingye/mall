<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class FlashShowingService extends BaseService
{
    /**
     * @param $params
     * @param bool $ispage
     * @return FlashShowingService|array|mixed
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('FlashShowing');

        if (isset($params['show_id']) && $params['show_id'] != '') {
            $selector->where(['show_id' => $params['show_id']]);
        }
        if (isset($params['start_time']) && $params['start_time'] != '') {
            $selector->where(['start_time' => $params['start_time']]);
        }
        if (isset($params['end_time']) && $params['end_time'] != '') {
            $selector->where(['end_time' => $params['end_time']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        } else {
            $selector->where(['status' => ['!=', 0]]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('show_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveFlashShowing($data)
    {
        if (isset($data['show_id']) && $data['show_id']) {
            \Db::table('FlashShowing')->where(['show_id' => $data['show_id']])->update($data);
        } else {
            \Db::table('FlashShowing')->insert($data);
        }
    }
}