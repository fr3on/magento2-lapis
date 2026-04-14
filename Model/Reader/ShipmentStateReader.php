<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Reader;

use Fr3on\Lapis\Api\StateReaderInterface;
use Fr3on\Lapis\Model\Data\StateFactory;
use Fr3on\Lapis\Model\Data\TransitionFactory;

class ShipmentStateReader implements StateReaderInterface
{
    private const RESOURCE_ID = 'shipment';
    private const ENDPOINT_PATTERN = '/rest/V1/shipments/{id}';

    public function __construct(
        private StateFactory $stateFactory,
        private TransitionFactory $transitionFactory
    ) {}

    public function getResourceId(): string { return self::RESOURCE_ID; }
    public function getEndpointPattern(): string { return self::ENDPOINT_PATTERN; }
    public function getStateField(): ?string { return null; }
    public function isInferred(): bool { return true; }

    public function getStates(): array
    {
        $statesRaw = [
            'pending' => 'Pending',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered'
        ];

        $states = [];
        foreach ($statesRaw as $id => $label) {
            $states[] = $this->stateFactory->create([
                'stateId' => $id,
                'label' => $label,
                'magentoValue' => null,
                'isInitial' => ($id === 'pending'),
                'isTerminal' => ($id === 'delivered'),
                'isInferred' => true
            ]);
        }
        return $states;
    }

    public function getTransitions(): array
    {
        $transitions = [
            ['pending', 'shipped', 'addTrack()', 'admin', 'POST /rest/V1/shipment/{id}/track'],
            ['shipped', 'delivered', 'carrier_callback', 'carrier', null],
        ];

        $result = [];
        foreach ($transitions as $t) {
            $result[] = $this->transitionFactory->create([
                'from' => $t[0],
                'toState' => $t[1],
                'via' => $t[2],
                'actor' => $t[3],
                'endpoint' => $t[4],
                'isDocumented' => false
            ]);
        }
        return $result;
    }
}
