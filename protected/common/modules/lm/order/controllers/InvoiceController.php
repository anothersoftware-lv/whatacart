<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace common\modules\order\controllers;

use common\modules\order\models\Invoice;
use usni\UsniAdaptor;
use yii\filters\AccessControl;
use common\modules\order\business\InvoiceManagerCustom as InvoiceManager;
use common\modules\order\dto\InvoiceDetailViewDTO;
use yii\web\ForbiddenHttpException;
/**
 * InvoiceController class file
 * 
 * @package common\modules\order\controllers
 */
class InvoiceController extends \usni\library\web\Controller
{
    /**
     * inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['order.manage'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['pay'],
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
    
    /**
     * View invoice
     * @param type $id
     */
    public function actionView($id)
    {
        //Remove some assets
        /*UsniAdaptor::app()->assetManager->bundles['usni\library\web\AdminAssetBundle']['css'] = [];
        UsniAdaptor::app()->assetManager->bundles['usni\library\web\AdminAssetBundle']['js'] = [];
        $this->getView()->sidenavView   = null;
        $this->getView()->headerView    = null;
        $this->getView()->footerView    = null;*/
    
        $detailViewDTO = InvoiceManager::getInstance()->getInvoiceViewDTO($id);
    
        echo $this->render('/invoice/view', ['detailViewDTO' => $detailViewDTO]);
    }
}