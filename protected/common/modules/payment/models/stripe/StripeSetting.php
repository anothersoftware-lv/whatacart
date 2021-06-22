<?php
namespace common\modules\payment\models\stripe;

use usni\UsniAdaptor;
/**
 * StripeSetting class file.
 *
 * @package common\modules\payment\models\stripe
 */
class StripeSetting extends \yii\base\Model
{
    /**
     * @var string 
     */
    public $public_key;
    /**
     * @var string 
     */
    public $private_key;
    
    /**
     * @var boolean 
     */
    public $sandbox;
    
    /**
     * Transaction type for paypal which could be sale or authorization
     * @var type 
     */
    public $transactionType;
    
    public $order_status;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['public_key', 'private_key', 'transactionType', 'order_status'], 'required'],
            [['sandbox'], 'boolean'],
            [['public_key', 'private_key', 'transactionType', 'sandbox'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                    'public_key'    => UsniAdaptor::t('payment', 'Public Key'),
                    'sandbox'           => UsniAdaptor::t('paypal', 'Sandbox Environment'),
                    'private_key'        => UsniAdaptor::t('payment', 'Private Key'),
                    'transactionType'   => UsniAdaptor::t('paypal', 'Transaction Type'),
                    'order_status'  => UsniAdaptor::t('orderstatus', 'Order Status')
               ];
    }
    
    /**
     * Gets attribute hints.
     * @return array
     */
    public function attributeHints()
    {
        return [
                    'sandbox'           => UsniAdaptor::t('paypalhint', 'Enable sandbox'),
                    'public_key'        => UsniAdaptor::t('paymenthint', 'Public api key'),
                    'private_key'       => UsniAdaptor::t('paymenthint', 'Private api key'),
                    'transactionType'   => UsniAdaptor::t('paypalhint', 'Transaction type for stripe Charge or Authorization'),
                    'order_status'  => UsniAdaptor::t('paymenthint', 'Default order status')
               ];
    }
}
