<?php

namespace console\home\controller;

use component\ConsoleController;

class MessageController extends ConsoleController
{
    public function init()
    {
        parent::init();
    }

    /**
     * 消息发送
     * @throws \Exception
     */
    public function publishAction()
    {
        $childList = \Db::table('MessageActivityChild')->where(['status' => 2])->order('sort desc,id desc')->findAll();
        $list = [];
        foreach ($childList as $v) {
            $list[$v['activity_id']][$v['publish_version']][] = $v;
        }
        $insertList = [];
        $userIds = \Db::table('User')->field(['user_id'])->findAll();
        foreach ($list as $activityId => $vs) {
            $activity = \Db::table('MessageActivity')->where(['id' => $activityId])->find();
            $userIds = array_column($userIds, 'user_id');
            foreach ($vs as $version => $vList) {
                foreach ($userIds as $userId) {
                    $data = [
                        'category_id' => 1,
                        'title' => $activity['title'],
                        'user_id' => $userId,
                        'content' => $vList[0]['title'],
                    ];
                    $children = [];
                    $first = [];
                    foreach ($vList as $i => $v) {
                        if ($i == 0) {
                            $first = [
                                'title' => $v['title'],
                                'pic' => $v['pic'],
                                'link_type' => $v['link_type'],
                                'link' => $v['link']
                            ];
                        } else {
                            $children[] = [
                                'title' => $v['title'],
                                'pic' => $v['pic'],
                                'link_type' => $v['link_type'],
                                'link' => $v['link']
                            ];
                        }
                    }
                    $data['extra'] = json_encode([
                        'icon' => $activity['pic'],
                        'first' => $first,
                        'children' => $children
                    ]);
                    $insertList[] = $data;
                }
            }
        }
        $tmp = [];
        foreach ($insertList as $k => $v) {
            $tmp[(int)floor($k / 200)][] = $v;
        }
        foreach ($tmp as $vs) {
            \Db::table('Message')->multiInsert($vs);
        }
        \Db::table('MessageActivityChild')
            ->where(['id' => ['in', array_column($childList, 'id')]])
            ->where(['status' => 2])
            ->update(['status' => 3]);

    }
}