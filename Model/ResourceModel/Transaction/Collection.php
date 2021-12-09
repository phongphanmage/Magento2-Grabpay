<?php

namespace Vnecoms\GrabPay\Model\ResourceModel\Transaction;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Contact Resource Model Collection
 *
 * @author      Pierre FAY
 */
class Collection extends AbstractCollection
{
    /**
     * @var string profiler_id
     */
    protected $_idFieldName = 'pay_id';
    
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Vnecoms\GrabPay\Model\Transaction', 'Vnecoms\GrabPay\Model\ResourceModel\Transaction');
    }

}