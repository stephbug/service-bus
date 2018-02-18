<?php

declare(strict_types=1);

namespace StephBug\ServiceBus;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use StephBug\ServiceBus\Bus\CommandBus;
use StephBug\ServiceBus\Bus\EventBus;
use StephBug\ServiceBus\Bus\QueryBus;

class ServiceBusProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    public function boot(): void
    {
        $this->publishes(
            [$this->getConfigPath() => config_path('service_bus.php')],
            'config'
        );
    }

    public function register(): void
    {
        $this->mergeConfig();

        $this->registerBusManager();

        $this->registerDefaultBuses();
    }

    public function provides(): array
    {
        return [ServiceBusManager::class, 'service_bus'];
    }

    protected function registerBusManager(): void
    {
        $this->app->singleton(ServiceBusManager::class, function (Application $app) {
            return new ServiceBusManager($app);
        });

        $this->app->alias(ServiceBusManager::class, 'service_bus');

        $this->app->bind(ServiceBusDispatcher::class);
    }

    protected function registerDefaultBuses(): void
    {
        $this->app->singleton(CommandBus::class, function (Application $app) {
            return $app->make('service_bus')->command();
        });

        $this->app->singleton(EventBus::class, function (Application $app) {
            return $app->make('service_bus')->event();
        });

        $this->app->singleton(QueryBus::class, function (Application $app) {
            return $app->make('service_bus')->query();
        });
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'service_bus');
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/service_bus.php';
    }
}