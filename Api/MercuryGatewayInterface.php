<?php

declare(strict_types=1);

namespace Mercury\Payment\Api;

interface MercuryGatewayInterface
{
    public const API_HOST = 'https://api-way.mercury.cash';
    public const API_HOST_DEV = 'https://api-way.mercurydev.tk';

    public const CRYPTO = [
        'ETH' => 'ethereum',
        'BTC' => 'bitcoin',
        'DASH' => 'dash',
    ];

    public function getInstance();
}
