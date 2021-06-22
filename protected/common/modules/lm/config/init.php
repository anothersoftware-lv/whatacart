<?php

return [
    'lm' => [
        'class' => 'common\modules\lm\Module',
        'modules' => [
            'base' => [
                'class' => 'LmModule\base\Module',
            ],
            'api' => [
                'class' => 'LmModule\api\Module',
                'modules' => [
                    'productcategories' => [
                        'class' => 'LmApi\modules\productCategories\Module',
                    ],
                    'products' => [
                        'class' => 'LmApi\modules\products\Module',
                    ],
                    'localization' => [
                        'class' => 'LmApi\modules\localization\Module',
                    ],
                    'shipping' => [
                        'class' => 'LmApi\modules\shipping\Module',
                    ],
                    'order' => [
                        'class' => 'LmApi\modules\order\Module',
                    ],
                    'customers' => [
                        'class' => 'LmApi\modules\customers\Module',
                    ],
                ],
            ],
            'invoice' => [
                'class' => 'LmModule\invoice\Module',
            ],
            'inventory' => [
                'class' => 'LmModule\inventory\Module',
            ],
            'import' => [
                'class' => 'LmModule\import\Module',
                'modules' => [
                    'catalog' => [
                        'class' => 'LmModule\import\modules\catalog\Module',
                        'modules' => [
                            'mobilux' => [
                                'class' => 'LmModule\import\modules\catalog\modules\mobilux\Module',
                            ],
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'class' => 'LmModule\tasks\Module',
            ],
            'prettyurl' => [
                'class' => 'LmModule\prettyurl\Module',
            ],
            'catalog' => [
                'class' => 'LmModule\catalog\Module',
                'modules' => [
                    'productCategories' => [
                        'class' => 'LmModule\catalog\modules\productCategories\Module',
                    ],
                    'products' => [
                        'class' => 'LmModule\catalog\modules\products\Module',
                    ]
                ]
            ],
            'menumanager' => [
                'class' => 'LmModule\menumanager\Module',
            ],
            'googlemaps' => [
                'class' => 'LmModule\menumanager\Module',
            ],
            'pagemanager' => [
                'class' => 'LmModule\pagemanager\Module',
            ],
            'streamer' => [
                'class' => 'LmModule\streamer\Module',
            ],
            'parser' => [
                'class' => 'LmModule\parser\Module',
            ],
            'slidermanager' => [
                'class' => 'LmModule\slidermanager\Module',
            ],
            'social' => [
                'class' => 'LmModule\social\Module',
            ],
            'xmlfeeds' => [
                'class' => 'LmModule\xmlfeeds\Module',
            ],
            'seomanager' => [
                'class' => 'LmModule\seomanager\Module',
            ],
            'localization' => [
                'class' => 'LmModule\localization\Module',
            ],
        ],
    ]
];
