<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl.html
 */
namespace common\modules\order\business;

use cart\dto\CheckoutDTO;
use common\modules\order\models\OrderShippingDetails;
use common\modules\shipping\components\ShippingFactory;
use common\modules\shipping\traits\ShippingTraitCustom;
use yii\base\Model;
use common\modules\order\models\Order;
use cart\models\PaymentMethodEditForm;
use products\behaviors\PriceBehavior;
use cart\dto\ReviewDTO;
use common\modules\shipping\dao\ShippingDAO;
use common\modules\localization\modules\orderstatus\dao\OrderStatusDAO;
use usni\library\utils\ArrayUtil;
use customer\models\Customer;
use common\modules\order\dao\OrderDAO;
use usni\library\modules\users\models\Address;
use usni\UsniAdaptor;
use cart\behaviors\CheckoutManagerBehaviorCustom as CheckoutManagerBehavior;
use common\modules\order\events\ConfirmOrderEvent;
use \common\modules\shipping\traits\ShippingTraitCustom as ShippingTrait;

/**
 * AdminCheckoutManager implements the business logic for checkout
 *
 * @package common\modules\order\business
 */
class AdminCheckoutManagerCustom extends AdminCheckoutManager
{
    use ShippingTrait;
    use ShippingTraitCustom;

    /**
     * Process checkout
     * @param CheckoutDTO $checkoutDTO
     */
    public function processCheckout($checkoutDTO)
    {
        $postData   = $checkoutDTO->getPostData();
        $cart       = $checkoutDTO->getCart();
        /* @var $checkout \cart\models\Checkout*/
        $checkout   = $checkoutDTO->getCheckout();

        $deliveryInfoEditForm = $checkout->deliveryInfoEditForm;
        $deliveryOptionsEditForm = $checkout->deliveryOptionsEditForm;
        $billingInfoEditForm = $checkout->billingInfoEditForm;
        
        if ($cart->isShippingRequired()) {

            $deliveryOptionsEditForm->shippingMethodForms = [];

            $shippingMethods = $this->getShippingMethods();

            foreach ($shippingMethods as $code => $label) {

                $deliveryOptionsEditForm->shippingMethodForms[$code] = [];

                $shippingFactory = new \common\modules\shipping\components\ShippingFactory(['type' => $code]);
                $formModels = $shippingFactory->getInstance()->getFormModels();

                foreach ($formModels as $className => $model) {
                    $deliveryOptionsEditForm->shippingMethodForms[$code][$className] = $model;
                }
            }
        }
        
        if(isset($postData['BillingInfoEditFormCustom']))
        {
            $hasInvalidModel = false;
            
            $billingInfoEditForm->setScenario('submit');

            $billingInfoEditForm->attributes = $postData['BillingInfoEditFormCustom'];

            if(!$billingInfoEditForm->validate()) {
                $hasInvalidModel = true;
            }

            $checkout->paymentMethodEditForm->attributes = $postData['PaymentMethodEditForm'];

            if(!$checkout->paymentMethodEditForm->validate()) {
                $hasInvalidModel = true;
            }

            if($cart->isShippingRequired())  {
                $deliveryInfoEditForm->setScenario('submit');

                $deliveryInfoEditForm->attributes = $postData['DeliveryInfoEditFormCustom'];
                $deliveryOptionsEditForm->attributes = $postData['DeliveryOptionsEditFormCustom'];
                
                $shippingMethodCode = $postData['DeliveryOptionsEditFormCustom']['shipping'];
                
                // Validate forms from shipping modules
                if( $shippingMethodCode) {
                    
                    $deliveryInfoEditForm->addressRequired = $checkout->deliveryOptionsEditForm->isToAddress();
                    
                    foreach ($deliveryOptionsEditForm->shippingMethodForms[$shippingMethodCode] as $className => $model) {
                        $deliveryOptionsEditForm->shippingMethodForms[$shippingMethodCode][$className]->attributes =
                            isset($postData[$className])
                                ? $postData[$className]
                                : [];
                        $deliveryOptionsEditForm->shippingMethodForms[$shippingMethodCode][$className]->setScenario('submit');
                        if (!$deliveryOptionsEditForm->shippingMethodForms[$shippingMethodCode][$className]->validate()) {
                            $hasInvalidModel = true;
                        }
                    }

                    if(!$hasInvalidModel) {
                        
                        $shippingFactory = new ShippingFactory(['type' => $shippingMethodCode]);
                        $instance = $shippingFactory->getInstance();
                        $shippingDetails = $instance->getOrderDetails($checkout);

                        $attributes = [
                            'shippingDetails' => $shippingDetails,
                        ];

                        if( !empty($shippingDetails['delivery_point_id'])) {
                            $attributes['shipping_location_id'] = $shippingDetails['delivery_point_id'];
                        }

                        $fillAttributes = ['firstname', 'lastname', 'mobilephone', 'email'];

                        
                        //@FIXME - dangerous! Should be validated against checkbox - "the same person"
                        foreach($fillAttributes as $attribute) {
                            if(empty($deliveryInfoEditForm->$attribute)) {
                                $deliveryInfoEditForm->$attribute = $billingInfoEditForm->$attribute;
                            }
                        }
                        
                        $deliveryInfoEditForm->setAttributes($attributes);
                    }
                }
                
                if(!$deliveryInfoEditForm->validate()) {
                    $hasInvalidModel = true;
                }
            }
            
            $checkout->deliveryInfoEditForm = $deliveryInfoEditForm;
            $checkout->deliveryOptionsEditForm = $deliveryOptionsEditForm;
            $checkout->billingInfoEditForm = $billingInfoEditForm;
            
            $checkoutDTO->setCheckout($checkout);
            
            if(!$hasInvalidModel)  {
                $this->processOrderCreation($checkoutDTO);
            }
        }
        else
        {
            $this->populateCustomerInfoInFormModel($checkoutDTO, Address::TYPE_BILLING_ADDRESS, 'billingInfoEditForm');
            $this->populateCustomerInfoInFormModel($checkoutDTO, Address::TYPE_SHIPPING_ADDRESS, 'deliveryInfoEditForm');
        }
        $checkout->deliveryOptionsEditForm->shipping        = $checkout->order->shipping;
        if(!empty($checkout->order->orderPaymentDetails))
        {
            $checkout->paymentMethodEditForm->payment_method    = $checkout->order->orderPaymentDetails->payment_method;
        }

        $shippingMethods    = $this->getShippingMethods();
        $checkoutDTO->setShippingMethods($shippingMethods);
        $paymentMethods     = $this->getPaymentMethodDropdown();
        $checkoutDTO->setPaymentMethods($paymentMethods);
    }

