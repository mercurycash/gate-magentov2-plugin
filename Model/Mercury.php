<?php

namespace Mercury\Payment\Model;

use Magento\Payment\Model\Method\AbstractMethod;

class Mercury extends AbstractMethod
{
    /**
     * Method code
     */
    const CODE = 'mercurypayment';

    /**
     * @var string
     */
    protected $_code = self::CODE;
}
