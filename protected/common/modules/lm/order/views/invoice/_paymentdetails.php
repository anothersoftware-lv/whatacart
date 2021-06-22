<?php
use usni\UsniAdaptor;
use common\modules\order\lib\numtotext\NumToText_LV;

$shippingPrice  = number_format($orderPayment['shipping_fee'], 2, ".", "");
$totalTax       = number_format($orderPayment['tax'], 2, ".", "");
$totalUnitPrice = number_format($orderPayment['total_including_tax'] - $totalTax, 2, ".", "");
$totalPrice     = number_format($orderPayment['total_including_tax'] + $shippingPrice, 2, ".", "");
?>
<div class="col-sm-4  col-sm-offset-8">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="text-right"><strong><?php echo UsniAdaptor::t('products', 'Sub-Total'); ?></strong></td>
                <td class="text-right"><?php echo $this->getPriceWithSymbol($totalUnitPrice, $currencySymbol); ?></td>
            </tr>
            <tr>
                <td class="text-right"><strong><?php echo UsniAdaptor::t('products', 'Tax'); ?></strong></td>
                <td class="text-right"><?php echo $this->getPriceWithSymbol($totalTax, $currencySymbol); ?></td>
            </tr>
            <?php
            if ($shippingPrice > 0)
            {
                ?>
                <tr>
                    <td class="text-right"><strong><?php echo UsniAdaptor::t('shipping', 'Shipping Cost'); ?></strong></td>
                    <td class="text-right"><?php echo $this->getPriceWithSymbol($shippingPrice, $currencySymbol); ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td class="text-right"><strong><?php echo UsniAdaptor::t('products', 'Total'); ?></strong></td>
                <td class="text-right"><?php echo $this->getPriceWithSymbol($totalPrice, $currencySymbol); ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="col-6 pull-right">
    <?php
    $numToText = new NumToText_LV();
    $currencies = $numToText->getCurrencies();
    $numToText->setCurrency($currencies['LV']['EUR']); ?>
    <b>Summa vārdiem:</b>
    <span style="margin-left: 10pt;margin-right: 30pt;">
        <?php echo $numToText->displayPrice($totalPrice); ?>
    </span>
</div>