    /**
     * Process order creation
     * @param CheckoutDTO $checkoutDTO
     */
    protected function processOrderCreation($checkoutDTO)
    {
        $checkout = $checkoutDTO->getCheckout();
        $checkout->order->store_id    = $this->selectedStoreId;
        $checkout->order->interface   = $checkoutDTO->getInterface();
        $paymentMethod   = $checkout->paymentMethodEditForm->payment_method;
        $shippingMethod  = $checkout->deliveryOptionsEditForm->shipping;
        if($shippingMethod != null)
        {
            $checkout->deliveryOptionsEditForm->shipping_fee = $this->getCalculatedPriceByType($shippingMethod, $checkoutDTO->getCart());
        }
        $paymentFactoryClassName    = $this->paymentFactoryClassName;
        $paymentFactory             = new $paymentFactoryClassName([
            'type' => $paymentMethod,
            'order' => $checkout->order,
            'checkoutDetails' => $checkout,
            'cartDetails'     => $checkoutDTO->getCart(),
            'customerId'      => $checkoutDTO->getCustomerId()]);
        $instance           = $paymentFactory->getInstance();
        $result             = $instance->processPurchase();
        $checkoutDTO->setResult($result);
    }


    /**
     * Populate review dto
     * @param ReviewDTO $reviewDTO
     * @param Checkout $checkout
     * @param Cart $cartmore lo 
     */
    public function populateReviewDTO($reviewDTO, $checkout, $cart)
    {
        $reviewDTO->setBillingContent($checkout->billingInfoEditForm->getConcatenatedAddress());
        if($cart->isShippingRequired())
        {
            $reviewDTO->setShippingContent($checkout->deliveryInfoEditForm->getConcatenatedAddress());

            $shippingName = ShippingDAO::getShippingMethodName($checkout->deliveryOptionsEditForm->shipping,
                $this->language);

            $shippingDetails = $checkout->deliveryInfoEditForm->shippingDetails;
            
            if(!empty($shippingDetails['delivery_point_id'])) {

                $shippingLocation = \common\modules\shipping\models\ShippingLocations::findOne(
                    $shippingDetails['delivery_point_id']
                );
                
                $shippingName .= '<br/>'
                    . $shippingLocation->name . ' '
                    . $shippingLocation->address;
                
                unset($shippingDetails['delivery_point_id']);
            }
            
            $reviewDTO->setShippingName($shippingName);
        }
        $paymentMethodName = $this->getPaymentMethodName($checkout->paymentMethodEditForm->payment_method);
        $reviewDTO->setPaymentMethodName($paymentMethodName);
        $allStatus      = OrderStatusDAO::getAll(UsniAdaptor::app()->languageManager->selectedLanguage);
        $allStatusMap   = ArrayUtil::map($allStatus, 'id', 'name');
        $reviewDTO->setAllStatus($allStatusMap);
    }

    /**
     * Populate customer info in model
     * @param CheckoutDTO $checkoutDTO
     * @param int $type
     * @param string $attribute
     */
    public function populateCustomerInfoInFormModel($checkoutDTO, $type, $attribute)
    {
        $customerId = $checkoutDTO->getCustomerId();
        if($customerId != null && $customerId != Customer::GUEST_CUSTOMER_ID)
        {
            $address = OrderDAO::getLatestOrderAddressByType($customerId, $type);
            if($address !== false)
            {
                $checkoutDTO->getCheckout()->$attribute->attributes = $address;
            }

            $fillAttributes = [
                'address1',
                'address2',
                'city',
                'state',
                'country',
                'postal_code',
                'firstname',
                'lastname',
                'mobilephone',
                'email',
            ];
            
            $emptyAttributes = [];
            
            foreach($fillAttributes as $attr) {
                if(empty($address[$attr])) {
                    $emptyAttributes[] = $attr;
                }
            }
            
            if(!empty($emptyAttributes)) {
                
                $model = Customer::findOne($customerId);

                $addressAttributes = [
                    'address1' => $model->address->address1,
                    'address2' => $model->address->address2,
                    'city' => $model->address->city,
                    'state' => $model->address->state,
                    'country' => $model->address->country,
                    'postal_code' => $model->address->postal_code,
                    'firstname' => $model->person->firstname,
                    'lastname' => $model->person->lastname,
                    'mobilephone' => $model->person->mobilephone,
                    'email' => $model->person->email,
                ];

                foreach($emptyAttributes as $attr) {
                    $checkoutDTO->getCheckout()->$attribute->$attr = $addressAttributes[$attr];
                }

                //$checkoutDTO->getCheckout()->$attribute->attributes = $addressAttributes;
            }
        }
    }
}