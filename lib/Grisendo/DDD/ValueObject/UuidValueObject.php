<?php

declare(strict_types=1);

namespace Grisendo\DDD\ValueObject;

use Grisendo\DDD\Uuid;

abstract class UuidValueObject
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
}
