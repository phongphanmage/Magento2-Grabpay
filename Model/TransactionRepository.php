<?php
/**
 * Created by PhpStorm.
 * User: nvhai
 * Date: 12/23/2016
 * Time: 10:54 AM
 */
namespace Vnecoms\GrabPay\Model;

use Vnecoms\GrabPay\Api\Data;
use Vnecoms\GrabPay\Api\TransactionRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Vnecoms\GrabPay\Model\ResourceModel\Transaction as TransactionResource;
use Vnecoms\GrabPay\Model\ResourceModel\Transaction\CollectionFactory as TransactionCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class TablerateRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @var TransactionResource
     */
    protected $resource;

    /**
     * @var TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var TransactionCollectionFactory
     */
    protected $transactionCollectionFactory;

    /**
     * @var Data\TransactionSearchResultsInterface
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Vnecoms\GrabPay\Api\Data\TransactionInterfaceFactory
     */
    protected $dataTransactionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * TransactionRepository constructor.
     * @param TransactionResource $resource
     * @param TransactionFactory $transactionFactory
     * @param Data\TransactionInterfaceFactory $dataTransactionFactory
     * @param TransactionCollectionFactory $transactionCollectionFactory
     * @param Data\TransactionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        TransactionResource $resource,
        TransactionFactory $transactionFactory,
        \Vnecoms\GrabPay\Api\Data\TransactionInterfaceFactory $dataTransactionFactory,
        TransactionCollectionFactory $transactionCollectionFactory,
        Data\TransactionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->transactionFactory = $transactionFactory;
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTransactionFactory = $dataTransactionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Data\TransactionInterface $transaction
     * @return mixed
     * @throws CouldNotSaveException
     */
    public function save(Data\TransactionInterface $transaction)
    {
        try {
            $this->resource->save($transaction);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $transaction;
    }

    /**
     * Load Reponse data by given rate Identity
     *
     * @param string $transactionId
     * @return Reponse
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($transactionId)
    {
        $pay = $this->transactionFactory->create();
        $this->resource->load($pay, $transactionId);
        if (!$pay->getId()) {
            throw new NoSuchEntityException(__('Response with id "%1" does not exist.', $transactionId));
        }
        return $pay;
    }

    /**
     * Load Rate data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Vnecoms\GrabPay\Model\ResourceModel\Transaction\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->transactionCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $rate = [];
        /** @var Tablerate $tablerateModel */
        foreach ($collection as $tablerateModel) {
            $rateData = $this->dataTransactionFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $rateData,
                $tablerateModel->getData(),
                'Vnecoms\GrabPay\Api\Data\TransactionInterface'
            );
            $rate[] = $this->dataObjectProcessor->buildOutputDataArray(
                $rateData,
                'Vnecoms\GrabPay\Api\Data\TransactionInterface'
            );
        }
        $searchResults->setItems($rate);
        return $searchResults;
    }

    /**
     * Delete Reponse
     *
     * @param \Vnecoms\GrabPay\Api\Data\TransactionInterface $transaction
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\TransactionInterface $transaction)
    {
        try {
            $this->resource->delete($transaction);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param $transactionId
     * @return bool
     * @throws CouldNotSaveException
     */
    public function deleteById($transactionId)
    {
        try {
            return $this->delete($this->getById($transactionId));
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

    }
}
