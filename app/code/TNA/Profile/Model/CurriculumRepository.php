<?php


namespace TNA\Profile\Model;

use TNA\Profile\Api\CurriculumRepositoryInterface;
use TNA\Profile\Api\Data\CurriculumSearchResultsInterfaceFactory;
use TNA\Profile\Api\Data\CurriculumInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use TNA\Profile\Model\ResourceModel\Curriculum as ResourceCurriculum;
use TNA\Profile\Model\ResourceModel\Curriculum\CollectionFactory as CurriculumCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class CurriculumRepository implements CurriculumRepositoryInterface
{

    protected $resource;

    protected $CurriculumFactory;

    protected $CurriculumCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataCurriculumFactory;

    private $storeManager;


    /**
     * @param ResourceCurriculum $resource
     * @param CurriculumFactory $curriculumFactory
     * @param CurriculumInterfaceFactory $dataCurriculumFactory
     * @param CurriculumCollectionFactory $curriculumCollectionFactory
     * @param CurriculumSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceCurriculum $resource,
        CurriculumFactory $curriculumFactory,
        CurriculumInterfaceFactory $dataCurriculumFactory,
        CurriculumCollectionFactory $curriculumCollectionFactory,
        CurriculumSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->curriculumFactory = $curriculumFactory;
        $this->curriculumCollectionFactory = $curriculumCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCurriculumFactory = $dataCurriculumFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \TNA\Profile\Api\Data\CurriculumInterface $curriculum
    ) {
        /* if (empty($curriculum->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $curriculum->setStoreId($storeId);
        } */
        try {
            $this->resource->save($curriculum);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the curriculum: %1',
                $exception->getMessage()
            ));
        }
        return $curriculum;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($curriculumId)
    {
        $curriculum = $this->curriculumFactory->create();
        $curriculum->load($curriculumId);
        if (!$curriculum->getId()) {
            throw new NoSuchEntityException(__('Curriculum with id "%1" does not exist.', $curriculumId));
        }
        return $curriculum;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->curriculumCollectionFactory->create();
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
        
        foreach ($collection as $curriculumModel) {
            $curriculumData = $this->dataCurriculumFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $curriculumData,
                $curriculumModel->getData(),
                'TNA\Profile\Api\Data\CurriculumInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $curriculumData,
                'TNA\Profile\Api\Data\CurriculumInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \TNA\Profile\Api\Data\CurriculumInterface $curriculum
    ) {
        try {
            $this->resource->delete($curriculum);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Curriculum: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($curriculumId)
    {
        return $this->delete($this->getById($curriculumId));
    }
}
