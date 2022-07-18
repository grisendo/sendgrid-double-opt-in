<?php

declare(strict_types=1);

namespace Grisendo\DDD\ValueObject;

use Grisendo\DDD\Uuid;
use Stringable;

abstract class UuidValueObject implements Stringable
{
    private Uuid $value;

    public function __construct(Uuid $value)
    {
        $this->value = $value;
    }

    public function getValue(): Uuid
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->__toString();
    }
}
