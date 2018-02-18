<?php

declare(strict_types=1);

namespace StephBugDev\ServiceBus\Bus;

use Prooph\ServiceBus\EventBus as BaseEventBus;

class EventBus extends BaseEventBus implements NamedMessageBus
{
    use NamedMessageBusTrait;
}