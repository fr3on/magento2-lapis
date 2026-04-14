<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Generator;

use Fr3on\Lapis\Api\StateReaderInterface;
use Fr3on\Lapis\Api\Data\StateInterface;
use Fr3on\Lapis\Api\Data\TransitionInterface;

class RldGenerator
{
    /**
     * @param StateReaderInterface[] $readers
     */
    public function __construct(
        private array $readers = []
    ) {}

    /**
     * Generate RLD data for all registered readers.
     *
     * @return array
     */
    public function generateAll(): array
    {
        $result = [];
        foreach ($this->readers as $id => $reader) {
            $result[$id] = $this->generate($reader);
        }
        return $result;
    }

    /**
     * Generate RLD data for a single reader.
     *
     * @param StateReaderInterface $reader
     * @return array
     */
    public function generate(StateReaderInterface $reader): array
    {
        return [
            'resource' => $reader->getResourceId(),
            'generated' => (new \DateTime())->format('c'),
            'generator' => 'magento2-lapis/0.1.0',
            'api' => [
                'name' => 'Magento 2 REST API',
                'version' => 'V1',
                'base_path' => $reader->getEndpointPattern(),
                'docs' => 'https://developer.adobe.com/commerce/webapi/rest/',
            ],
            'state_field' => $reader->getStateField(),
            'inferred' => $reader->isInferred(),
            'states' => $this->buildStates($reader->getStates()),
            'transitions' => $this->buildTransitions($reader->getTransitions()),
        ];
    }

    private function buildStates(array $states): array
    {
        $result = [];
        foreach ($states as $state) {
            $result[] = [
                'id' => $state->getId(),
                'label' => $state->getLabel(),
                'magento_value' => $state->getMagentoValue(),
                'initial' => $state->isInitial(),
                'terminal' => $state->isTerminal(),
                'inferred' => $state->isInferred()
            ];
        }
        return $result;
    }

    private function buildTransitions(array $transitions): array
    {
        $result = [];
        foreach ($transitions as $transition) {
            $result[] = [
                'from' => $transition->getFrom(),
                'to' => $transition->getTo(),
                'via' => $transition->getVia(),
                'actor' => $transition->getActor(),
                'endpoint' => $transition->getEndpoint(),
                'documented' => $transition->isDocumented()
            ];
        }
        return $result;
    }
}
