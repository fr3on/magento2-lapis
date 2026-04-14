<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Test\Unit\Model\Reader;

use Fr3on\Lapis\Model\Reader\OrderStateReader;
use Fr3on\Lapis\Model\Data\StateFactory;
use Fr3on\Lapis\Model\Data\TransitionFactory;
use Fr3on\Lapis\Model\Data\State;
use Fr3on\Lapis\Model\Data\Transition;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\TestCase;

class OrderStateReaderTest extends TestCase
{
    private $resourceConnection;
    private $stateFactory;
    private $transitionFactory;
    private $reader;
    private $connection;

    protected function setUp(): void
    {
        $this->resourceConnection = $this->createMock(ResourceConnection::class);
        $this->connection = $this->createMock(AdapterInterface::class);
        $this->stateFactory = $this->createMock(StateFactory::class);
        $this->transitionFactory = $this->createMock(TransitionFactory::class);

        $this->resourceConnection->method('getConnection')->willReturn($this->connection);
        $this->resourceConnection->method('getTableName')->willReturnArgument(0);

        $this->reader = new OrderStateReader(
            $this->resourceConnection,
            $this->stateFactory,
            $this->transitionFactory
        );
    }

    public function testGetResourceId()
    {
        $this->assertEquals('order', $this->reader->getResourceId());
    }

    public function testGetStates()
    {
        $select = $this->createMock(Select::class);
        $this->connection->method('select')->willReturn($select);
        $select->method('from')->willReturnSelf();
        $select->method('joinLeft')->willReturnSelf();

        $rows = [
            ['status' => 'pending', 'label' => 'Pending', 'state' => 'new', 'is_default' => 1],
            ['status' => 'processing', 'label' => 'Processing', 'state' => 'processing', 'is_default' => 1],
            ['status' => 'complete', 'label' => 'Complete', 'state' => 'complete', 'is_default' => 1]
        ];
        $this->connection->method('fetchAll')->willReturn($rows);

        $this->stateFactory->expects($this->exactly(3))
            ->method('create')
            ->willReturn($this->createMock(State::class));

        $states = $this->reader->getStates();
        $this->assertCount(3, $states);
    }

    public function testGetTransitions()
    {
        $this->transitionFactory->method('create')
            ->willReturn($this->createMock(Transition::class));

        $transitions = $this->reader->getTransitions();
        $this->assertGreaterThanOrEqual(11, count($transitions));
    }
}
