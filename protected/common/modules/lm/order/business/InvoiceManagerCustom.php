<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://www.gnu.org/licenses/gpl.html
 */
namespace common\modules\order\business;

use common\modules\order\models\Invoice;
use common\modules\order\models\Order;
use common\modules\order\dao\OrderDAO;
use common\modules\order\widgets\InvoiceView;
use frontend\web\View;
use yii\base\InvalidParamException;
use usni\library\modules\users\models\Address;
use usni\UsniAdaptor;
use common\modules\order\utils\OrderUtil;
use common\modules\order\dto\InvoiceDetailViewDTO;
use common\modules\order\business\Manager as OrderBusinessManager;
use common\modules\order\behaviors\InvoiceManagerBehavior;
use usni\library\utils\ArrayUtil;
use common\modules\order\business\InvoiceManager;
use yii\web\ForbiddenHttpException;

/**
 * Implements business logic for invoice
 *
 * @package common\modules\order\business
 */
class InvoiceManagerCustom extends InvoiceManager
{
    public function getInvoiceViewDTO($id)
    {
        $detailViewDTO      = new InvoiceDetailViewDTO();
        $detailViewDTO->setId($id);
        $detailViewDTO->setModelClass(Invoice::className());
        $result     = InvoiceManager::getInstance()->processDetail($detailViewDTO);
        if($result === false)
        {
            throw new ForbiddenHttpException(\Yii::t('yii','You are not authorized to perform this action.'));
        }

        return $detailViewDTO;
    }
}