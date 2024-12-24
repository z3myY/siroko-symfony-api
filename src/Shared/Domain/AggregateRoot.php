<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Event\DomainEventInterface;

abstract class AggregateRoot implements SerializableInterface
{
    /** @var array<DomainEventInterface> **/
    protected array $domainEvents = [];

    public function recordDomainEvent(DomainEventInterface $event): self
    {
        $this->domainEvents[] = $event;

        return $this;
    }

    /**
     * @return array<DomainEventInterface>
     * */
    public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }
}
