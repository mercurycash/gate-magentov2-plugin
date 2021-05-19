<?php

declare(strict_types=1);

namespace Mercury\Payment\Controller\Transaction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Mercury\Payment\Service\MercuryGateway;

class Check extends Action
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var MercuryGateway
     */
    private $mercuryGateway;

    public function __construct(
        Context $context,
        MercuryGateway $mercuryGateway,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);
        $this->serializer = $serializer;
        $this->mercuryGateway = $mercuryGateway;
    }

    public function execute()
    {
        $endpoint = $this->mercuryGateway->getInstance();

        $status = $endpoint->status($this->getRequest()->getParam('uuid'));

        return $this->getResponse()->representJson(
            $this->serializer->serialize(
                [
                    'data' => [
                        'status' => $status->getStatus(),
                        'confirmations' => $status->getConfirmations(),
                    ]
                ]
            )
        );
    }
}
