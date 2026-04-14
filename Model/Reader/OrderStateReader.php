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

class OrderStateReader implements StateReaderInterface
{
    private const RESOURCE_ID = 'order';
    private const ENDPOINT_PATTERN = '/rest/V1/orders/{id}';
    private const STATE_FIELD = 'status';

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
        $connection = $this->resourceConnection->getConnection();
        $statusTable = $this->resourceConnection->getTableName('sales_order_status');
        $statusStateTable = $this->resourceConnection->getTableName('sales_order_status_state');

        $query = $connection->select()
            ->from(['sos' => $statusTable], ['status', 'label'])
            ->joinLeft(
                ['soss' => $statusStateTable],
                'sos.status = soss.status',
                ['state', 'is_default']
            );

        $rows = $connection->fetchAll($query);
        $states = [];

        foreach ($rows as $row) {
            $states[] = $this->stateFactory->create([
                'stateId' => $row['status'],
                'label' => $row['label'],
                'magentoValue' => $row['status'],
                'isInitial' => ($row['state'] === 'new' && $row['is_default']),
                'isTerminal' => in_array($row['state'], ['complete', 'closed', 'canceled', 'fraud']),
                'isInferred' => false
            ]);
        }

        return $states;
    }

    public function getTransitions(): array
    {
        // Core transitions as documented in Magento
        $transitions = [
            ['new', 'canceled', 'cancel()', 'admin', 'POST /rest/V1/orders/{id}/cancel'],
            ['new', 'pending_payment', 'place()', 'system', null],
            ['pending_payment', 'new', 'payment_review()', 'system', null],
            ['pending_payment', 'canceled', 'expire()', 'system', null],
            ['payment_review', 'processing', 'acceptPayment()', 'admin', 'POST /rest/V1/orders/{id}/capture'],
            ['payment_review', 'canceled', 'denyPayment()', 'admin', null],
            ['payment_review', 'fraud', 'flagAsFraud()', 'system', null],
            ['processing', 'holded', 'hold()', 'admin', 'POST /rest/V1/orders/{id}/hold'],
            ['processing', 'complete', 'auto_complete()', 'system', null],
            ['holded', 'processing', 'unhold()', 'admin', 'POST /rest/V1/orders/{id}/unhold'],
            ['complete', 'closed', 'creditmemo()', 'admin', null],
        ];

        $result = [];
        foreach ($transitions as $t) {
            $result[] = $this->transitionFactory->create([
                'from' => $t[0],
                'toState' => $t[1],
                'via' => $t[2],
                'actor' => $t[3],
                'endpoint' => $t[4],
                'isDocumented' => true
            ]);
        }

        // TODO: Implement heuristic detection for custom transitions in Phase 2 refinements
        return $result;
    }
}
