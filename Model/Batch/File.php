<?php

namespace Vnecoms\GrabPay\Model\Batch;

/**
 * Contact Model
 *
 * @author      Pierre FAY
 */
class File
{
    const FOLDER_VAR = "grab_reports";

    const FOLDER_VAR_WORNG_FILE = "invalid_file";

    const REQUIRED_MIN_KEY = 5;

    const REQUIRED_COLUMN = ["Transaction Date", "Transaction Type" , "Transaction Channel", "Payment Method", "Transaction Amount"];

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $_dir;

    /**
     * @var \Vnecoms\GrabPay\Model\BatchFactory
     */
    protected $batchFactory;

    /**
     * @var \Vnecoms\GrabPay\Model\Batch\DetailFactory
     */
    protected $detailFactory;

    /**
     * @var \Vnecoms\GrabPay\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $file;

    /**
     * @var \Vnecoms\GrabPay\Model\OneTime\Pay
     */
    protected $payment;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * File constructor.
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     * @param DetailFactory $detailFactory
     * @param \Vnecoms\GrabPay\Model\BatchFactory $batchFactory
     * @param \Vnecoms\GrabPay\Helper\Email $helperData
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\Io\File $file
     * @param \Vnecoms\GrabPay\Model\OneTime\Pay $payment
     */
    public function __construct(
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Vnecoms\GrabPay\Model\Batch\DetailFactory $detailFactory,
        \Vnecoms\GrabPay\Model\BatchFactory $batchFactory,
        \Vnecoms\GrabPay\Helper\Email $helperData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\Io\File $file,
        \Vnecoms\GrabPay\Model\OneTime\Pay $payment,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_dir = $dir;
        $this->batchFactory = $batchFactory;
        $this->detailFactory = $detailFactory;
        $this->helperData = $helperData;
        $this->_storeManager = $storeManager;
        $this->file = $file;
        $this->payment = $payment;
        $this->logger = $logger;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function loadFileContent() {
        $content = [];
        $var = $this->_dir->getPath('var')."/".self::FOLDER_VAR;
        $files = array_diff(scandir($var), array('.', '..'));

        if ($files) {
            foreach ($files as $file) {
                if ($file == self::FOLDER_VAR_WORNG_FILE) continue;
                try {
                    $fileName = $var."/".$file;
                    $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($fileName);
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
                    $spreadsheet = $reader->load($fileName);
                    $datas = $spreadsheet->getActiveSheet()->toArray();
                    foreach ($datas as $key => $data) {
                        $count = count(array_filter($data));
                        if ($count <= self::REQUIRED_MIN_KEY) {
                            unset($datas[$key]);
                        }
                    }
                    $datas = array_values($datas);
                    $keys = $datas[0];

                    $checkKey = array_intersect(self::REQUIRED_COLUMN, $keys);

                    if (count($checkKey) != count(self::REQUIRED_COLUMN)) {
                        $newPathOriginFile = $this->_dir->getPath('var')."/".self::FOLDER_VAR."/".self::FOLDER_VAR_WORNG_FILE
                        ;
                        if (!file_exists($newPathOriginFile)) {
                            $this->file->mkdir($newPathOriginFile, 0775);
                        }
                        $newPathOriginFile = $newPathOriginFile."/".$file;
                        $originDir = $this->_dir->getPath('var')."/".self::FOLDER_VAR."/".$file;
                        $this->file->mv($originDir, $newPathOriginFile);
                        $this->logger->error($file." wrong format");
                        continue;
                    } else {
                        unset($datas[0]);
                        $newData = [];
                        foreach ($datas as $data) {
                            $tmp = array_combine($keys, $data);
                            $newData[] = $tmp;
                        }
                        $content[$file] = $newData;
                    }
                } catch (\Exception $e) {
                    $newPathOriginFile = $this->_dir->getPath('var')."/".self::FOLDER_VAR."/".self::FOLDER_VAR_WORNG_FILE
                    ;
                    if (!file_exists($newPathOriginFile)) {
                        $this->file->mkdir($newPathOriginFile, 0775);
                    }
                    $newPathOriginFile = $newPathOriginFile."/".$file;
                    $originDir = $this->_dir->getPath('var')."/".self::FOLDER_VAR."/".$file;
                    $this->file->mv($originDir, $newPathOriginFile);
                    $this->logger->error($file." ".$e->getMessage());
                }

            }
        }

        return $content;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function processGrabFile() {
        $contents = $this->loadFileContent();
        $keyFile = date("Ymd");
        $datedir = $this->_dir->getPath('media').'/grab_reports/'.$keyFile;
        if (!file_exists($datedir)) {
            $this->file->mkdir($datedir, 0775);
        }


        $convertFileList = [];
        if ($contents) {
            foreach ($contents as $fileName => $datas) {
                $batchModel = $this->batchFactory->create()->getCollection()
                    ->addFieldToFilter("grapfile_name", $fileName)
                ;
                if ($batchModel->count()) continue;

                $convertData = [];

                foreach ($datas as $data) {
                    $batchDate = $requestDate = null;
                    if (isset($data["Transaction Date"])) {
                        $batchDate = date("m/d/Y", strtotime(trim($data["Transaction Date"])));
                    }
                    if (isset($data["Transaction Time"])) {
                        $requestDate = $batchDate." ".trim($data["Transaction Time"]);;
                    }

                    $payment = isset($data["Payment Method"]) ? $data["Payment Method"] : null;
                    $status = isset($data["Transaction Channel"]) ? $data["Transaction Channel"] : null;
                    $type = isset($data["Transaction Type"]) ? $data["Transaction Type"] : null;

                    $cardType = $this->_formatPaymentMethod($payment, $status, $type);

                    $tmp = [
                        "RequestID" => isset($data["Grab Transaction ID"]) ? $data["Grab Transaction ID"] : null,
                        "RequestDate" => $requestDate,
                        "MerchantId" => "",
                        "BatchID" => $keyFile,
                        "BatchDate" => $batchDate,
                        "Status" => isset($data["Transaction Channel"]) ? $this->_formatStatus($data["Transaction Channel"]) : null,
                        "AuthorizationCode" => "",
                        "BinNumber" => "",
                        "RequestedAmount" => isset($data["Transaction Amount"]) ? (string)$data["Transaction Amount"] : null,
                        "RequestedAmountCurrencyCode" => $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
                        "CardType" => $cardType,
                        "ExpirationMonth" => "",
                        "ExpirationYear" => "",
                        "MerchantReferenceNumber" => isset($data["Partner Ref ID 2"]) ? $data["Partner Ref ID 2"] : null,
                        "TransactionType" => $type,
                        "OriginalRequestId" => isset($data["Original Grab Transaction ID"]) ? $data["Original Grab Transaction ID"] : null,
                    ];
                    $convertData[] = $tmp;
                }

                if ($convertData) {
                    $convertFileName = $this->_createFileConvert($datedir, $fileName, $keyFile, $convertData);
                    if ($convertFileName) {
                        $batchModel = $this->batchFactory->create();
                        $batchModel->setData(
                            [
                                "batch_id" => $keyFile,
                                "grapfile_name" => $fileName,
                                "gcfile_name" => $convertFileName
                            ]
                        )->save();

                        $numberRecords = 0;
                        $numberRecordsSettlementTxn = 0;
                        $numberRecordsCollectTxn = 0;
                        $numberRecordsRefundTxn = 0;
                        $transactionAmt = 0;
                        $transactionAmtSettlement = 0;
                        $transactionAmtCollect = 0;
                        $transactionAmtRefund = 0;

                        foreach ($convertData as $cvData) {
                            $batchModelDefail = $this->detailFactory->create();
                            $batchModelDefail->setData(
                                [
                                    "batch_id" => $batchModel->getId(),
                                    "request_date" => $cvData["RequestDate"],
                                    "batch_date" => $cvData["BatchDate"],
                                    "status" => $cvData["Status"],
                                    "requested_amount" => $cvData["RequestedAmount"],
                                    "requesed_amount_currency_code" => $cvData["RequestedAmountCurrencyCode"],
                                    "card_type" => $cvData["CardType"],
                                    "merchant_reference_number" => $cvData["MerchantReferenceNumber"],
                                    "grab_transaction_id" => $cvData["RequestID"],
                                    "original_grab_transaction_id" => $cvData["OriginalRequestId"],
                                    "transaction_type" => $cvData["TransactionType"],
                                ]
                            )->save();

                            switch ($cvData['TransactionType']) {
                                case "Settlement":
                                    $numberRecordsSettlementTxn++;
                                    $transactionAmtSettlement += $cvData["RequestedAmount"];
                                    break;
                                case "Collect":
                                    $numberRecordsCollectTxn++;
                                    $transactionAmtCollect += $cvData["RequestedAmount"];
                                    break;
                                case "Refund":
                                    $numberRecordsRefundTxn++;
                                    $transactionAmtRefund += $cvData["RequestedAmount"];
                                    break;
                            }
                            $transactionAmt += $cvData["RequestedAmount"];
                            $numberRecords++;
                        }

                        $batchModelUpdate = $this->batchFactory->create()->load($batchModel->getId());
                        $batchModelUpdate->setData(
                            [
                                "entity_id" => $batchModel->getId(),
                                "number_records" => $numberRecords,
                                "number_records_settlement_txn" => $numberRecordsSettlementTxn,
                                "number_records_collect_txn" => $numberRecordsCollectTxn,
                                "number_records_refund_txn" => $numberRecordsRefundTxn,
                                "transaction_amt" => $transactionAmt,
                                "transaction_amt_settlement" => $transactionAmtSettlement,
                                "transaction_amt_collect" => $transactionAmtCollect,
                                "transaction_amt_refund" => $transactionAmtRefund
                            ]
                        )->save();
                        $convertFileList[$convertFileName] = $datedir."/".$convertFileName;
                    }
                }
            }

            if ($convertFileList) {
                //send email to admin with attachment file
                $this->_sendEmailToAdmin($convertFileList);
            }
        }
        return $this;
    }

    /**
     * @param $attachments
     * @return $this
     */
    protected function _sendEmailToAdmin($attachments){
        $templateFile = $this->payment->getConfigValue("export_file_template");
        $emails = $this->payment->getConfigValue("email_to");
        $senderEntity = $this->payment->getConfigValue("sender_email_identity");
        $emails = explode(",", $emails);
        $toEmail = $emails[0];
        unset($emails[0]);

        try {
            //send email to customer
            $this->helperData->sendTransactionEmail(
                $templateFile,
                \Magento\Framework\App\Area::AREA_FRONTEND,
                $senderEntity,
                $toEmail,
                [],
                $attachments,
                $emails
            );
        } catch (\Exception $e) {
            //do something
        }
        return $this;
    }

    /**
     * @param $datedir
     * @param $orginFile
     * @param $keyFile
     * @param $sheetDatas
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function _createFileConvert($datedir, $orginFile, $keyFile, $sheetDatas) {
        $mySpreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $mySpreadsheet->removeSheetByIndex(0);

        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "GC");
        $mySpreadsheet->addSheet($worksheet, 0);

        $keys = array_keys($sheetDatas[0]);
        array_unshift($sheetDatas, $keys);
        $worksheet->fromArray($sheetDatas);
        foreach ($worksheet->getColumnIterator() as $column)
        {
            $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($mySpreadsheet);

        $gcFileName = "GC_Transaction_".time().".xlsx";
        $writer->save($datedir."/".$gcFileName);


        $newPathOriginFile = $datedir."/".$orginFile;
        $originDir = $this->_dir->getPath('var')."/".self::FOLDER_VAR."/".$orginFile;

        $this->file->mv($originDir, $newPathOriginFile);

        return $gcFileName;
    }

    /**
     * @param $payment
     * @param $status
     * @param $type
     * @return string
     */
    private function _formatPaymentMethod($payment, $status, $type){
        $paymentMethod = "";

        if (!$paymentMethod) {
            if (preg_match("/refund/is", $type)) {
                $paymentMethod = "GRABPAY REFUND";
            }
        }

        if (!$paymentMethod) {
            if (preg_match("/static/is", $status)
                && preg_match("/credit/is", $payment)
            ) {
                $paymentMethod = "GRABPAY STATIC";
            }
        }

        if (!$paymentMethod) {
            if (preg_match("/paylater/is", $payment)) {
                $paymentMethod = "GRABPAY LATER";
            }
        }

        if (!$paymentMethod) {
            if (preg_match("/credit/is", $payment)) {
                $paymentMethod = "GRABPAY CREDIT";
            }
        }

        return $paymentMethod;
    }

    /**
     * @param $text
     * @return null
     */
    private function _formatStatus($text){
        $status = "";
        if (preg_match("/static/is", $text)) {
            $status = "POS";
        }

        if (preg_match("/api/is", $text)) {
            $status = "ECOMMERCE";
        }

        if (preg_match("/pos/is", $text)) {
            $status = "POS";
        }

        return $status;
    }
}