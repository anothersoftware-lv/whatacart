<?php
namespace frontend\dto;

use usni\UsniAdaptor;
use common\utils\ApplicationUtil;
use cart\models\Cart;
use yii\base\Model;
use products\models\CompareProducts;
use cart\models\Checkout;
use wishlist\models\Wishlist;
/**
 * ExternalApiDTO class file.
 *
 * @package common\utils
 */
class ExternalApiDTO
{
    private $_googleMapsJavaScriptAPIKey;

    public function setGoogleMapsJavaScriptAPIKey($key)
    {
        $this->_googleMapsJavaScriptAPIKey = $key;
    }

    public function getGoogleMapsJavaScriptAPIKey()
    {
        return $this->_googleMapsJavaScriptAPIKey;
    }
}