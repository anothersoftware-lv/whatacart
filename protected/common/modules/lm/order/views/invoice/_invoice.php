<?php
use common\modules\order\widgets\InvoiceView;
use products\behaviors\PriceBehavior;


/* @var $this \usni\library\web\AdminView */
$this->attachBehavior('priceBehavior', PriceBehavior::className());
$model = $detailViewDTO->getModel();
echo InvoiceView::widget(['invoice' => $detailViewDTO->getModel(), 'orderProducts' => $detailViewDTO->getOrderProducts()]);
