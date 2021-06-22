<?php
use usni\library\widgets\DetailView;
use usni\UsniAdaptor;
use common\modules\order\utils\OrderUtil;
use yii\helpers\Url;
use kartik\editable\Editable;

/* @var $detailViewDTO \common\modules\order\dto\DetailViewDTO */
/* @var $this \usni\library\web\AdminView */

$model          = $detailViewDTO->getModel();
$widgetParams   = [
                    'detailViewDTO' => $detailViewDTO,
                    'decoratorView' => false,
                    'model'         => $model,
                    'attributes'    => [
                        [
                            'attribute' => 'billingAddress',
                            'label'      => UsniAdaptor::t('users', 'Address'),
                            'format' => 'raw',
                            'value' => Editable::widget([
                                'name' => 'name',
                                'asPopover' => true,
                                'value' => OrderUtil::getConcatenatedAddress($model['billingAddress']),
                                'header' => UsniAdaptor::t('users', 'Address'),
                                'inputType' => Editable::INPUT_TEXTAREA,
                                'size' => 'md',
                                'options' => [
                                    'class' => 'form-control',
                                ],
                                'ajaxSettings' => [
                                    'url' => Url::to(['editable', 'id' => $model['id']]),
                                ],
                            ]),
                        ],
                    ]
                ];
echo DetailView::widget($widgetParams);