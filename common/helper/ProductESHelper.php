<?php

namespace common\helper;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ProductESHelper
{
    /** @var Client */
    public $elasticsearch;

    /** @var ProductESHelper */
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
            $instance->elasticsearch = ClientBuilder::create()->setHosts($config)->build();
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

    public function createIndex($index = 'mall')
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

    public function existMapping($type = 'product', $index = 'mall')
    {
        $params = [
            'index' => $index, //索引名称
            'type' => $type, //索引名称
        ];
        return $this->elasticsearch->indices()->existsType($params);
    }

    public function existsDoc($id = 1, $type = 'product', $index = 'mall')
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];

        $response = $this->elasticsearch->exists($params);
        return $response;
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
                            'pic' => [
                                'type' => 'keyword'
                            ],
                            'price' => [
                                'type' => 'float'
                            ],
                            'comment_number' => [
                                'type' => 'integer'
                            ],
                            'good_comment_percent' => [
                                'type' => 'float'
                            ],
                            'sale_number' => [
                                'type' => 'integer'
                            ],
                            'created_at' => [
                                'type' => 'integer'
                            ],
                        ]
                    ],
                ]
            ];
            $this->elasticsearch->indices()->putMapping($params);
        }
    }

    public function createFlash()
    {
        if (!$this->existMapping('mall_flash', 'index')) {
            $params = [
                'index' => 'mall_flash',
                'type' => 'index',
                'body' => [
                    'index' => [
                        'properties' => [ //配置数据结构与类型
                            'flash_id' => [ //
                                'type' => 'integer',//类型 string、integer、float、double、boolean、date
                            ],
                            'show_id' => [ //
                                'type' => 'integer',//类型 string、integer、float、double、boolean、date
                            ],
                            'date' => [ //
                                'type' => 'date',//类型 string、integer、float、double、boolean、date
                            ],
                            'start_time' => [ //
                                'type' => 'integer',//类型 string、integer、float、double、boolean、date
                            ],
                            'end_time' => [ //
                                'type' => 'integer',//类型 string、integer、float、double、boolean、date
                            ]
                        ]
                    ],
                ]
            ];
            $this->elasticsearch->indices()->putMapping($params);
        }
    }

    public function deleteMapping($type = 'product', $index = 'mall')
    {
        if ($this->existMapping($type, $index)) {
            $params = [
                'index' => $index,
                'type' => $type
            ];
            $this->elasticsearch->indices()->deleteMapping($params);
        }
    }


    public function delete($id, $type = 'product', $index = 'mall')
    {
        if (!$this->existsDoc($id)) {
            return true;
        }
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];
        return $this->elasticsearch->delete($params);
    }

    public function get($id, $type = 'product', $index = 'mall')
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];

        return $this->elasticsearch->get($params);
    }

    public function getMapping($index, $type)
    {
        $params = [
            'index' => $index,
            'type' => $type
        ];
        return $this->elasticsearch->indices()->getMapping($params);
    }

    public function search($keywords, $page = 1, $size = 10, $sort = [], $index = 'mall', $type = 'product')
    {
        $from = ($page - 1) * $size;
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'product_name' => [
                                        'query' => $keywords,
                                        'boost' => 3, // 权重大
                                    ]
                                ]
                            ],
                            [
                                'match' => [
                                    'product_sub_name' => [
                                        'query' => $keywords,
                                        'boost' => 3,
                                    ]
                                ]
                            ], [
                                'match' => [
                                    'category_name' => [
                                        'query' => $keywords,
                                        'boost' => 2,
                                    ]
                                ]
                            ], [
                                'match' => [
                                    'brand' => [
                                        'query' => $keywords,
                                        'boost' => 1,
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
                'from' => $from,
                'size' => $size
            ]
        ];
        if (!empty($sort)) {
            $params['body']['sort'][$sort['name']] = ['order' => $sort['order']];
        }
        $results = $this->elasticsearch->search($params);
        $results = $results['hits'];
        $total = $results['total'];
        $totalPage = (int)ceil($total / $size);
        $list = array_column($results['hits'], '_source');
        return ['list' => $list, 'page' => $page, 'total_page' => $totalPage];
    }

    public function bulkDelete($idList, $type = 'product', $index = 'mall')
    {
        if (!count($idList)) {
            return true;
        }
        $params = [
            'index' => $index,
            'type' => $type,
        ];
        foreach ($idList as $id) {
            $params['body'][] = [
                'delete' => ['_id' => $id]
            ];
        }
        $this->elasticsearch->bulk($params);
    }

    public function bulkIndex($list, $type = 'product', $index = 'mall')
    {
        if (!count($list)) {
            return true;
        }
        $params = [
            'index' => $index,
            'type' => $type,
        ];
        foreach ($list as $v) {
            $params['body'][] = [
                'index' => ['_id' => $v['product_id']]
            ];
            $params['body'][] = $v;
        }
        $this->elasticsearch->bulk($params);
    }
}