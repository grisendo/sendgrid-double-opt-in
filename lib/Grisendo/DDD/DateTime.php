<?php

declare(strict_types=1);

namespace Grisendo\DDD;

use DateTimeImmutable;
use Stringable;

class DateTime extends DateTimeImmutable implements Stringable
{
    public function toString(): string
    {
        return $this->format(self::ATOM);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
