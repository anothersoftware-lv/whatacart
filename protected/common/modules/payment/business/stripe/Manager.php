<?php

namespace common\modules\payment\business\stripe;

use common\modules\extension\models\Extension;
use common\modules\payment\db\stripe\StripeTransactionTableBuilder;
use usni\library\utils\ArrayUtil;
use common\modules\payment\models\stripe\StripeTransaction;
use common\modules\payment\models\stripe\StripeSetting;
use usni\UsniAdaptor;
use common\modules\order\services\NotificationService;
use products\behaviors\PriceBehavior;
use common\utils\ApplicationUtil;
use common\modules\order\dao\OrderDAO;
use common\modules\order\models\Order;
use Yii;
use customer\dao\CustomerDAO;
/**
 * Manager class file
 *
 * @package common\modules\payment\business\stripe
 */
class Manager extends \common\modules\payment\business\Manager
{
    use \common\modules\localization\modules\orderstatus\traits\OrderStatusTrait;
    use \common\modules\order\traits\OrderTrait;
    
    /**
     * inheritdoc
     */
    public function behaviors()
    {
        return [
            NotificationService::className(),
            PriceBehavior::className()
        ];
    }
    
    /**
     * Process change status.
     * @param integer $id
     * @param integer $status
     */
    public function processChangeStatus($id, $status)
    {
        $extension      = Extension::findOne($id);
        if($this->checkIfPaymentMethodAllowedToDeactivate() == false)
        {
            return false;
        }
        //Install/Uninstall PaypalStandardTransactionTableBuilder
        if($status == Extension::STATUS_ACTIVE)
        {
            //Install table
            $builderClassName = str_replace('/', '\\', StripeTransactionTableBuilder::className());
            $instance = new $builderClassName();
            $instance->buildTable();
        }
        elseif($status == Extension::STATUS_INACTIVE)
        {
            //Drop table
            $builderClassName = str_replace('/', '\\', StripeTransactionTableBuilder::className());
            $instance = new $builderClassName();
            $instance->dropTableIfExists($instance->getTableName());
        }
        $extension->status = $status;
        $extension->save();
        return true;
    }
    
    /**
     * Stripe settings.
     * @param StripeFormDTO $formDTO
     */
    public function processSettings($formDTO)
    {
        $stripeSettings     = new StripeSetting();
        $postData           = $formDTO->getPostData();
        if(!empty($postData))
        {
            $stripeSettings->attributes     = $postData;
            if($stripeSettings->validate())
            {
               $this->configManager->processInsertOrUpdateConfiguration($stripeSettings, 'stripe', 'payment', $this->selectedStoreId);
               $formDTO->setIsTransactionSuccess(true);
            }
        }
        else
        {
            $stripeSettings->attributes     = $this->configManager->getConfigurationByCode('stripe', 'payment');
        }
        $formDTO->setModel($stripeSettings);
        $orderStatusData = $this->getOrderStatusDropdownData();
        $formDTO->setOrderStatusDropdownData($orderStatusData);
        $formDTO->setTransactionType($this->getTransactionType());
    }
    
    /**
     * Sets variables.
     * 
     * @return void
     */
    public function getStripeConfig()
    {
        $stripeConfig = [];
        $inputConfig  = [];
        if(UsniAdaptor::app()->installed == true)
        {
            $inputConfig            = $this->configManager->getConfigurationByCode('stripe', 'payment');
            if(empty($inputConfig))
            {
                $stripeConfig['stripeSandbox']  = true;
                $stripeConfig['paymentAction']  = 'authorization';
                $stripeConfig['public_key']   = null;
                $stripeConfig['private_key']   = null;
                $stripeConfig['order_status']   = null;
            }
            else
            {
                $stripeConfig['stripeSandbox']  = $inputConfig['sandbox'];
                $stripeConfig['paymentAction']  = ArrayUtil::getValue($inputConfig, 'transactionType', 'authorization');
                $stripeConfig['public_key']   = $inputConfig['public_key'];
                $stripeConfig['private_key']   = $inputConfig['private_key'];
                $stripeConfig['order_status']  = $inputConfig['order_status'];
            }
            return $stripeConfig;
        }
    }
    
    /**
     * Get transaction type
     * @return array
     */
    public function getTransactionType()
    {
        return [
            'authorization' => UsniAdaptor::t('paypal', 'Authorization'),
            'charge' => UsniAdaptor::t('payment', 'Charge')
        ];
    }
    
    /**
     * Get transaction table builder class name
     * @return string
     */
    public function getTransactionTableBuilderClassName()
    {
        return StripeTransactionTableBuilder::className();
    }
    
    /**
     * Get transaction model class name
     * @return string
     */
    public function getTransactionModelClassName()
    {
        return StripeTransaction::className();
    }
    
