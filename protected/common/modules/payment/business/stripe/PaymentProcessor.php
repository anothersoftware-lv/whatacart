<?php
namespace common\modules\payment\business\stripe;

/**
 * PaymentProcessor class file
 *
 * @package common\modules\payment\business\stripe
 */
class PaymentProcessor extends \common\modules\payment\business\BaseSitePaymentProcessor
{
    /**
     * @inheritdoc
     */
    public function processConfirm()
    {
        return true;
    }
}
