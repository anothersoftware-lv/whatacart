<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
use yii\helpers\ArrayHelper;

$config  = array(
                    'id'                => 'whatacart-cpanel',
                    'basePath'          => dirname(__DIR__),
                    'controllerNamespace' => 'backend\controllers',
                    'defaultRoute'        => 'home/default/index', //Example 'home/default/index'
                    'components'        => array(
                        'user'       => ['loginUrl' => ['/users/default/login'],
                                         'identityCookie' => [
                                                                'name' => '_backendUser', // unique for backend
                                                             ]                                                        ],
                        'urlManager' => [
                                            'enablePrettyUrl' => true,
                                            'showScriptName'  => true,
                                        ],
                        'view'              => [
                                                    'class' => 'usni\library\web\AdminView',
                                                    'headerView' => '@app/views/site/_topnav.php',
                                                    'footerView' => '@app/views/site/_footer.php',
                                                    'sidenavView'=> '@app/views/site/_sidenav.php'
                                                ],
                        'languageManager'   => ['class' => 'usni\library\components\LanguageManager'],
                        'currencyManager'   => [
                            'class' => 'common\modules\localization\modules\currency\components\CurrencyManager',                             
                            'defaultCurrencyCode' => 'EUR',
                        ],
                        'storeManager'      => ['class' => 'common\modules\stores\components\StoreManager'],
                        'session'           => [
                                                    'name' => 'PHPBACKSESSID',
                                                    'class' => 'yii\web\DbSession',
                                                    'sessionTable' => 'tbl_session_backend',
                                               ],
                        'cookieManager'     => [
                                                    'class' => 'common\web\CookieManager',
                                                    'contentLanguageCookieName' => 'whatacartAdminContentLanguage',
                                                    'applicationStoreCookieName' => 'whatacartAdminStore',
                                                    'applicationCurrencyCookieName' => 'whatacartAdminCurrency'
                                                ],
                        'errorHandler'      => ['errorAction' => 'home/default/error'],
                        'consoleRunner' => [
                            'class' => 'vova07\console\ConsoleRunner',
                            'file' => '@app/yii' // or an absolute path to console file
                        ],
                    ),
                    'modules' => [
                        'gridview' => [
                            'class' => '\kartik\grid\Module'
                            // enter optional module parameters below - only if you need to  
                            // use your own export download action or custom translation 
                            // message source
                            // 'downloadAction' => 'gridview/export/download',
                            // 'i18n' => []
                        ]
                    ]
                );
$instanceConfigFile = APPLICATION_PATH . '/protected/common/config/instanceConfig.php'; 
if(file_exists($instanceConfigFile))
{
    $config = ArrayHelper::merge($config, require($instanceConfigFile));
}
return $config;