    /**
     * Charge amount from card
     * @param array $postData
     */
    public function processCharge($postData)
    {
        $config = $this->getStripeConfig();
        \Stripe\Stripe::setApiKey($config['private_key']);
        $checkout       = ApplicationUtil::getCheckout();
        $order          = $checkout->order;
        $amount         = $this->getTotalAmount($order);
        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $postData['stripeToken'];
        if($config['stripeSandbox'])
        {
            $token = 'tok_visa';
        }
        $charge = \Stripe\Charge::create([
            'amount' => $amount * 100,
            'currency' => UsniAdaptor::app()->currencyManager->selectedCurrency,
            'description' => 'For order id: ' . $order['id'],
            'source' => $token,
            'metadata' => array(
                'order_id' => $order['id']
            ),
            'receipt_email' => $checkout->billingInfoEditForm->email
        ]);
        $stripeResponse = $charge->jsonSerialize();
        return $this->processOrderUpdate($stripeResponse, $order);
    }
    
    /**
     * Get total amount.
     * @param Order $order
     * @return float
     */
    public function getTotalAmount($order)
    {
        $cart   = ApplicationUtil::getCart();
        $total  = $cart->getAmount();
        $totalByCurrency = $this->getPriceByCurrency($total, UsniAdaptor::app()->currencyManager->selectedCurrency);
        if($order['shipping_fee'] > 0)
        {
            $totalByCurrency += $order['shipping_fee'];
        }
        return $totalByCurrency;
    }
    
    /**
     * Process order status
     * @param array $response
     * @param Order $order
     * @return void
     */
    public function processOrderUpdate($response, $order)
    {
        $stripeConfig           = $this->getStripeConfig();
        $orderId                = $order['id'];
        $userId                 = ApplicationUtil::getCustomerId();
        $orderTable             = UsniAdaptor::tablePrefix(). 'order';
        if($response['amount_refunded'] == 0 && empty($response['failure_code']) && $response['paid'] == 1 && $response['captured'] == 1 & $response['status'] == 'succeeded')
        {
            $paymentStatus = $stripeConfig['order_status'];
            $returnStatus  = 'success';
        }
        else
        {
            $paymentStatus = $this->getStatusId(Order::STATUS_FAILED, $this->language);
            $returnStatus  = $response['failure_code'];
        }
        
        $transactionId          = $response['balance_transaction'];
        $isSuccess              = false; //For db commit
        //If order id is null
        if($orderId == null)
        {
            Yii::error('Order id is null', 'stripe');
            return;
        }
        $order  = OrderDAO::getById($orderId, $this->language, $this->selectedStoreId);
        if($order == null)
        {
            Yii::error('The order corresponding to order id doesn\'t exist in the system', 'stripe');
            return;
        }
        $dbTransaction  = UsniAdaptor::app()->db->beginTransaction();
        try
        {
            //Update order
            UsniAdaptor::db()->createCommand()->update($orderTable, 
                                                [
                                                    'status' => $paymentStatus, 
                                                    'modified_by' => $userId,
                                                    'modified_datetime'  => UsniAdaptor::getNow()
                                                ], 
                                                'id = :id', 
                                                [':id' => $orderId])->execute();
            //Save paypal transaction
            $trData['order_id'] = $orderId;
            $trData['payment_status']   = $response['status'];
            $trData['transaction_id']   = $transactionId;
            $trData['amount']           = $response['amount'];
            $trData['debug_data']       = json_encode($response);
            $orderPaymentTransaction    = $this->saveStripeTransaction($trData); 
            if($orderPaymentTransaction !== false)
            {
                Yii::info('After saving stripe transaction', 'stripe');
                //Save payment transaction map.
                $isSuccess = $this->saveOrderPaymentTransactionMap($orderPaymentTransaction, $response['amount'], $orderId, 'stripe');
            }
            if($isSuccess)
            {
                $dbTransaction->commit();
                $order  = OrderDAO::getById($orderId, $this->language, $this->selectedStoreId);
                $this->saveOrderHistory($order, UsniAdaptor::t('stripe', 'Response from stripe with status {status} and transaction id {trid}'
                    , ['status' => $response['status'], 'trid' => $transactionId]), true);
                \Yii::info('Order history saved.', 'stripe');
            }
            else
            {
                Yii::error('Transaction fails', 'stripe');
                $dbTransaction->rollBack();
            }
        } 
        catch (\yii\db\Exception $e) 
        {
            $dbTransaction->rollBack();
            Yii::error('Stripe transaction fails with error ' . $e->getMessage(), 'stripe');
        }
        return $returnStatus;
    }
    
    /**
     * Save stripe transaction
     * @param array $data
     * @return boolean
     */
    public function saveStripeTransaction($data)
    {
        //Save transaction
        $orderPaymentTransaction = new StripeTransaction(['scenario' => 'create']);
        $orderPaymentTransaction->payment_status    = $data['payment_status'];
        $orderPaymentTransaction->transaction_id    = $data['transaction_id'];
        $orderPaymentTransaction->amount            = $data['amount'];
        $orderPaymentTransaction->received_date     = date('Y-m-d');
        $orderPaymentTransaction->order_id          = $data['order_id'];
        $orderPaymentTransaction->debug_data        = isset($data['debug_data']) ? $data['debug_data'] : null;
        if($orderPaymentTransaction->save())
        {
            return $orderPaymentTransaction;
        }
        return false;
    }
}
