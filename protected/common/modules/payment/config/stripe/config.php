<?php
use usni\UsniAdaptor;
use common\modules\extension\models\Extension;
return [
        'code'      => 'stripe',
        'name'      => UsniAdaptor::t('payment', 'Stripe'),
        'author'    => 'WhatACart',
        'version'   => '1.0',
        'product_version' => '2.0.3',
        'status'    => Extension::STATUS_INACTIVE,
        'category'  => 'payment'
        ];

