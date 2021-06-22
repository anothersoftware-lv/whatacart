<?php
namespace common\modules\payment\controllers\stripe;

use common\modules\payment\business\stripe\Manager;
use usni\UsniAdaptor;
/**
 * ConfirmController class file
 *
 * @package common\modules\payment\controllers\stripe
 */
class ConfirmController extends \usni\library\web\Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if(parent::beforeAction($action))
        {
            //Registration translations
            UsniAdaptor::app()->i18n->translations['stripe*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/messages'
            ];
            UsniAdaptor::app()->i18n->translations['stripehint*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@app/messages'
            ];
            //Register log targets
            $targets   = UsniAdaptor::app()->log->targets;
            $targets[] = \Yii::createObject([
                        'class' => 'yii\log\FileTarget',
                        'logFile' => '@runtime/logs/stripe.log',
                        'levels' => ['error', 'warning', 'info'],
                        'logVars' => ['_GET', '_POST', '_SESSION'],
                        'categories' => ['stripe'],
                    ]);
            UsniAdaptor::app()->log->targets = $targets;
            return true;
        }
        return false;
    }
    
    /**
     * Index action for confirm
     * @return string
     */
    public function actionIndex()
    {
        $config                 = Manager::getInstance()->getStripeConfig();
        return $this->renderPartial('/stripe/confirmorder', ['config' => $config]);
    }
}
