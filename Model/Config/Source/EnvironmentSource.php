<?php

declare(strict_types=1);

namespace Mercury\Payment\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

final class EnvironmentSource implements OptionSourceInterface
{
    const TEST_ENVIRONMENT = 'test';
    const PROD_ENVIRONMENT = 'prod';

    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::TEST_ENVIRONMENT,
                'label' => __('Test mode'),
            ],
            [
                'value' => self::PROD_ENVIRONMENT,
                'label' => __('Live mode'),
            ],
        ];
    }
}
