<?php
use \yii\bootstrap\Html;

$model = $formDTO->getCheckout()->billingInfoEditForm;
?>
    <h6 class="widget-title border-left mb-20"><?= \usni\UsniAdaptor::t('customer', 'Billing Receiver');?></h6>

<div class="row">
    <div class="col-sm-4">
        <?= $form->field($model, 'firstname')->textInput(['placeholder' => 'Ieraksti vārdu...']);?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'lastname')->textInput(['placeholder' => 'Ieraksti uzvārdu...']);?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'mobilephone')->textInput(['placeholder' => 'Ieraksti tālruņa numuru...']);?>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Ieraksti Epastu...']);?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'agree_to_newsletter')
            ->dropDownList(
                [1 => 'Epastā', 0 => 'Nevēlos saņemt']
//                ['placeholder' => $model->getAttributeLabel('agreeToNewsletter')]
            );?>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <?php
        if ($formInterface == 'front') {

            $linksToTerms =  Html::a(
                'Piegādes',
                ['/cms/site/page', 'alias' => 'piegade'],
                ['target' => '_blank']
            );
            $linksToTerms .= ', ' . Html::a(
                    'Garantijas un atteikuma tiesību',
                    ['/cms/site/page', 'alias' => 'garantija-un-atteikuma-tiesibas'],
                    ['target' => '_blank']
                );
            $linksToTerms .= ', un ' . Html::a(
                    'Privātuma',
                    ['/cms/site/page', 'alias' => 'privatuma-nosacijumi'],
                    ['target' => '_blank']
                );
            $linksToTerms .= ' nosacījumiem.';

            echo $form->field($model, 'agree_to_terms', [
                'horizontalCssClasses' => ['wrapper' => 'col-sm-12'],
                'checkboxTemplate' => "{beginWrapper}\n<div class=\"checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n"
                    . $linksToTerms . "{endLabel}\n</div>\n{error}\n{endWrapper}"
            ])->checkbox();
            //echo $this->render('/_termsmodal', ['formDTO' => $form]);
        } else {
            echo Html::activeHiddenInput($model, 'agree_to_terms', ['value' => 1]);
        }
        ?>
    </div>
</div>

<?php
/*
<?php

?>

*Firmas nosaukums:
*Reģistrācijas numurs:
*PVN numurs:
*Juridiska adrese:
*Bankas nosaukums:
*Bankas konts*:
(Lūdzam norādīt bankas norēķinu kontu, no kura tiks veikta preces apmaksa)
*Jūsu e-pasts:
Telefona Nr.:
*Pasūtījuma ID numurs:
*Jūsu īsziņa:

Apliecinu, ka iegādātā prece tiks izmantota saimnieciskās darbības veikšanai un nodrošināšanai
Apliecinu, ka komersants ir atbildīgs par atgrieztā pievienotās vērtības nodokļa nomaksu atbilstoši Pievienotās vērtības nodokļa likuma 143.1 pantam.

* Lūdzam norādīt bankas norēķinu kontu, no kuras tiks veikta preces apmaksa



*/