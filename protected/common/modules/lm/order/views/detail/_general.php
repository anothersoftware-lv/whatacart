<?php
use usni\library\widgets\DetailView;
use usni\UsniAdaptor;
use common\modules\localization\modules\orderstatus\widgets\StatusLabel;
use kartik\editable\Editable;
use yii\helpers\Url;
use common\modules\shipping\traits\ShippingTrait;

/* @var $detailViewDTO \usni\library\dto\DetailViewDTO */
/* @var $this \usni\library\web\AdminView */

$model              = $detailViewDTO->getModel();
$currencySymbol     = UsniAdaptor::app()->currencyManager->getCurrencySymbol($model['currency_code']);
$widgetParams   = [
                    'detailViewDTO' => $detailViewDTO,
                    'decoratorView' => false,
                    'model'         => $model,
                    'attributes'    => [
                                            'unique_id',
                                            [
                                                'label'      => UsniAdaptor::t('customer', 'Customer'),
                                                'attribute'  => 'customer_id',
                                                'value'      => function($model) {
                                                        return $model['customer_id'] > 0
                                                            ? \yii\bootstrap\Html::a(
                                                                $model['firstname'] . ' ' . $model['lastname'],
                                                                ['/customer/default/update', 'id' => $model['customer_id']],
                                                                ['target' => '_blank']
                                                            )
                                                            : $model['firstname'] . ' ' . $model['lastname'] . ' <b>Guest</b>';
                                                },
                                                'format'    => 'raw'
                                            ],
                                            [
                                                'attribute' => 'status',
                                                'value'     => function($model) {
                                                    $value = StatusLabel::widget(['model' => $model]);
                                                    return $value;
                                                },
                                                'format'    => 'raw'
                                            ],
                                            // Ir atsevišķā tabā "Pirkumu vēsture"
                                            /*[
                                                'attribute' => 'status',
                                                'value' => function($model) {
                                                    $label = new StatusLabel();
                                                    $labelValue = $label->getOrderStatusLabel($model['status']);
                                                    $allStatusses = array_flip($label->getAllOrderStatus());
                                                    $value = Editable::widget([
                                                        'name' => 'status',
                                                        'value' => $labelValue,
                                                        'asPopover' => true,
                                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                                        'data' => $allStatusses,
                                                        'size' => 'md',
                                                        'options' => [
                                                            'class' => 'form-control',
                                                        ],
                                                        'ajaxSettings' => [
                                                            'url' => Url::to(
                                                                [
                                                                    '/api/order/status/update',
                                                                    'id' => $model['id'],
                                                                    //'labelId' => $model['id']
                                                                ]
                                                            ),
                                                        ],
                                                    ]);
                                                    return $value;
                                                },
                                                'format'    => 'raw' 
                                            ],*/
                                            [
                                                'attribute' => 'shipping',
                                                'value'     => $model['shipping_method_name'],
                                                'format'    => 'raw'
                                            ],
                                            [
                                                 'attribute'  => 'shipping_fee',
                                                 'value'      => $this->getPriceWithSymbol($model['shipping_fee'], $currencySymbol)
                                             ],
                                            /*[
                                                'label'      => UsniAdaptor::t('stores', 'Store'),
                                                'attribute'  => 'store_id',
                                                'value'      => $model['store_name']
                                            ],*/
                                            'shipping_comments'
                                        ]
                    ];
echo DetailView::widget($widgetParams);

