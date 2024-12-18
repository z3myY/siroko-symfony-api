<?php
declare(strict_types=1);

namespace App\Shared\Domain\Event;

class DomainEvent implements DomainEventInterface
{
    private array $event;
    private \DateTimeImmutable $recordedAt;
    private array $payload;

    public function __construct(array $event, array $payload = [])
    {
        $this->event = $event;
        $this->payload = $payload;
        $this->recordedAt = new \DateTimeImmutable();
    }

    public function event(): array
    {
        return $this->event;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function recordedAt(): \DateTimeImmutable
    {
        return $this->recordedAt;
    }


}
