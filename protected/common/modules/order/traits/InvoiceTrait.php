<?php
/**
 * @copyright Copyright (C) 2016 Usha Singhai Neo Informatique Pvt. Ltd
 * @license http://www.gnu.org/licenses/gpl.html
 */
namespace common\modules\order\traits;

use common\modules\order\dao\OrderDAO;
use common\modules\order\models\Invoice;

/**
 * Implement common functions related to invoice
 *
 * @package common\modules\order\traits
 */
trait InvoiceTrait
{
    /**
     * @param $invoiceId
     * @return \common\modules\order\dao\Invoice
     */
    public function getInvoice($invoiceId)
    {
        $invoice = OrderDAO::getInvoice($invoiceId, $this->language);
        
        return $invoice;    
    }
}
