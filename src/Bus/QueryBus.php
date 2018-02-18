<?php

declare(strict_types=1);

namespace StephBugDev\ServiceBus\Bus;

use Prooph\ServiceBus\QueryBus as BaseQueryBus;

class QueryBus extends BaseQueryBus implements NamedMessageBus
{
    use NamedMessageBusTrait;
}