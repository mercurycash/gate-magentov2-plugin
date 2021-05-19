<?php

declare(strict_types=1);

namespace Mercury\Payment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Mercury\Payment\Model\Config\Source\EnvironmentSource;

final class Config extends AbstractHelper
{
    const XML_PATH_ACTIVE = 'payment/mercurypayment/active';
    const XML_PATH_TITLE = 'payment/mercurypayment/title';
    const XML_PATH_DESCRIPTION = 'payment/mercurypayment/description';
    const XML_PATH_BITCOIN_MIN = 'payment/mercurypayment/bitcoinmin';
    const XML_PATH_ETHEREUM_MIN = 'payment/mercurypayment/ethereummin';
    const XML_PATH_DASH_MIN = 'payment/mercurypayment/dashmin';
    const XML_PATH_MODE = 'payment/mercurypayment/mode';
    const XML_PATH_PENDING_SET = 'payment/mercurypayment/pending_set';

    const XML_PATH_TEST_PUBLISHABLE_KEY = 'payment/mercurypayment/test_publishable_key';
    const XML_PATH_TEST_PRIVATE_KEY = 'payment/mercurypayment/test_private_key';

    const XML_PATH_PUBLISHABLE_KEY = 'payment/mercurypayment/publishable_key';
    const XML_PATH_PRIVATE_KEY = 'payment/mercurypayment/private_key';

    public function isActive($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getTitle($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TITLE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getDescription($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DESCRIPTION,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getBitcoinMin($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_BITCOIN_MIN,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getEthereumMin($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ETHEREUM_MIN,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getDashMin($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DASH_MIN,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getMode($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getPublishableKey($store = null)
    {
        return $this->getMode($store) === EnvironmentSource::PROD_ENVIRONMENT ?
            $this->scopeConfig->getValue(
                self::XML_PATH_PUBLISHABLE_KEY,
                ScopeInterface::SCOPE_STORE,
                $store
            )
            : $this->scopeConfig->getValue(
                self::XML_PATH_TEST_PUBLISHABLE_KEY,
                ScopeInterface::SCOPE_STORE,
                $store
            );
    }

    public function getPrivateKey($store = null)
    {
        return $this->getMode($store) === EnvironmentSource::PROD_ENVIRONMENT
            ? $this->scopeConfig->getValue(
                self::XML_PATH_PRIVATE_KEY,
                ScopeInterface::SCOPE_STORE,
                $store
            )
            : $this->scopeConfig->getValue(
                self::XML_PATH_TEST_PRIVATE_KEY,
                ScopeInterface::SCOPE_STORE,
                $store
            );
    }

    public function getPendingSet($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PENDING_SET,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function isProd($store = null): bool
    {
        return $this->getMode($store) === EnvironmentSource::PROD_ENVIRONMENT;
    }
}
