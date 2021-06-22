<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace cart\models;

use yii\base\Model;
use usni\UsniAdaptor;
/**
 * PaymentMethodEditForm class file
 *
 * @package cart\models
 */
class PaymentMethodEditForm extends Model
{
    /**
     * Paymemt method
     * @var int 
     */
    public $payment_method;
    
    /**
     * Comments for the payment
     * @var string 
     */
    public $comments;
    
    /**
     * Agreement for terms
     * @var boolean 
     */
    public $agree;
    
    /**
     * Validation rules for the model.
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            [['payment_method'],         'required'],
            [['payment_method', 'comments', 'agree'],  'safe'],
            [['agree'],         'required', 'isEmpty' => [$this, 'checkAgree'], 
                                            'requiredValue' => "1", 
                                            'message' => UsniAdaptor::t('cart', 'Customer should agree to terms and conditions for the purchase')]
        );
    }

    public function validatePaymentMethod()
    {
        if(empty($this->payment_method)) {
            $this->addError('shipping' , '<div class="alert alert-danger">Lūdzu izvēlies apmaksas veidu.</div>');
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function getLabel($n = 1)
    {
        return UsniAdaptor::t('payment', 'Payment Method');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'payment_method'    => UsniAdaptor::t('cart', 'Payment Method'),
                'gateway'    => UsniAdaptor::t('cart', 'Payment Type'),
                'comments'          => UsniAdaptor::t('application', 'Comments'),
                'agree'             => UsniAdaptor::t('application', 'I agree to')
               ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'comments'       => UsniAdaptor::t('paymenthint', 'Comments while making payment')
        ];
    }
    
    /**
     * Check if agree is empty
     * @param string $value
     * @return boolean
     */
    public function checkAgree($value)
    {
        return empty($value);
    }
}
