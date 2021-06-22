<?php
namespace common\modules\payment\controllers\stripe;

use usni\UsniAdaptor;
use usni\library\utils\FlashUtil;
use common\modules\payment\dto\StripeFormDTO;
use common\modules\payment\business\stripe\Manager;
use usni\library\utils\ArrayUtil;
use yii\filters\AccessControl;
/**
 * SettingsController class file
 *
 * @package common\modules\payment\controllers\stripe
 */
class SettingsController extends \usni\library\web\Controller
{
    /**
     * inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['extension.manage'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $formDTO    = new StripeFormDTO();
        $postData   = ArrayUtil::getValue($_POST, ['StripeSetting']);
        $formDTO->setPostData($postData);
        Manager::getInstance()->processSettings($formDTO);
        if($formDTO->getIsTransactionSuccess() === true)
        {
            FlashUtil::setMessage('success', UsniAdaptor::t('paymentflash', 'Settings are saved successfully.'));
            return $this->refresh();
        }
        else
        {
            return $this->render('/stripe/settings', ['formDTO' => $formDTO]);
        }
    }
}