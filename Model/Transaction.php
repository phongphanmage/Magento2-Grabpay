<?php

namespace Vnecoms\GrabPay\Model;

use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;
use Vnecoms\GrabPay\Api\Data\TransactionInterface;

/**
 * Contact Model
 *
 * @author      Pierre FAY
 */
class Transaction extends AbstractModel implements TransactionInterface
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Vnecoms\GrabPay\Model\ResourceModel\Transaction::class);
    }

    /**
     *
     * @return int|null
     */
    public function getPayId() {
        return $this->getData(self::PAY_ID);
    }


    /**
     *
     * @return string|null
     */
    public function getPartnerTxnId() {
        return $this->getData(self::PARTNER_TXN_ID);
    }

    /**
     *
     * @return string|null
     */
    public function getTxnId() {
        return $this->getData(self::TXN_ID);
    }

    /**
     *
     * @return string|null
     */
    public function getOrderId() {
        return $this->getData(self::ORDER_ID);
    }

    /**
     *
     * @return string|null
     */
    public function getState() {
        return $this->getData(self::STATE);
    }

    /**
     *
     * @return string|null
     */
    public function getStatus() {
        return $this->getData(self::STATUS);
    }

    /**
     *
     * @return string|null
     */
    public function getNote() {
        return $this->getData(self::NOTE);
    }


    /**
     *
     * @return string|null
     */
    public function getCreated() {
        return $this->getData(self::CREATED);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setPayId($id) {
        return $this->setData(self::PAY_ID, $id);
    }

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setPartnerTxnId($text) {
        return $this->setData(self::PARTNER_TXN_ID, $text);
    }

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setTxnId($text) {
        return $this->setData(self::TXN_ID, $text);
    }

    /**
     *
     * @param string $text
     * @return $this
     */
    public function setOrderId($text) {
        return $this->setData(self::ORDER_ID, $text);
    }

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setState($text) {
        return $this->setData(self::STATE, $text);
    }

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setStatus($text) {
        return $this->setData(self::STATUS, $text);
    }

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setNote($text) {
        return $this->setData(self::NOTE, $text);
    }

    /**
     *
     * @param string $text
     * @return \Vnecoms\GrabPay\Api\Data\TransactionInterface
     */
    public function setCreated($text) {
        return $this->setData(self::CREATED, $text);
    }
}