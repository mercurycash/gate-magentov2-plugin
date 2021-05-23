<?php

declare(strict_types=1);

namespace Mercury\Payment\Controller\Transaction;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Mercury\Payment\Api\MercuryGatewayInterface;
use Mercury\Payment\Service\MercuryGateway;

class Check extends Action implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * @var MercuryGateway
     */
    private $mercuryGateway;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    public function __construct(
        Context $context,
        MercuryGateway $mercuryGateway,
        JsonFactory $resultJsonFactory,
        CartRepositoryInterface $cartRepository,
        Session $session
    ) {
        parent::__construct($context);
        $this->mercuryGateway = $mercuryGateway;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session = $session;
        $this->cartRepository = $cartRepository;
    }

    public function execute()
    {
        $endpoint = $this->mercuryGateway->getInstance();

        $uuid = $this->getRequest()->getParam('uuid');

        $status = $endpoint->status($uuid);

        if ($status->getStatus() === MercuryGatewayInterface::TRANSACTION_APROVED) {
            $quote = $this->session->getQuote();

            $quote->getPayment()->setData('mercury_transaction', $uuid);

            $this->cartRepository->save($quote);
        }

        return $this->resultJsonFactory->create()->setData(
            [
                'data' => [
                    'status' => $status->getStatus(),
                    'confirmations' => $status->getConfirmations(),
                ]
            ]
        );
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
