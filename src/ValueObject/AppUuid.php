<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

final class AppUuid
{
    private Uuid $uuid;

    public function __construct(string $uuid)
    {
            $this->ensureIsValidUuid($uuid);
            $this->uuid = Uuid::fromString($uuid);
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    public static function generate(): self
    {
        return new self(UuidV4::v4()->toRfc4122());
    }

    public function __toString(): string
    {
        return $this->uuid->toRfc4122();
    }

    private function ensureIsValidUuid(string $uuid): void
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgumentException(sprintf('Invalid UUID: %s', $uuid));
        }
    }

    public function equals(self $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }
}