<?php

declare(strict_types=1);

namespace StephBugDev\ServiceBus\Bus;

use Prooph\ServiceBus\CommandBus as BaseCommandBus;

class CommandBus extends BaseCommandBus implements NamedMessageBus
{
    use NamedMessageBusTrait;
}