<?php
namespace common\modules\payment\controllers\stripe;

use usni\UsniAdaptor;
use common\modules\payment\business\stripe\Manager;
use common\utils\ApplicationUtil;
use usni\library\utils\FlashUtil;
/**
 * SiteController class file
 *
 * @package 
 */
class SiteController extends \usni\library\web\Controller
{
    public $manager;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;    
    }
    
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
            $this->manager = Manager::getInstance();
            return true;
        }
        return false;
    }
    
    /**
     * Send action for the controller.
     * 
     * @return string
     */
    public function actionCharge() 
    {
        $returnStatus = $this->manager->processCharge($_POST);
        if($returnStatus == 'success')
        {
            $checkout       = ApplicationUtil::getCheckout();
            $order          = $checkout->order;
            return $this->redirect(UsniAdaptor::createUrl('cart/checkout/complete-order', ['orderId' => $order['id']]));
        }
        FlashUtil::setMessage('error', $returnStatus); 
        return $this->redirect(UsniAdaptor::createUrl('cart/checkout/review-order'));
    }
}