<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\GrabPay\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for template search results.
 * @api
 */
interface TransactionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Reponses list.
     *
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface[]
     */
    public function getItems();

    /**
     * Set Reponses list.
     *
     * @param \Vnecoms\GrabPay\Api\Data\TransactionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
