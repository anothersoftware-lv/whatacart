<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace common\modules\order\models;

use common\modules\localization\modules\city\models\City;
use common\modules\localization\modules\state\models\State;
use usni\library\db\ActiveRecord;
use usni\UsniAdaptor;
use common\modules\order\utils\OrderUtil;
/**
 * OrderAddressDetails active record.
 * 
 * @package common\modules\Order\models
 */
class OrderAddressDetails extends ActiveRecord 
{
	/**
     * @inheritdoc
     */
	public function rules()
	{
		return [
                    [['firstname', 'lastname', 'email', 'address1', 'city', 'postal_code', 'country'], 'required'],
                    [['mobilephone', 'officephone', 'shipping_location_id'], 'number', 'integerOnly' => true],
                    [['email', 'firstname', 'lastname', 'mobilephone', 'officephone', 'address1', 'address2', 'city', 'country', 'postal_code', 'state', 
                      'type', 'order_id', 'shipping_location_id'],   'safe'],
               ];
	}
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = $scenarios['update'] = [
            'email',
            'firstname',
            'lastname',
            'mobilephone',
            'officephone',
            'address1',
            'address2',
            'city',
            'country',
            'postal_code',
            'state',
            'type',
            'order_id',
            'shipping_location_id',
        ];

        return $scenarios;
    }

	/**
     * @inheritdoc
     */
	public function attributeLabels()
	{
		$labels = [
                    'email'        => UsniAdaptor::t('users', 'Email'),
                    'firstname'    => UsniAdaptor::t('users','First Name'),
                    'lastname'     => UsniAdaptor::t('users','Last Name'),
                    'mobilephone'  => UsniAdaptor::t('users','Mobile'),
                    'officephone'  => UsniAdaptor::t('users','Office Phone'),
                    'address1'     => UsniAdaptor::t('users', 'Address1'),
                    'address2'     => UsniAdaptor::t('users', 'Address2'),
                    'city'         => UsniAdaptor::t('city', 'City'),
                    'state'        => UsniAdaptor::t('state', 'State'),
                    'country'      => UsniAdaptor::t('country', 'Country'),
                    'postal_code'  => UsniAdaptor::t('users', 'Postal Code'),
                    'type'         => UsniAdaptor::t('application', 'Type'),
                    'order_id'     => UsniAdaptor::t('order', 'Order'),
                  ];
        return parent::getTranslatedAttributeLabels($labels);
	}
    
    /**
     * @inheritdoc
     */
    public static function getLabel($n = 1)
    {
        return UsniAdaptor::t('order', 'Order Address');
    }

    public function getCityModel()
    {
        return $this->hasOne(City::class, ['id' => 'city']);
    }

    public function getStateModel()
    {
        return $this->hasOne(State::class, ['id' => 'state']);
    }

    /**
     * Get concatenated displayed address
     * @return string
     */
    public function getConcatenatedDisplayedAddress()
    {
        return OrderUtil::getConcatenatedAddress($this);
    }
}