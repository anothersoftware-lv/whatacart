<?php

use usni\library\utils\Html;
use usni\UsniAdaptor;
use common\modules\payment\business\paysera\Manager;

/* @var $this \frontend\web\View */
/* @var $form \usni\library\bootstrap\ActiveForm */
/* @var $formDTO \cart\dto\CheckoutDTO */
$paymentMethods = $formDTO->getPaymentMethods();

$accordionId = 'accordion-payment-methods';
$model = $formDTO->getCheckout()->paymentMethodEditForm;
if ($model->payment_method == null) {
    $keys = array_keys($paymentMethods);
    if (!empty($keys)) {
        $model->payment_method = $keys[0];
    }
}

echo $form->field($model, 'payment_method')
    ->radioList($paymentMethods, ['encode' => false])
    ->label(false);

foreach($paymentMethods as $code => $label) {
    echo $this->render('_paymentoptions-item', [
        'index' => $code,
        'value' => $code,
        'form' => $form,
        'formDTO' => $formDTO,
    ]);
}
?>