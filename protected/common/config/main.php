<?php
use yii\helpers\ArrayHelper;

$imageManagerClass  = 'usni\library\web\ImageManager';
$manufacturerImageManagerClass  = 'common\modules\manufacturer\web\ImageManager';
$productImageManagerClass  = 'products\web\ImageManager';
//$productCategoryImageManagerClass  = 'common\modules\asl_catalog\web\ImageManager'; //@FIXME - should be added dynamically in module (not config dependent)
$fileManagerClass   = 'usni\library\web\FileManager';
$videoManagerClass  = 'usni\library\web\VideoManager';
return ArrayHelper::merge(
                    require(USNI_PATH . '/library/config/common.php'), [
                                            'vendorPath'    => VENDOR_PATH,
                                            'installed'     => $installed,
                                            'name'          => $siteName,
                                            'version'       => '2.0.6',
                                            'language'      => 'lv',
                                            'poweredByName' => 'WhatACart',
                                            'poweredByUrl'  => 'http://whatacart.com',
                                            'environment'   => $environment,
                                            'components' => [
                                                'authorizationManager' => ['class' => 'usni\library\modules\auth\business\AuthManager'],
                                                'cache' => [
                                                                'class'     => 'yii\caching\FileCache',
                                                                'keyPrefix' => 'whatacart', //This is very important as it differntiates application cache
                                                                'cachePath' => APPLICATION_PATH . '/runtime/cache'
                                                           ],
                                                'productWeightManager'      => ['class' => 'products\managers\ProductWeightManager'],
                                                'productDimensionManager'   => ['class' => 'products\managers\ProductDimensionManager'],
                                                'assetManager'      => [
                                                                            'class' => 'common\web\AssetManager',
                                                                            'resourcesPath' => APPLICATION_PATH . '/resources',
                                                                            'fileUploadPath' => APPLICATION_PATH . '/resources/files',
                                                                            'imageUploadPath' => APPLICATION_PATH . '/resources/images',
                                                                            'thumbUploadPath' => APPLICATION_PATH . '/resources/images/thumbs',
                                                                            'manufacturerImageUploadPath' => APPLICATION_PATH . '/resources/images/manufacturer',
                                                                            'manufacturerThumbUploadPath' => APPLICATION_PATH . '/resources/images/manufacturer/thumbs',
                                                                            'productImageUploadPath' => APPLICATION_PATH . '/resources/images/product',
                                                                            'productThumbUploadPath' => APPLICATION_PATH . '/resources/images/product/thumbs',
                                                                            'productCategoryImageUploadPath' => APPLICATION_PATH . '/resources/images/product_category',
                                                                            'productCategoryThumbUploadPath' => APPLICATION_PATH . '/resources/images/product_category/thumbs',
                                                                            'videoUploadPath' => APPLICATION_PATH . '/resources/videos',
                                                                            'imageManagerClass'  => $imageManagerClass,
                                                                            'productImageManagerClass'  => $productImageManagerClass,
                                                                            //'productCategoryImageManagerClass'  => $productCategoryImageManagerClass,
                                                                            'fileManagerClass'   => $fileManagerClass,
                                                                            'videoManagerClass'  => $videoManagerClass
                                                                        ],
                                                'moduleManager'      => ['class' => 'usni\library\components\ModuleManager',
                                                                            'modulePaths' => ['@usni/library/modules', '@common/modules', 
                                                                                       '@backend/modules', '@frontend/modules']],
                                            ],
                                            'as beforeRequest'  => ['class' => 'usni\library\web\BeforeRequestBehavior'],
                                            'as beforeAction'   => ['class' => 'backend\web\BeforeActionBehavior']
                                        ]
                );
