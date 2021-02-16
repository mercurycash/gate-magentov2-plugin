<?php

declare(strict_types=1);

namespace Mercury\Payment\Controller\Transaction;

require_once __DIR__ . '/../../sdk/vendor/autoload.php';

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mercury\Payment\Helper\Config;
use MercuryCash\SDK\Adapter;
use MercuryCash\SDK\Auth\APIKey;
use MercuryCash\SDK\Endpoints\Transaction;

class Mercury extends Action
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Session
     */
    private $cart;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        Context $context,
        Config $config,
        Session $cart,
        StoreManagerInterface $storeManager,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->cart = $cart;
        $this->storeManager = $storeManager;
        $this->serializer = $serializer;
    }

    public function execute()
    {
        $quote = $this->cart->getQuote();

        $apiKey = new APIKey($this->config->getPublishableKey(), $this->config->getPrivateKey());
        $adapter = new Adapter($apiKey, 'https://api-way.mercurydev.tk');
        $endpoint = new Transaction($adapter);

        $transaction = $endpoint->create([
            'email' => null !== $quote->getBillingAddress() ? $quote->getBillingAddress()->getEmail() : '',
            'crypto' => $this->getRequest()->getParam('crypto'),
            'fiat' => $this->storeManager->getStore()->getCurrentCurrencyCode(),
            'amount' => $quote->getGrandTotal(),
            'tip' => 0,
        ]);

        $endpoint->process($transaction->getUuid());

        return $this->getResponse()->representJson(
            $this->serializer->serialize([
                'uuid' => $transaction->getUuid(),
                'cryptoAmount' => $transaction->getCryptoAmount(),
                'fiatIsoCode' => $transaction->getFiatIsoCode(),
                'fiatAmount' => $transaction->getFiatAmount(),
                'rate' => $transaction->getRate(),
                'address' => $transaction->getAddress(),
                'fee' => $transaction->getFee()
            ])
        );
    }
}
