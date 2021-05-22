<?php

declare(strict_types=1);

namespace Mercury\Payment\Controller\Transaction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Mercury\Payment\Api\MercuryGatewayInterface;
use Mercury\Payment\Service\MercuryGateway;

class Mercury extends Action
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var MercuryGateway
     */
    private $mercuryGateway;

    public function __construct(
        Context $context,
        MercuryGatewayInterface $mercuryGateway,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);
        $this->serializer = $serializer;
        $this->mercuryGateway = $mercuryGateway;
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

        return $this->getResponse()->representJson(
            $this->serializer->serialize([
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
            ])
        );
    }
}
