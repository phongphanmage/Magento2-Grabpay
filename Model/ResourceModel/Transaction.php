<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\GrabPay\Model\ResourceModel;

/**
 * Tax class resource
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Transaction extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('ves_grabpay_transaction', 'pay_id');
    }
}
