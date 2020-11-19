<?php

namespace common\extend\elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class Elasticsearch
{
    /** @var Client */
    public $elasticsearch;

    /** @var Elasticsearch */
    public static $instance;

    public static function instance()
    {
        if (static::$instance == null) {
            $instance = new static();
            $ef = COMMON_PATH . 'config/elasticsearch.php';
            if (!file_exists($ef)) {
                throw new \Exception('elasticsearch配置文件elasticsearch.php不存在');
            }
            $config = require $ef;
            $instance->elasticsearch = ClientBuilder::create()->setHosts([$config['host'] . ':' . $config['port']])->build();
            static::$instance = $instance;
        }
        return static::$instance;
    }

    public function deleteIndex($index)
    {
        if ($this->existIndex($index)) {
            $params = [
                'index' => $index
            ];
            $this->elasticsearch->indices()->delete($params);
        }
    }

    public function createIndex($index)
    {
        if (!$this->existIndex($index)) {
            $params = [
                'index' => $index, //索引名称
            ];
            $this->elasticsearch->indices()->create($params);
        }
    }

    public function existIndex($index)
    {
        $params = [
            'index' => $index, //索引名称
        ];
        return $this->elasticsearch->indices()->exists($params);
    }

    public function existMapping($index, $type)
    {
        $params = [
            'index' => $index, //索引名称
            'type' => $type, //索引名称
        ];
        return $this->elasticsearch->indices()->existsType($params);
    }


    public function createProduct()
    {
        if (!$this->existMapping('mall', 'product')) {
            $params = [
                'index' => 'mall',
                'type' => 'product',
                'body' => [
                    'product' => [
                        'properties' => [ //配置数据结构与类型
                            'product_id' => [ //
                                'type' => 'integer',//类型 string、integer、float、double、boolean、date
                            ],
                            'product_name' => [ //
                                'type' => 'text',//类型 string、integer、float、double、boolean、date
                                'analyzer' => 'ik_max_word',
                                'search_analyzer' => 'ik_max_word',
                            ],
                            'product_sub_name' => [ //
                                'type' => 'text',//类型 string、integer、float、double、boolean、date
                                'analyzer' => 'ik_max_word',
                                'search_analyzer' => 'ik_max_word',
                            ],
                            'category_name' => [ //
                                'type' => 'text',//类型 string、integer、float、double、boolean、date
                                'analyzer' => 'ik_max_word',
                                'search_analyzer' => 'ik_max_word',
                            ],
                            'brand' => [ //
                                'type' => 'text',//类型 string、integer、float、double、boolean、date
                                'analyzer' => 'ik_max_word',
                                'search_analyzer' => 'ik_max_word',
                            ],
                        ]
                    ],
                ]
            ];
            $this->elasticsearch->indices()->putMapping($params);
        }
    }

    public function deleteProduct()
    {
        if ($this->existMapping('mall', 'product')) {
            $params = [
                'index' => 'mall',
                'type' => 'product'
            ];
            $this->elasticsearch->indices()->deleteMapping($params);
        }
    }

    public function put($type, $data)
    {
        $params = [
            'index' => 'mall',
            'type' => $type,
            'body' => [
                'product_id' => $data['product_id'],
                'product_name' => $data['product_name'],
                'product_sub_name' => $data['product_sub_name'],
                'category_name' => $data['category_name'],
                'brand' => $data['brand'],
            ]
        ];
        $this->elasticsearch->index($params);
    }
}