<?php

declare(strict_types=1);

namespace Mercury\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Mercury\Payment\Api\MercuryGatewayInterface;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    public function getConfig(): array
    {
        return [
            'payment' => [
                Mercury::CODE => [
                    'transactionResults' => MercuryGatewayInterface::CRYPTO,
                ],
            ]
        ];
    }
}
