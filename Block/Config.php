<?php

declare(strict_types=1);

namespace Mercury\Payment\Block;

use Magento\Checkout\Model\Session;
use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\Quote;

class Config extends Template
{
    /**
     * @var Session
     */
    private $cart;
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \Mercury\Payment\Helper\Config
     */
    private $config;

    public function __construct(
        Template\Context $context,
        Session $cart,
        \Mercury\Payment\Helper\Config $config,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->cart = $cart;
        $this->priceCurrency = $priceCurrency;
        $this->config = $config;
    }

    /**
     * Returns label
     *
     * @param string $field
     * @return Phrase
     */
    protected function getLabel($field)
    {
        return __($field);
    }

    public function mercuryData(): array
    {
        return [
            'cart_price' => $this->getQuote()->getGrandTotal(),
            'currency' => $this->getCurrency(),
            'curr_symbol' => $this->getCurrencySymbol(),
            'btc' => $this->config->getBitcoinMin(),
            'eth' => $this->config->getEthereumMin(),
            'dash' => $this->config->getDashMin(),
            'time' => $this->config->getPendingSet(),
            'email' => $this->getEmail(),
            'pathCreateTransaction' => $this->getAjaxUrl(),
            'pathCheckTransaction' => $this->getAjaxUrlCheckStatus(),
            'url' => $this->getViewFileUrl('/')
        ];
    }

    public function getAjaxUrl(): string
    {
        return $this->getUrl('mercury/transaction/mercury', ['_secure' => true]);
    }

    public function getAjaxUrlCheckStatus(): string
    {
        return $this->getUrl('mercury/transaction/check', ['_secure' => true]);
    }

    private function getQuote(): Quote
    {
        return $this->cart->getQuote();
    }

    private function getEmail()
    {
        $quote = $this->getQuote()->getBillingAddress();

        return $quote->getEmail() ?: $quote->getCustomerEmail();
    }

    private function getCurrency(): string
    {
        return $this->priceCurrency->getCurrency()->getCurrencyCode();
    }

    private function getCurrencySymbol(): string
    {
        return $this->priceCurrency->getCurrency()->getCurrencySymbol();
    }
}
