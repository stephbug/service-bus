<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use StephBug\ServiceBus\ServiceBusManager;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var ServiceBusManager
     */
    protected $bus;

    public function setUp(): void
    {
        $app = new Application();
        $app->singleton('config', function () {
            $config = require(__DIR__ . '/Fixture/service_bus.php');
            return new Repository(['service_bus' => $config]);
        });

        $app->singleton('service_bus', function (Application $app) {
            return new ServiceBusManager($app);
        });

        $this->bus = $app->make('service_bus');

        $this->app = $app;
    }
}