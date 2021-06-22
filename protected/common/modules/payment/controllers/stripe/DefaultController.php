<?php

namespace common\modules\payment\controllers\stripe;

use yii\filters\AccessControl;
use common\modules\payment\business\stripe\Manager;
use usni\UsniAdaptor;
/**
 * DefaultController class file
 *
 * @package common\modules\payment\controllers\stripe
 */
class DefaultController extends \usni\library\web\Controller
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
                        'actions' => ['change-status'],
                        'roles' => ['extension.update'],
                    ]
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }
    
    /**
     * Change status.
     * @param int $id
     * @param int $status
     * @return void
     */
    public function actionChangeStatus($id, $status)
    {
        $result     = Manager::getInstance()->processChangeStatus($id, $status);
        if($result == false)
        {
            throw new NotFoundHttpException();
        }
        return $this->redirect(UsniAdaptor::createUrl('payment/default/index'));
    }
}
