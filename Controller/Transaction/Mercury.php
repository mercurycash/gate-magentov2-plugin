<?php

declare(strict_types=1);

namespace Mercury\Payment\Controller\Transaction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Mercury\Payment\Api\MercuryGatewayInterface;
use Mercury\Payment\Service\MercuryGateway;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Mercury extends Action implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * @var MercuryGateway
     */
    private $mercuryGateway;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    public function __construct(
        Context $context,
        MercuryGatewayInterface $mercuryGateway,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->mercuryGateway = $mercuryGateway;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $endpoint = $this->mercuryGateway->getInstance();
        $crypto = $this->getRequest()->getParam('crypto');

        $transaction = $endpoint->create([
            'email' => $this->getRequest()->getParam('email'),
            'crypto' => $this->getRequest()->getParam('crypto'),
            'fiat' => $this->getRequest()->getParam('currency'),
            'amount' => $this->getRequest()->getParam('price'),
            'tip' => 0,
        ]);

        $endpoint->process($transaction->getUuid());

        $address = $transaction->getAddress();
        $amount = $transaction->getCryptoAmount();

        $cryptoName = MercuryGatewayInterface::CRYPTO[$crypto];

        $qrCodeText = '';
        $qrCodeText .= $cryptoName . ':' . $address . '?';
        $qrCodeText .= 'amount=' . $amount . '&';
        $qrCodeText .= 'cryptoCurrency=' . $crypto;

        return $this->resultJsonFactory->create()->setData(
            [
                'data' => [
                    'uuid' => $transaction->getUuid(),
                    'cryptoAmount' => $amount,
                    'fiatIsoCode' => $transaction->getFiatIsoCode(),
                    'fiatAmount' => $transaction->getFiatAmount(),
                    'exchangeRate' => $transaction->getRate(),
                    'address' => $address,
                    'networkFee' => $transaction->getFee(),
                    'cryptoCurrency' => $crypto,
                    'qrCodeText' => $qrCodeText,
                ],
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
