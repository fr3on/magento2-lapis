<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Data;

use Fr3on\Lapis\Api\Data\StateInterface;

/**
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
class State implements StateInterface
{
    public function __construct(
        private string $stateId,
        private string $label,
        private string|int|null $magentoValue,
        private bool $isInitial = false,
        private bool $isTerminal = false,
        private bool $isInferred = false,
        private ?string $customModule = null
    ) {}

    public function getId(): string { return $this->stateId; }
    public function getLabel(): string { return $this->label; }
    public function getMagentoValue(): string|int|null { return $this->magentoValue; }
    public function isInitial(): bool { return $this->isInitial; }
    public function isTerminal(): bool { return $this->isTerminal; }
    public function isInferred(): bool { return $this->isInferred; }
    public function getCustomModule(): ?string { return $this->customModule; }
}
