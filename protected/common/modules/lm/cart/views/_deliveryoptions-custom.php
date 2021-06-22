<?php
use yii\bootstrap\Alert;

/* @var $this \frontend\web\View */
/* @var $form \usni\library\bootstrap\ActiveForm */
/* @var $formDTO \cart\dto\CheckoutDTO */
$shippingMethods     = $formDTO->getShippingMethods();
$model          = $formDTO->getCheckout()->deliveryOptionsEditForm;
?>
<div class="row">
    <h6 class="widget-title border-left mb-20"><?php
        echo \usni\UsniAdaptor::t('shipping', 'Shipping Method');?></h6>
    <?php
    $modelErrors = $model->getErrors();
    if (!empty($modelErrors)): ?>
        <?= Alert::widget(
            ['options' => [
                'class' => 'alert-danger alert fade in'],
                'body' => 'Lūdzu izvēlies piegādes veidu',
            ]) ?>
    <?php
    endif;
    ?>
    <?php /* Alert::widget(
            ['options' => [
                'class' => 'alert-warning'],
                'body' => 'Piegādes maksa ir mainīga, atkarībā no izvēlētā piegādes veida, preču svara un izmēriem. 
                               Precīzas piegādes izmaksas tiks norādītas rēķinā.'
            ]) */

    echo $form->field($model, 'shipping')
        ->radioList($shippingMethods, ['encode' => false])
        ->label(false);

    foreach($shippingMethods as $code => $label) {
        echo $this->render('_deliveryoptions-item', [
            'index' => $code,
            'value' => $code,
            'form' => $form,
            'formDTO' => $formDTO,
        ]);
    }
    ?>
</div>
