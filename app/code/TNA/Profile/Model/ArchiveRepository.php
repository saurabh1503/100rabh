<?php


namespace TNA\Profile\Model;

use TNA\Profile\Api\ArchiveRepositoryInterface;
use TNA\Profile\Api\Data\ArchiveSearchResultsInterfaceFactory;
use TNA\Profile\Api\Data\ArchiveInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use TNA\Profile\Model\ResourceModel\Archive as ResourceArchive;
use TNA\Profile\Model\ResourceModel\Archive\CollectionFactory as ArchiveCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class ArchiveRepository implements ArchiveRepositoryInterface
{

    protected $resource;

    protected $ArchiveFactory;

    protected $ArchiveCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataArchiveFactory;

    private $storeManager;


    /**
     * @param ResourceArchive $resource
     * @param ArchiveFactory $archiveFactory
     * @param ArchiveInterfaceFactory $dataArchiveFactory
     * @param ArchiveCollectionFactory $archiveCollectionFactory
     * @param ArchiveSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceArchive $resource,
        ArchiveFactory $archiveFactory,
        ArchiveInterfaceFactory $dataArchiveFactory,
        ArchiveCollectionFactory $archiveCollectionFactory,
        ArchiveSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->archiveFactory = $archiveFactory;
        $this->archiveCollectionFactory = $archiveCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataArchiveFactory = $dataArchiveFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \TNA\Profile\Api\Data\ArchiveInterface $archive
    ) {
        /* if (empty($archive->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $archive->setStoreId($storeId);
        } */
        try {
            $this->resource->save($archive);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the archive: %1',
                $exception->getMessage()
            ));
        }
        return $archive;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($archiveId)
    {
        $archive = $this->archiveFactory->create();
        $archive->load($archiveId);
        if (!$archive->getId()) {
            throw new NoSuchEntityException(__('Archive with id "%1" does not exist.', $archiveId));
        }
        return $archive;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->archiveCollectionFactory->create();
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
        
        foreach ($collection as $archiveModel) {
            $archiveData = $this->dataArchiveFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $archiveData,
                $archiveModel->getData(),
                'TNA\Profile\Api\Data\ArchiveInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $archiveData,
                'TNA\Profile\Api\Data\ArchiveInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \TNA\Profile\Api\Data\ArchiveInterface $archive
    ) {
        try {
            $this->resource->delete($archive);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Archive: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($archiveId)
    {
        return $this->delete($this->getById($archiveId));
    }
}
