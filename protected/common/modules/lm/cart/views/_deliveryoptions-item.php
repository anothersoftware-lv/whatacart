<?php
$shippingFactory = new \common\modules\shipping\components\ShippingFactory(['type' => $value]);
$content = $shippingFactory->getInstance()->getSelectionBody($form, $formDTO->getCheckout());
?>
<div class="collapse delivery-options-collapse" id="delivery-options-accordion-item-<?= $index ?>">
    <?= $content ?>
</div>