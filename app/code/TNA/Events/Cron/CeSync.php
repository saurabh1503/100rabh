<?php

namespace TNA\Events\Cron;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use TNA\Events\Api\ContinuingEducationRepositoryInterface;
use TNA\Events\Api\Data\ContinuingEducationInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DB\Adapter\AdapterInterface;

class CeSync
{
    protected $logger;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        ProductInterfaceFactory $ProductInterfaceFactory,
        ProductRepositoryInterface $ProductRepositoryInterface,
        \Magento\Framework\App\Filesystem\DirectoryList $DirectoryList,
        ContinuingEducationRepositoryInterface $ContinuingEducationRepositoryInterface,
        ContinuingEducationInterfaceFactory $ContinuingEducationInterfaceFactory,
        SearchCriteriaBuilder $SearchCriteriaBuilder,
        AdapterInterface $AdapterInterface
    ) {
        $this->logger = $logger;
        $this->productInterfaceFactory = $ProductInterfaceFactory;
        $this->productRepositoryInterface = $ProductRepositoryInterface;
        $this->directoryList = $DirectoryList;
        $this->continuingEducationRepository = $ContinuingEducationRepositoryInterface;
        $this->continuingEducationFactory = $ContinuingEducationInterfaceFactory;
        $this->searchCriteriaBuilder = $SearchCriteriaBuilder;
        $this->adapterInterface = $AdapterInterface;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        return;
        include($this->directoryList->getPath('app')."/code/TNA/Events/.env.php");
        $conn = oci_connect(getenv("ORA_USER"), getenv("ORA_PW"), getenv("ORA_CS"));
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $eventSql = file_get_contents($this->directoryList->getPath('app')."/code/TNA/Events/sql/ceApproval.sql");
        $stid = oci_parse($conn, $eventSql);

        oci_execute($stid);
        $i = 1;
        while ($event = oci_fetch_object($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if ($i %10 == 0) {
                $this->logger->addInfo("[TNA_Events][CeSync] " . $i);
            };
            $this->updateCe($event);
            $i += 1;
        }
        oci_free_statement($stid);
        oci_close($conn);

        $this->logger->addInfo("[TNA_Events][CeSync] " . "Cronjob CeSync is executed.");
    }

    private function updateCe($ce)
    {
        $continuingEducation = $this->continuingEducationFactory->create();
        $continuingEducation->setContinuingEducationId($ce->CEID);
        $continuingEducation->setEventCode($ce->EVENT_CODE);
        $continuingEducation->setStateCode($ce->STATE_ABBREVIATION);
        $continuingEducation->setLicenseType($ce->LICENSE_TYPE);
        $continuingEducation->setLicenseLabel($ce->LICENSE_LABEL);
        $continuingEducation->setCreditHours($ce->CE_HOURS);
        $continuingEducation->setGoodStanding($ce->GOOD_STANDING);

        try {
            $this->continuingEducationRepository->delete($continuingEducation);
            $this->continuingEducationRepository->save($continuingEducation);
            $this->logger->addInfo("[TNA_Events][CeSync] " . "Saved CE " . $ce->EVENT_CODE);
        } catch (Exception $e) {
            $this->logger->addInfo($ce->EVENT_CODE . $e->getMessage());
        }

        return;
    }
}
