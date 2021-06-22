<?php
use usni\UsniAdaptor;
use common\modules\asl_address\dao\StateDAO;

$language =  UsniAdaptor::app()->languageManager->selectedLanguage;
$states = \common\utils\AddressUtil::getStatesDropdownArray();
?>
<h6 class="widget-title border-left mb-20"><?= UsniAdaptor::t('customer', 'Billing Address');?></h6>
<div class="row">
    <?= $this->render('@common/modules/cms/views/_state-city', ['form' => $form, 'model' => $model, 'states' => $states]) ?>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'address1')->textInput(['placeholder' => ''])?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'postal_code')->textInput(['placeholder' => $model->getAttributeLabel('postal_code')])?>
    </div>
    <?= $form->field($model, 'country')->hiddenInput(['value' => 'LV'])->label(false);?>
    <!--<a href="#" id="set-alternate-address-button">Ierakstīt speciālu adresi</a>-->
</div>
