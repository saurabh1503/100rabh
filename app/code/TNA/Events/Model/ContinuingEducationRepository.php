<?php


namespace TNA\Events\Model;

use Magento\Framework\Reflection\DataObjectProcessor;
use TNA\Events\Model\ResourceModel\ContinuingEducation\CollectionFactory as ContinuingEducationCollectionFactory;
use TNA\Events\Api\Data\ContinuingEducationSearchResultsInterfaceFactory;
use TNA\Events\Api\Data\ContinuingEducationInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use TNA\Events\Api\ContinuingEducationRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\DataObjectHelper;
use TNA\Events\Model\ResourceModel\ContinuingEducation as ResourceContinuingEducation;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;

class ContinuingEducationRepository implements ContinuingEducationRepositoryInterface
{

    protected $continuing_educationCollectionFactory;

    protected $dataObjectHelper;

    protected $dataContinuingEducationFactory;

    protected $searchResultsFactory;

    private $storeManager;

    protected $continuing_educationFactory;

    protected $resource;

    protected $dataObjectProcessor;


    /**
     * @param ResourceContinuingEducation $resource
     * @param ContinuingEducationFactory $continuingEducationFactory
     * @param ContinuingEducationInterfaceFactory $dataContinuingEducationFactory
     * @param ContinuingEducationCollectionFactory $continuingEducationCollectionFactory
     * @param ContinuingEducationSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceContinuingEducation $resource,
        ContinuingEducationFactory $continuingEducationFactory,
        ContinuingEducationInterfaceFactory $dataContinuingEducationFactory,
        ContinuingEducationCollectionFactory $continuingEducationCollectionFactory,
        ContinuingEducationSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->continuingEducationFactory = $continuingEducationFactory;
        $this->continuingEducationCollectionFactory = $continuingEducationCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataContinuingEducationFactory = $dataContinuingEducationFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \TNA\Events\Api\Data\ContinuingEducationInterface $continuingEducation
    ) {
        /* if (empty($continuingEducation->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $continuingEducation->setStoreId($storeId);
        } */
        try {
            $this->resource->save($continuingEducation);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the continuingEducation: %1',
                $exception->getMessage()
            ));
        }
        return $continuingEducation;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($continuingEducationId)
    {
        $continuingEducation = $this->continuingEducationFactory->create();
        $continuingEducation->load($continuingEducationId);
        if (!$continuingEducation->getId()) {
            throw new NoSuchEntityException(__('continuing_education with id "%1" does not exist.', $continuingEducationId));
        }
        return $continuingEducation;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->continuingEducationCollectionFactory->create();
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

        foreach ($collection as $continuingEducationModel) {
            $continuingEducationData = $this->dataContinuingEducationFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $continuingEducationData,
                $continuingEducationModel->getData(),
                'TNA\Events\Api\Data\ContinuingEducationInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $continuingEducationData,
                'TNA\Events\Api\Data\ContinuingEducationInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \TNA\Events\Api\Data\ContinuingEducationInterface $continuingEducation
    ) {
        try {
            $this->resource->delete($continuingEducation);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the continuing_education: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($continuingEducationId)
    {
        return $this->delete($this->getById($continuingEducationId));
    }
}
