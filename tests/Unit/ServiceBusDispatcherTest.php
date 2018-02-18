<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit;

use StephBug\ServiceBus\ServiceBusDispatcher;
use StephBugTest\ServiceBus\Unit\Mock\TestMessageHandler;
use StephBugTest\ServiceBus\Unit\Mock\TestSomeCommand;

class ServiceBusDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function it_dispatch_command_message(): void
    {
        $this->app->singleton(TestMessageHandler::class);
        $handler = $this->app->make(TestMessageHandler::class);

        $command = new TestSomeCommand([]);

        $dispatcher = new ServiceBusDispatcher($this->bus);
        $dispatcher->dispatch($command);

        $this->assertSame($command, $handler->lastCommand());
    }

    /**
     * @test
     */
    public function it_dispatch_event_message(): void
    {

    }

    /**
     * @test
     */
    public function it_dispatch_query_message(): void
    {

    }
}