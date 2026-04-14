<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Api;

/**
 * Interface StateReaderInterface
 */
interface StateReaderInterface
{
    /**
     * Resource identifier e.g. 'order', 'invoice'
     *
     * @return string
     */
    public function getResourceId(): string;

    /**
     * API endpoint pattern e.g. '/rest/V1/orders/{id}'
     *
     * @return string
     */
    public function getEndpointPattern(): string;

    /**
     * Name of the API response field that holds the state value.
     * Returns null if the lifecycle is inferred from data shape.
     *
     * @return string|null
     */
    public function getStateField(): ?string;

    /**
     * Returns true if the lifecycle must be inferred rather than
     * read from a single field.
     *
     * @return bool
     */
    public function isInferred(): bool;

    /**
     * Returns all states currently registered in this Magento instance.
     *
     * @return \Fr3on\Lapis\Api\Data\StateInterface[]
     */
    public function getStates(): array;

    /**
     * Returns all known transitions between states.
     *
     * @return \Fr3on\Lapis\Api\Data\TransitionInterface[]
     */
    public function getTransitions(): array;
}
