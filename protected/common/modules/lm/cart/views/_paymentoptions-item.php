<?php
$paymentFactory = new \common\modules\payment\components\PaymentFactory(['type' => $value]);

$content = $paymentFactory->getInstance()->getSelectionBody($form, $formDTO->getCheckout());
?>
<div class="collapse payment-options-collapse" id="payment-options-accordion-item-<?= $index ?>">
    <?= $content ?>
</div>