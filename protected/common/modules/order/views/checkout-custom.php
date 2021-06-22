<?php
use usni\UsniAdaptor;
use wishlist\widgets\WishlistSubView;
use cart\widgets\SiteCartSubView;
use yii\web\View;
use common\modules\asl_address\assets\AddressAsset;
use frontend\widgets\ActiveForm;
use yii\bootstrap\Html;

AddressAsset::register($this);

/* @var $this \frontend\web\View */
/* @var $formDTO \cart\dto\CheckoutDTO */

$this->title = $this->params['breadcrumbs'][] = UsniAdaptor::t('cart', 'Checkout');
$isShippingRequired = $formDTO->getCart()->isShippingRequired();

$shippingMethods = $formDTO->getShippingMethods();
$paymentMethods  = $formDTO->getPaymentMethods();

if($isShippingRequired && empty($shippingMethods)): ?>
    <div class="shop-section mb-80">
        <div class="container">
            <p><?php echo UsniAdaptor::t('cart', "Shipping methods are not enabled. Please contact admin to enable shipping methods.");?></p>
        </div>
    </div>
<?php return; endif;

if(empty($paymentMethods)): ?>
    <div class="shop-section mb-80">
        <div class="container">
            <p><?php echo UsniAdaptor::t('cart', "Payment methods are not enabled. Please contact admin to enable payment methods.");?></p>
        </div>
    </div>
<?php return; endif;


$renderCheckoutForm = true;
$renderCompletedOrderContent = false;
$tabActiveClass = ' class="active"';
$controllerId = isset( $this->context->id ) ? $this->context->id : false;
$billingAddressModel = $formDTO->getCheckout()->billingInfoEditForm;

/*
$sidebarTabs = [
    [
        'slug'    => 'shopping-cart',
        'class' => $controllerId && 'checkout' == $controllerId ? 'class="active" ' : '',
        'name'  => UsniAdaptor::t('cart', 'Shopping Cart'),
    ],
    [
        'slug'    => 'wishlist',
        'class' => $controllerId && 'checkout' == $controllerId ? 'class="active" ' : '',
        'name'  => UsniAdaptor::t('wishlist', 'Wishlist'),
    ],
    [
        'slug'    => 'checkout',
        'class' => $controllerId && 'checkout' == $controllerId ? 'class="active" ' : '',
        'name'  => UsniAdaptor::t('cart', 'Checkout'),
    ],
    [
        'slug'    => 'order-complete',
        'class' => 'active',
        'name'  => UsniAdaptor::t('order', 'Order Completion'),
    ],
];
*/?>
<?php /*
                <div class="col-md-2 col-sm-12">
                    <ul class="cart-tab">
                        <?php
                        foreach($sidebarTabs as $i => $tab): ?>
                            <li id="<?= $tab['slug'] ?>-tab">
                                <a href="#<?= $tab['slug'] ?>-content" id="<?= $tab['slug'] ?>-link" <?= $tab['class'] ?>data-toggle="tab">
                                    <span><?= $i + 1 ?></span>
                                    <?= $tab['name'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>*/ ?>
    <!-- SHOP SECTION START -->
    <div class="shop-section mb-80">
        <div class="container">
            <?php
            $form = ActiveForm::begin([
                'id'          => 'checkoutview',
                'caption'     => $this->title,
                'fieldConfig' => [
                    'template' => "{beginLabel}{labelTitle}{endLabel}{beginWrapper}{error}{input}{endWrapper}",
                    //'template' => "{error}{input}",
                    'horizontalCssClasses' => [
                        'label'     => '',
                        'offset'    => '',
                        'wrapper'   => '',
                        'error'     => '',
                        'hint'      => '',
                    ],
                    'errorOptions' => ['encode' => false]
                ],
                //'enableAjaxValidation' => true,
            ]); ?>
            <?= $this->render(
                    '@cart/views/_customeroptions',
                    ['form' => $form, 'formDTO' => $formDTO, 'formInterface' => $formDTO->getInterface()]
            ) ?>
            <hr>
            <?php echo $this->render('@cart/views/_billingeditform', ['form' => $form, 'model' => $billingAddressModel]);?>
            <hr>
            
            <?php echo $this->render('@cart/views/_deliveryoptions-custom', ['form' => $form, 'formDTO' => $formDTO]);?>
                <hr/>
            <?= $this->render('@cart/views/_paymentoptions-custom', ['form' => $form, 'formDTO' => $formDTO]) ?>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <?=
                    \frontend\widgets\FormButtons::widget(
                            [
                                'submitButtonLabel' => UsniAdaptor::t('application', 'Continue'),
                                'submitButtonOptions' => ['class' => 'submit-btn-1 mt-30 btn-hover-1', 'id' => 'save'],
                                'showCancelButton' => false
                            ]
                    );?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>