<?php
namespace common\modules\order\models;

use cart\models\BillingInfoEditFormCustom as BillingInfoEditForm;
use cart\models\DeliveryInfoEditFormCustom as DeliveryInfoEditForm;
use cart\models\DeliveryOptionsEditFormCustom as DeliveryOptionsEditForm;
use cart\models\PaymentMethodEditForm;


/**
 * AdminCheckout custom class file.
 *
 * @package common\modules\order\models
 */
class AdminCheckoutCustom extends AdminCheckout
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->billingInfoEditForm  = new BillingInfoEditForm();
        $this->deliveryInfoEditForm = new DeliveryInfoEditForm();
        $this->deliveryOptionsEditForm = new DeliveryOptionsEditForm();
    }
}