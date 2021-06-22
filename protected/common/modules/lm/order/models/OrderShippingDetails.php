<?php
namespace common\modules\order\models;

use common\modules\localization\modules\city\models\City;
use common\modules\localization\modules\state\models\State;
use usni\library\db\ActiveRecord;
use usni\UsniAdaptor;
use common\modules\order\utils\OrderUtil;
/**
 * OrderShippingDetails active record.
 * 
 * @package common\modules\Order\models
 */
class OrderShippingDetails extends ActiveRecord 
{
	/**
     * @inheritdoc
     */
	public function rules()
	{
		return [
                    [['order_id', 'name', 'value'], 'required'],
                    [['order_id'], 'number', 'integerOnly' => true],
                    [['name', 'value', 'order_id'],   'safe'],
               ];
	}
	
	/**
     * @inheritdoc
     */
	public function attributeLabels()
	{
		$labels = [
                    'name'     => UsniAdaptor::t('order', 'Property Name'),
                    'value'    => UsniAdaptor::t('users','Property value'),
                    'order_id' => UsniAdaptor::t('order', 'Order'),
                  ];
        return parent::getTranslatedAttributeLabels($labels);
	}
    
    /**
     * @inheritdoc
     */
    public static function getLabel($n = 1)
    {
        return UsniAdaptor::t('order', 'Order Shipping info');
    }
}