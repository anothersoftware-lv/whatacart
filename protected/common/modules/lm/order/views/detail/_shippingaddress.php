<?php
use usni\library\widgets\DetailView;
use usni\UsniAdaptor;
use common\modules\order\utils\OrderUtil;
use kartik\editable\Editable;
use yii\helpers\Url;


/* @var $detailViewDTO \common\modules\order\dto\DetailViewDTO */
/* @var $this \usni\library\web\AdminView */

$model          = $detailViewDTO->getModel();
$widgetParams   = [
                    'detailViewDTO' => $detailViewDTO,
                    'decoratorView' => false,
                    'model'         => $model,
                    'attributes'    => [
                        [
                            'attribute' => 'shipping',
                            'value' => function($model) {

                                $shippingManager = new \common\modules\shipping\business\Manager();
                                $methods = $shippingManager->getShippingMethods();
                                $labelValue = $model['shipping_method_name'];

                                $value = Editable::widget([
                                    'name' => 'shipping',
                                    'value' => $labelValue,
                                    'asPopover' => true,
                                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                    'data' => $methods,
                                    'size' => 'md',
                                    'options' => [
                                        'class' => 'form-control',
                                    ],
                                    'ajaxSettings' => [
                                        'url' => Url::to(
                                            [
                                                '/api/order/shipping/update',
                                                'id' => $model['id'],
                                            ]
                                        ),
                                    ],
                                ]);
                                return $value;
                            },
                            'format'    => 'raw'
                        ],
                        
                                            [
                                                'label'      => UsniAdaptor::t('users', 'Address'),
                                                'value'      => OrderUtil::getConcatenatedAddress($model['shippingAddress']),
                                                'format'     => 'raw'
                                            ]
                                        ]
                    ];
echo DetailView::widget($widgetParams);