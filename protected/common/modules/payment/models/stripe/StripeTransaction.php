<?php
namespace common\modules\payment\models\stripe;

use usni\library\db\ActiveRecord;
use usni\UsniAdaptor;
use common\modules\payment\utils\PaymentUtil;
/**
 * StripeTransaction class  file.
 *
 * @package common\modules\payment\models\stripe
 */
class StripeTransaction extends ActiveRecord 
{
    /**
     * @inheritdoc
     */
    public function rules() 
    {
        return [
            [['payment_status','transaction_id', 'amount', 'received_date', 'order_id'], 'required'],
            [['payment_status','transaction_id', 'amount', 'received_date', 'order_id', 'debug_data'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenario               = parent::scenarios();
        $scenario['create']     = $scenario['update'] = ['payment_status','transaction_id', 'amount', 'received_date', 'order_id', 'debug_data'];
        return $scenario;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() 
    {
        return PaymentUtil::getTransactionAttributeLabels();
    }
    
    /**
     * @inheritdoc
     */
    public static function getLabel($n = 1)
    {
        return $n == 1 ? UsniAdaptor::t('payment', 'Stripe Transaction') : UsniAdaptor::t('payment', 'Stripe Transactions');
    }
}