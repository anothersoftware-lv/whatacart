<?php

/* @var $this \usni\library\web\AdminView */
/* @var $formDTO \usni\library\dto\FormDTO */

use usni\UsniAdaptor;
use usni\library\bootstrap\ActiveForm;
use usni\library\bootstrap\FormButtons;

$model          = $formDTO->getModel();
$dropdownData   = $formDTO->getOrderStatusDropdownData();
$transactionTypeArray   = $formDTO->getTransactionType();
$this->params['breadcrumbs'] = [
        [
            'label' => UsniAdaptor::t('application', 'Manage') . ' ' . UsniAdaptor::t('payment', 'Payments'),
            'url'   => ['/payment/default/index']
        ],
        [
            'label' => UsniAdaptor::t('payment', 'Stripe Settings')
        ]
];
$title = UsniAdaptor::t('payment', 'Stripe Settings');
$this->title = $title;
$form = ActiveForm::begin([
        'id' => 'stripesettingseditview',
        'layout' => 'horizontal',
        'caption' => $title
    ]);
?>
<?= $form->field($model, 'public_key')->textInput();?>
<?= $form->field($model, 'private_key')->textInput();?>
<?= $form->field($model, 'order_status')->select2input($dropdownData);?>
<?= $form->field($model, 'transactionType')->select2input($transactionTypeArray);?>
<?= $form->field($model, 'sandbox', ['horizontalCssClasses' => ['wrapper'   => 'col-sm-12'], 
                                         'horizontalCheckboxTemplate' => "{beginWrapper}\n<div class=\"checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{endWrapper}"])->checkbox();?>
<?= FormButtons::widget(['cancelUrl' => UsniAdaptor::createUrl('payment/default/index')]);?>
<?php ActiveForm::end();

