<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace cart\models;

use yii\base\Model;
use usni\UsniAdaptor;
/**
 * DeliveryOptionsEditForm class file
 * 
 * @package cart\models
 */
class DeliveryOptionsEditFormCustom extends DeliveryOptionsEditForm
{
    public $shippingMethodForms = [];
    public $shipping_location_id;

    public function rules()
    {
        return array(
            [['shipping', 'comments', 'shipping_fee', 'shipping_location_id'],  'safe'],
            [
                ['shipping'],
                'required',
                'message' => '<div class="alert alert-danger">Lūdzu izvēlies piegādes veidu.</div>',
            ],
            [['shipping_location_id'], 'number'],
        );
    }

    function isToAddress()
    {
        return 'to_address' == $this->shipping;
    }
}
