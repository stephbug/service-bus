<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit\Bus;

use StephBug\ServiceBus\Bus\CommandBus;
use StephBugTest\ServiceBus\Unit\Mock\TestBusPlugin;
use StephBugTest\ServiceBus\Unit\Mock\TestMessageHandler;
use StephBugTest\ServiceBus\Unit\Mock\TestSomeCommand;
use StephBugTest\ServiceBus\Unit\TestCase;

class CommandBusTest extends TestCase
{
    /**
     * @test
     */
    public function it_dispatch_message(): void
    {
        $this->app->singleton(TestMessageHandler::class);
        $handler = $this->app->make(TestMessageHandler::class);
        $command = new TestSomeCommand([]);

        $this->bus->command()->dispatch($command);

        $this->assertSame($command, $handler->lastCommand());
    }

    /**
     * @test
     */
    public function it_supports_multiple_buses(): void
    {
        $defaultBus = $this->bus->command();
        $anotherBus = $this->bus->command('another');

        $this->assertInstanceOf(CommandBus::class, $defaultBus);
        $this->assertInstanceOf(CommandBus::class, $anotherBus);
        $this->assertNotSame($defaultBus, $anotherBus);
    }

    /**
     * @test
     */
    public function it_resolve_handler_through_container(): void
    {
        $this->bus->command()->dispatch(new TestSomeCommand([]));

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_attach_plugin(): void
    {
        $plugin = new TestBusPlugin();
        $this->app->singleton(TestBusPlugin::class, function () use ($plugin) {
            return $plugin;
        });

        $this->bus->command()->dispatch($command = new TestSomeCommand([]));

        $this->assertTrue($plugin->wasFired());
    }

    /**
     * @test
     * @expectedException \StephBug\ServiceBus\Exception\RuntimeException
     */
    public function it_raises_exception_when_bus_name_is_unknown(): void
    {
        $this->bus->command('unknown_bus_name');
    }
}