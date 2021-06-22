<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace cart\models;

use yii\base\Model;
use usni\UsniAdaptor;
use usni\library\validators\EmailValidator;
use usni\library\modules\users\models\Address;
use usni\library\modules\users\models\Person;
use usni\library\utils\ArrayUtil;
use usni\library\modules\users\utils\UserUtil;
use common\modules\order\models\OrderAddressDetails;
/**
 * BillingInfoEditForm class file
 * 
 * @package cart\models
 */
class BillingInfoEditFormCustom extends BillingInfoEditForm
{
    public $agree_to_newsletter;
    public $agree_to_terms;
    
    const SCENARIO_SUBMIT = 'submit';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = array_merge(
            $this->baseRules(),
            [
                [['firstname', 'lastname', 'mobilephone', 'email', 'agree_to_newsletter'], 'required'],
                //Address rules
                [['address1', 'state', 'country', 'postal_code'], 'required'],
                [['agree_to_newsletter'],          'string', 'max' => 10],
                [['agree_to_terms'],         'required', 'isEmpty' => [$this, 'checkAgree'],
                    'requiredValue' => "1",
                    'message' => UsniAdaptor::t(
                        'cart',
                        'Customer should agree to terms and conditions for the purchase'
                    )
                ],
            ]
        );
        
        return $rules;
    }
    
    public function baseRules()
    {
        $rules = [
            //Person rules
            [['firstname', 'lastname'],         'string', 'max' => 32],
            ['email',                           EmailValidator::class],
            ['mobilephone',                     'number'],
            //Address rules
            [['address1', 'address2'],          'string', 'max' => 128],
            [['city', 'state', 'country', 'postal_code'],      'string', 'max' => 64],
            [['firstname', 'lastname', 'email', 'officephone', 'mobilephone', 'address1', 'address2', 'city', 'state', 'country', 'postal_code'],  'safe'],
        ];
        
        return $rules;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SUBMIT] = [
            'firstname', 'lastname', 'email', 'officephone', 'mobilephone', 'address1', 'address2', 'city', 'state', 'country', 'postal_code',
        ];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'agree_to_terms' => 'Piekrītu ',
            'agree_to_newsletter' => 'Saņemt jaunumus un labākos piedāvājumus: '
        ];

        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * @inheritdoc
     */
    public static function getLabel($n = 1)
    {
        return UsniAdaptor::t('cart', 'Billing Details');
    }
    
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        $person     = new Person();
        $address    = new Address();
        return ArrayUtil::merge($person->attributeHints(), $address->attributeHints());
    }

    /**
     * Get concatenated address
     * @return string
     */
    public function getConcatenatedAddress()
    {
        $orderAddressDetails = new OrderAddressDetails();
        $orderAddressDetails->attributes = $this->getAttributes();
        return $orderAddressDetails->getConcatenatedDisplayedAddress();
    }
}