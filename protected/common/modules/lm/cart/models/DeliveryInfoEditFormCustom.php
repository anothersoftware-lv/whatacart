<?php
namespace cart\models;

use usni\UsniAdaptor;

/**
 * DeliveryInfoEditFormCustom class file
 * @package cart\models
 */
class DeliveryInfoEditFormCustom extends BillingInfoEditFormCustom
{
    public $shippingDetails = [];
    public $addressRequired;
    
    /**
     * Same as billing address
     * @var boolean
     */
    public $sameAsBillingAddress;

    public function rules()
    {
        $baseRules = parent::baseRules();

        $rules = array_merge(
            $baseRules,
            [
                [['firstname', 'lastname', 'mobilephone', 'email'], 'required'],
                ['sameAsBillingAddress', 'boolean'],
                //Address rules
                [
                    ['address1', 'state', 'country', 'postal_code'],
                    'required',
                    'when' => [$this, 'isAddressRequired'],
                    'whenClient' => 'checkoutIsToAddressSelected',
                    'on' => self::SCENARIO_DEFAULT,
                ],
            ]
        );
        return $rules;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SUBMIT] = [
            'firstname',
            'lastname',
            'email',
            'officephone',
            'mobilephone',
            'address1',
            'address2',
            'city',
            'state',
            'country',
            'postal_code',
        ];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'sameAsBillingAddress' => UsniAdaptor::t('users', 'Same As Billing Address'),
        ];

        return array_merge($labels, parent::attributeLabels());
    }

    function isAddressRequired()
    {
        return $this->addressRequired;
    }
}
