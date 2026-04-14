<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Reader;

use Fr3on\Lapis\Api\StateReaderInterface;
use Fr3on\Lapis\Model\Data\StateFactory;
use Fr3on\Lapis\Model\Data\TransitionFactory;
use Magento\Framework\App\ResourceConnection;

class InvoiceStateReader implements StateReaderInterface
{
    private const RESOURCE_ID = 'invoice';
    private const ENDPOINT_PATTERN = '/rest/V1/invoices/{id}';
    private const STATE_FIELD = 'state';

    public function __construct(
        private ResourceConnection $resourceConnection,
        private StateFactory $stateFactory,
        private TransitionFactory $transitionFactory
    ) {}

    public function getResourceId(): string { return self::RESOURCE_ID; }
    public function getEndpointPattern(): string { return self::ENDPOINT_PATTERN; }
    public function getStateField(): ?string { return self::STATE_FIELD; }
    public function isInferred(): bool { return false; }

    public function getStates(): array
    {
        // Core invoice states: 1=Pending, 2=Paid, 3=Canceled
        $statesRaw = [
            1 => 'Pending',
            2 => 'Paid',
            3 => 'Canceled'
        ];

        $states = [];
        foreach ($statesRaw as $val => $label) {
            $states[] = $this->stateFactory->create([
                'id' => strtolower($label),
                'label' => $label,
                'magentoValue' => $val,
                'initial' => ($val === 1),
                'terminal' => ($val === 2 || $val === 3),
                'inferred' => false
            ]);
        }
        return $states;
    }

    public function getTransitions(): array
    {
        $transitions = [
            ['pending', 'paid', 'capture()', 'admin', 'POST /rest/V1/invoices/{id}/capture'],
            ['pending', 'canceled', 'cancel()', 'admin', null],
        ];

        $result = [];
        foreach ($transitions as $t) {
            $result[] = $this->transitionFactory->create([
                'from' => $t[0],
                'to' => $t[1],
                'via' => $t[2],
                'actor' => $t[3],
                'endpoint' => $t[4],
                'documented' => true
            ]);
        }
        return $result;
    }
}
