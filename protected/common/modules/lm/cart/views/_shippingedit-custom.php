<?php
use usni\UsniAdaptor;
use cart\utils\CartScriptUtil;

/* @var $form \usni\library\bootstrap\ActiveForm */
/* @var $this \frontend\web\View */
?><p><?= UsniAdaptor::t('customer', 'Shipping Address');?></p>

<?= $form->field(
        $model,
        'sameAsBillingAddress',
        [
                'horizontalCssClasses' => ['wrapper'   => 'col-sm-12'],
                'horizontalCheckboxTemplate' => "{beginWrapper}\n
                                        <div class=\"checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n
                                        </div>\n{error}\n{endWrapper}"
        ]
    )->checkbox();?>
<?php 
echo $this->render('@cart/views/_billingedit', ['form' => $form, 'model' => $model]);
CartScriptUtil::registerSameAsBillingAddressScript($this);