<?php

declare(strict_types=1);

namespace StephBugDev\ServiceBus;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use StephBugDev\ServiceBus\Bus\CommandBus;
use StephBugDev\ServiceBus\Bus\EventBus;
use StephBugDev\ServiceBus\Bus\QueryBus;
use StephBugDev\ServiceBus\Exception\RuntimeException;

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

        // $this->registerDefaultBuses();
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
        $buses = $this->app->make('config')->get('service_bus.buses');

        foreach ($buses as $bus) {
            foreach ($bus as $type => $name) {

                switch ($type) {
                    case 'command':
                        $typeAlias = CommandBus::class;
                        break;

                    case 'event':
                        $typeAlias = EventBus::class;
                        break;

                    case 'query':
                        $typeAlias = QueryBus::class;
                        break;

                    default:
                        throw new RuntimeException(
                            sprintf('Unable to determine bus type %s for bus name %s', $type, $name)
                        );
                }

                $this->app->singleton($typeAlias, function (Application $app) use ($typeAlias, $name) {
                    return $app->make('service_bus')->make($name, $typeAlias);
                });
            }
        }
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