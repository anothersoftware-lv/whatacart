<?php
namespace common\utils;

use common\modules\asl_address\dao\CityDAO;
use common\modules\asl_address\dao\StateDAO;
use frontend\web\View;
use usni\UsniAdaptor;
use yii\helpers\ArrayHelper;

class AddressUtil
{
    public static function getStatesDropdownArray($language = false)
    {
        if(!$language) {
            $language = UsniAdaptor::app()->languageManager->selectedLanguage;
        }

        $states = ArrayHelper::map( StateDAO::getAll( $language ), 'id', 'name');
        
        return $states;
    }
    
    public static function getCitiesDropdownArray($language = false)
    {
        if(!$language) {
            $language = UsniAdaptor::app()->languageManager->selectedLanguage;
        }

        $cities = ArrayHelper::map( CityDAO::getAll( $language ), 'id', 'name');
        
        return $cities;
    }
    
    public static function getStateCitiesArray($stateId, $language = false)
    {
        if(!$language) {
            $language = UsniAdaptor::app()->languageManager->selectedLanguage;
        }

        $cities = CityDAO::getAllByStateId( $stateId, $language );
        
        return $cities;
    }

    public static function getStateCitiesDropdownArray($stateId, $language = false)
    {
        $cities = self::getStateCitiesArray($stateId, $language);
        
        $cities = ArrayHelper::map( $cities, 'id', 'name');
        
        return $cities;
    }

    /*public static function getStateCitiesDropdownOptions($stateId, $language)
    {
        $cities = CityDAO::getAllByStateId($stateId, $language );
        
        $options = '';
        
        foreach($cities as $city) {
            $options .= '';
        }
        
        return $options;
    }*/

    public static function registerStateScripts()
    {
        $homeUrl = UsniAdaptor::app()->getFrontUrl();

        $vars = [
            'apiStateCitiesRoute' => $homeUrl .'/api/localization/state/cities',
            'apiShippingCompanyStateCitiesRoute' => $homeUrl .'/api/shipping/state/cities',
            'apiShippingCompanyStatePointsRoute' => $homeUrl .'/api/shipping/state/points',
            'cityDropdownPromtMessage' => UsniAdaptor::t('city', 'Select a city'),
        ];
        
        $view = UsniAdaptor::app()->getView();
        
        $view->registerJs(
            "var jsVars = ".\yii\helpers\Json::htmlEncode($vars).";",
            \frontend\web\View::POS_HEAD,
            'jsVars'
        );
    }
}