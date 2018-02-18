<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit\Mock;

use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Event\DetachAggregateHandlers;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\Plugin\AbstractPlugin;

class TestBusPlugin extends AbstractPlugin
{
    use DetachAggregateHandlers;

    private $fired = false;

    public function wasFired(): bool
    {
        return $this->fired;
    }

    public function attachToMessageBus(MessageBus $bus): void
    {
        $this->trackHandler($bus->attach(
            MessageBus::EVENT_DISPATCH,
            [$this, 'onInitialize'],
            MessageBus::PRIORITY_INITIALIZE
        ));
    }

    public function onInitialize(ActionEvent $event): void
    {
        $this->fired = true;
    }

    public function reset(): void
    {
        $this->fired = false;
    }
}