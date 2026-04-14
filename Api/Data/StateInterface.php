<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Api\Data;

/**
 * Interface StateInterface
 */
interface StateInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string|int|null
     */
    public function getMagentoValue(): string|int|null;

    /**
     * @return bool
     */
    public function isInitial(): bool;

    /**
     * @return bool
     */
    public function isTerminal(): bool;

    /**
     * @return bool
     */
    public function isInferred(): bool;

    /**
     * @return string|null
     */
    public function getCustomModule(): ?string;
}
