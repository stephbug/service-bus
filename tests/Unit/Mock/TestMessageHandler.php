<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit\Mock;

class TestMessageHandler
{
    private $received = [];

    public function __invoke(TestSomeCommand $command)
    {
        $this->received[] = $command;
    }

    public function lastCommand(): TestSomeCommand
    {
        return end($this->received);
    }
}