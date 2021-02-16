<?php

declare(strict_types=1);

namespace Mercury\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    public const AVAILABLE_CURRENCY = [
        'ETH' => 'ethereum',
        'BTC' => 'bitcoin',
        'DASH' => 'dash',
    ];

    public function getConfig(): array
    {
        return [
            'payment' => [
                Mercury::CODE => [
                    'transactionResults' => self::AVAILABLE_CURRENCY,
                ],
            ]
        ];
    }
}
