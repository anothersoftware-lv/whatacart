<?php
namespace common\modules\payment\dto;

use usni\library\dto\FormDTO;
use common\modules\payment\models\stripe\StripeSetting;
/**
 * StripeFormDTO class file
 *
 * @package common\modules\payment\dto
 */
class StripeFormDTO extends FormDTO
{
    /**
     * @var array 
     */
    private $_transactionType;
    
    /**
     * @var array 
     */
    private $_orderStatusDropdownData;
    
    public function getTransactionType()
    {
        return $this->_transactionType;
    }

    public function getOrderStatusDropdownData()
    {
        return $this->_orderStatusDropdownData;
    }

    public function setTransactionType($transactionType)
    {
        $this->_transactionType = $transactionType;
    }

    public function setOrderStatusDropdownData($orderStatusDropdownData)
    {
        $this->_orderStatusDropdownData = $orderStatusDropdownData;
    }
}
