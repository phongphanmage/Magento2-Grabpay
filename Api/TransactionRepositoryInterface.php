<?php
/**
 * Created by PhpStorm.
 * User: nvhai
 * Date: 12/23/2016
 * Time: 10:42 AM
 */
namespace Vnecoms\GrabPay\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * CMS block CRUD interface.
 * @api
 */
interface TransactionRepositoryInterface
{
    /**
     * Save pay transaction.
     * @param \Vnecoms\GrabPay\Api\Data\TransactionInterface $transaction
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\TransactionInterface $transaction);

    /**
     * Retrieve rate.
     *
     * @param int $transactionId
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($transactionId);

    /**
     * Retrieve reason matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vnecoms\GrabPay\Api\Data\TransactionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete rate.
     *
     * @param \Vnecoms\GrabPay\Api\Data\TransactionInterface $transaction
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\TransactionInterface $transaction);

    /**
     * Delete rate by ID.
     * @param int $transactionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($transactionId);
}
