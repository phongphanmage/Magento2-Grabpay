<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\GrabPay\Api\Data;

/**
 * RMA Reponse interface.
 * @api
 */
interface TransactionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const PAY_ID      = 'pay_id';
    const PARTNER_TXN_ID         = 'partner_txn_id';
    const TXN_ID       = 'txn_id';
    const ORDER_ID       = 'order_id';
    const STATE       = 'state';
    const STATUS       = 'status';
    const NOTE       = 'note';
    const CREATED       = 'created';

    /**
     *
     * @return int|null
     */
    public function getPayId();


    /**
     *
     * @return string|null
     */
    public function getPartnerTxnId();

    /**
     *
     * @return string|null
     */
    public function getTxnId();

    /**
     *
     * @return string|null
     */
    public function getOrderId();

    /**
     *
     * @return string|null
     */
    public function getState();

    /**
     *
     * @return string|null
     */
    public function getStatus();

    /**
     *
     * @return string|null
     */
    public function getNote();


    /**
     *
     * @return string|null
     */
    public function getCreated();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setPayId($id);

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setPartnerTxnId($text);

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setTxnId($text);

    /**
     *
     * @param string $text
     * @return $this
     */
    public function setOrderId($text);

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setState($text);

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setStatus($text);

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setNote($text);

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setCreated($text);


}
