<?php


namespace TNA\Profile\Model;

use TNA\Profile\Model\ResourceModel\ICE\CollectionFactory as ICECollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use TNA\Profile\Api\ICERepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use TNA\Profile\Model\ResourceModel\ICE as ResourceICE;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use TNA\Profile\Api\Data\ICEInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use TNA\Profile\Api\Data\ICESearchResultsInterfaceFactory;

class ICERepository implements ICERepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $ICECollectionFactory;

    private $storeManager;

    protected $ICEFactory;

    protected $dataICEFactory;

    protected $dataObjectHelper;

    protected $resource;


    /**
     * @param ResourceICE $resource
     * @param ICEFactory $iCEFactory
     * @param ICEInterfaceFactory $dataICEFactory
     * @param ICECollectionFactory $iCECollectionFactory
     * @param ICESearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceICE $resource,
        ICEFactory $iCEFactory,
        ICEInterfaceFactory $dataICEFactory,
        ICECollectionFactory $iCECollectionFactory,
        ICESearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->iCEFactory = $iCEFactory;
        $this->iCECollectionFactory = $iCECollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataICEFactory = $dataICEFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\TNA\Profile\Api\Data\ICEInterface $iCE)
    {
        /* if (empty($iCE->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $iCE->setStoreId($storeId);
        } */
        try {
            $this->resource->save($iCE);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the iCE: %1',
                $exception->getMessage()
            ));
        }
        return $iCE;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($iCEId)
    {
        $iCE = $this->iCEFactory->create();
        $iCE->load($iCEId);
        if (!$iCE->getId()) {
            throw new NoSuchEntityException(__('ICE with id "%1" does not exist.', $iCEId));
        }
        return $iCE;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->iCECollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $items = [];
        
        foreach ($collection as $iCEModel) {
            $iCEData = $this->dataICEFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $iCEData,
                $iCEModel->getData(),
                'TNA\Profile\Api\Data\ICEInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $iCEData,
                'TNA\Profile\Api\Data\ICEInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\TNA\Profile\Api\Data\ICEInterface $iCE)
    {
        try {
            $this->resource->delete($iCE);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ICE: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($iCEId)
    {
        return $this->delete($this->getById($iCEId));
    }
}
