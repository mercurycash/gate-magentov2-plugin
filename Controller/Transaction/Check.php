<?php

declare(strict_types=1);

namespace Mercury\Payment\Controller\Transaction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Mercury\Payment\Helper\Config;
use MercuryCash\SDK\Adapter;
use MercuryCash\SDK\Auth\APIKey;
use MercuryCash\SDK\Endpoints\Transaction;

class Check extends Action
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        Context $context,
        Config $config,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->serializer = $serializer;
    }

    public function execute()
    {
        $apiKey = new APIKey($this->config->getPublishableKey(), $this->config->getPrivateKey());
        $adapter = new Adapter($apiKey, 'https://api-way.mercurydev.tk');
        $endpoint = new Transaction($adapter);

        $status = $endpoint->status($this->getRequest()->getParam('uuid'));

        return $this->getResponse()->representJson(
            $this->serializer->serialize([
                'status' => $status,
            ])
        );
    }
}
