<?php

namespace Vnecoms\GrabPay\Model\ResourceModel\Batch;

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
    protected $_idFieldName = 'entity_id';
    
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Vnecoms\GrabPay\Model\Batch', 'Vnecoms\GrabPay\Model\ResourceModel\Batch');
    }

}