<?php

declare(strict_types=1);

namespace Mercury\Payment\Api;

use MercuryCash\SDK\Endpoints\Transaction;

interface MercuryGatewayInterface
{
    public const API_HOST = 'https://api-way.mercury.cash';
    public const API_HOST_DEV = 'https://api-way.mercurydev.tk';

    public const CRYPTO = [
        'ETH' => 'ethereum',
        'BTC' => 'bitcoin',
        'DASH' => 'dash',
    ];

    public const TRANSACTION_APROVED = 'TRANSACTION_APROVED';
    public const TRANSACTION_RECEIVED = 'TRANSACTION_RECEIVED';

    public function getInstance(): Transaction;
}
