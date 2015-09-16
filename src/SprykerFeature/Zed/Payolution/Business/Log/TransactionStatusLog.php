<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Log;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;

class TransactionStatusLog implements TransactionStatusLogInterface
{

    /**
     * @var PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param PayolutionQueryContainerInterface $queryContainer
     */
    public function __construct(PayolutionQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            Constants::PAYMENT_CODE_PRE_AUTHORIZATION,
            Constants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            Constants::PAYMENT_CODE_CAPTURE,
            Constants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param string $paymentCode
     * @param string $exectedResponse
     *
     * @return bool
     */
    private function hasTransactionLogStatus(OrderTransfer $orderTransfer, $paymentCode, $exectedStatusReasonCode)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $paymentEntity = $this->queryContainer->queryPaymentBySalesOrderId($idSalesOrder)->findOne();

        $logEntity = $this
            ->queryContainer
            ->queryLatestItemOfTransactionStatusLogByPaymentIdAndPaymentCode(
                $paymentEntity->getIdPaymentPayolution(),
                $paymentCode
            )
            ->findOne();

        if (!$logEntity) {
            return false;
        }

        $expectedProcessingCode = $paymentCode . '.' . $exectedStatusReasonCode;

        return ($expectedProcessingCode === $logEntity->getProcessingCode());
    }

}
