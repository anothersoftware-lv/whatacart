<?php
namespace cart\models;

use common\modules\order\models\OrderShippingDetails;
use usni\UsniAdaptor;
use common\modules\order\models\Order;
use cart\models\BillingInfoEditFormCustom as BillingInfoEditForm;
use cart\models\DeliveryInfoEditFormCustom as DeliveryInfoEditForm;
use cart\models\DeliveryOptionsEditFormCustom as DeliveryOptionsEditForm;

/**
 * Class storing the data for the checkout
 * 
 * @package cart\models
 */
class CheckoutCustom extends Checkout
{
    public function init()
    {
        parent::init();
        $this->billingInfoEditForm     = new BillingInfoEditForm();
        $this->deliveryInfoEditForm    = new DeliveryInfoEditForm();
        $this->deliveryOptionsEditForm = new DeliveryOptionsEditForm();
    }
}