<?php

declare(strict_types=1);

namespace Grisendo\DDD\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
