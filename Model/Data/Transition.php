<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Data;

use Fr3on\Lapis\Api\Data\TransitionInterface;

/**
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
class Transition implements TransitionInterface
{
    public function __construct(
        private string $from,
        private string $toState,
        private string $via,
        private string $actor,
        private ?string $endpoint = null,
        private bool $isDocumented = true
    ) {}

    public function getFrom(): string { return $this->from; }
    public function getTo(): string { return $this->toState; }
    public function getVia(): string { return $this->via; }
    public function getActor(): string { return $this->actor; }
    public function getEndpoint(): ?string { return $this->endpoint; }
    public function isDocumented(): bool { return $this->isDocumented; }
}
