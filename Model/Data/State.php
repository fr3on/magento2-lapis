<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Data;

use Fr3on\Lapis\Api\Data\StateInterface;

class State implements StateInterface
{
    public function __construct(
        private string $id,
        private string $label,
        private string|int|null $magentoValue,
        private bool $initial = false,
        private bool $terminal = false,
        private bool $inferred = false,
        private ?string $customModule = null
    ) {}

    public function getId(): string { return $this->id; }
    public function getLabel(): string { return $this->label; }
    public function getMagentoValue(): string|int|null { return $this->magentoValue; }
    public function isInitial(): bool { return $this->initial; }
    public function isTerminal(): bool { return $this->terminal; }
    public function isInferred(): bool { return $this->inferred; }
    public function getCustomModule(): ?string { return $this->customModule; }
}
