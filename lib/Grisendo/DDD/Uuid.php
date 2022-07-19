<?php

declare(strict_types=1);

namespace Grisendo\DDD;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;

final class Uuid implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public static function random(): self
    {
        return new Uuid(RamseyUuid::uuid4()->toString());
    }

    public static function isValid(string $id): bool
    {
        return RamseyUuid::isValid($id);
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!self::isValid($id)) {
            $msg = sprintf(
                '<%s> does not allow the value <%s>.',
                static::class,
                $id
            );
            throw new InvalidArgumentException($msg);
        }
    }
}
