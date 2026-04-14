<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Api\Data;

/**
 * Interface TransitionInterface
 */
interface TransitionInterface
{
    /**
     * @return string
     */
    public function getFrom(): string;

    /**
     * @return string
     */
    public function getTo(): string;

    /**
     * @return string
     */
    public function getVia(): string;

    /**
     * @return string
     */
    public function getActor(): string;

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string;

    /**
     * @return bool
     */
    public function isDocumented(): bool;
}
