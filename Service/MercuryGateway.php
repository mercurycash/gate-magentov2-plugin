<?php

declare(strict_types=1);

namespace Mercury\Payment\Service;

require_once __DIR__ . '/../sdk/vendor/autoload.php';

use Mercury\Payment\Api\MercuryGatewayInterface;
use Mercury\Payment\Helper\Config;
use MercuryCash\SDK\Adapter;
use MercuryCash\SDK\Auth\APIKey;
use MercuryCash\SDK\Endpoints\Transaction;

class MercuryGateway implements MercuryGatewayInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getInstance(): Transaction
    {
        $apiKey = new APIKey($this->config->getPublishableKey(), $this->config->getPrivateKey());

        $host = $this->config->isProd() ? MercuryGatewayInterface::API_HOST : MercuryGatewayInterface::API_HOST_DEV;

        return new Transaction(
            new Adapter($apiKey, $host)
        );
    }
}
