<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\GrabPay\Model\CronJob;

/**
 * Class that provides functionality of cleaning expired quotes by cron
 */
class Export
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Vnecoms\GrabPay\Model\Batch\File
     */
    protected $fileProcess;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * Export constructor.
     * @param \Vnecoms\GrabPay\Model\Batch\File $fileProcess
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        \Vnecoms\GrabPay\Model\Batch\File $fileProcess,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\State $state
    ) {
        $this->logger = $logger;
        $this->fileProcess = $fileProcess;
        $this->state = $state;
    }

    /**
     * Clean expired quotes (cron process)
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->fileProcess->processGrabFile();
        }catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
