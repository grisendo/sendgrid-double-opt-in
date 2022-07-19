<?php

declare(strict_types=1);

namespace Grisendo\DDD;

use DomainException as BaseDomainException;

abstract class DomainException extends BaseDomainException
{
    public function __construct()
    {
        parent::__construct($this->getErrorMessage());
    }

    abstract public function getErrorCode(): string;

    abstract protected function getErrorMessage(): string;
}
