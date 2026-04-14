<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Test\Unit\Model\Generator;

use Fr3on\Lapis\Model\Generator\RldGenerator;
use Fr3on\Lapis\Api\StateReaderInterface;
use Fr3on\Lapis\Api\Data\StateInterface;
use Fr3on\Lapis\Api\Data\TransitionInterface;
use PHPUnit\Framework\TestCase;

class RldGeneratorTest extends TestCase
{
    private $generator;
    private $reader;

    protected function setUp(): void
    {
        $this->reader = $this->createMock(StateReaderInterface::class);
        $this->generator = new RldGenerator(['order' => $this->reader]);
    }

    public function testGenerate()
    {
        $this->reader->method('getResourceId')->willReturn('order');
        $this->reader->method('getEndpointPattern')->willReturn('/api/orders');
        $this->reader->method('getStateField')->willReturn('status');
        $this->reader->method('isInferred')->willReturn(false);

        $state = $this->createMock(StateInterface::class);
        $state->method('getId')->willReturn('new');
        $state->method('getLabel')->willReturn('New');
        
        $this->reader->method('getStates')->willReturn([$state]);
        
        $transition = $this->createMock(TransitionInterface::class);
        $transition->method('getFrom')->willReturn('new');
        $transition->method('getTo')->willReturn('processing');
        $transition->method('getVia')->willReturn('process()');
        $transition->method('getActor')->willReturn('admin');
        
        $this->reader->method('getTransitions')->willReturn([$transition]);

        $result = $this->generator->generate($this->reader);

        $this->assertEquals('order', $result['resource']);
        $this->assertCount(1, $result['states']);
        $this->assertCount(1, $result['transitions']);
        $this->assertEquals('new', $result['states'][0]['id']);
        $this->assertEquals('process()', $result['transitions'][0]['via']);
    }
}
