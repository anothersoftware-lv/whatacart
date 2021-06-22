<?php
namespace common\utils;

use common\modules\stores\dao\StoreDAO;
use common\modules\stores\models\StoreConfiguration;
use common\modules\stores\utils\StoreUtil;
use usni\UsniAdaptor;
use common\utils\ApplicationUtil;
use cart\models\Cart;
use yii\base\Model;
use products\models\CompareProducts;
use cart\models\CheckoutCustom as AdminCheckout;
use wishlist\models\Wishlist;
/**
 * ExternalApiUtil class file.
 *
 * @package common\utils
 */
class ExternalApiUtil
{
    public static function getGoogleMapsJavaScriptAPIKey()
    {
        $key =  UsniAdaptor::app()->storeManager->getStoreValueByKey('google_maps_javascript', 'api_key', 'storeconfig');

        return $key;
    }
}