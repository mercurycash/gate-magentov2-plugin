<?php

declare(strict_types=1);

namespace Mercury\Payment\Block;

use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;

class Config extends Template
{
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

    public function getAjaxUrl()
    {
        return $this->getUrl('mercury/transaction/mercury');
    }

    public function getAjaxUrlCheckStatus()
    {
        return $this->getUrl('mercury/transaction/check');
    }
}
