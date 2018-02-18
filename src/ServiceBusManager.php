<?php

declare(strict_types=1);

namespace StephBug\ServiceBus;

use Illuminate\Contracts\Foundation\Application;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\Plugin\MessageFactoryPlugin;
use Prooph\ServiceBus\Plugin\Router\AsyncSwitchMessageRouter;
use StephBug\ServiceBus\Bus\CommandBus;
use StephBug\ServiceBus\Bus\EventBus;
use StephBug\ServiceBus\Bus\NamedMessageBus;
use StephBug\ServiceBus\Bus\QueryBus;
use StephBug\ServiceBus\Exception\RuntimeException;
use StephBug\ServiceBus\Plugin\LaravelContainerResolver;

class ServiceBusManager
{

    /**
     * @var Application
     */
    private $app;

    /**
     * @var array
     */
    private $buses = [];

    /**
     * ServiceBusManager constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function make(string $busName, string $busType): NamedMessageBus
    {
        $id = $busName . $busType;

        if (!isset($this->buses[$id])) {
            $config = $this->getConfigForBus($busType, $busName);

            $emitter = $config['emitter'] ?? ProophActionEventEmitter::class;

            $bus = $this->getBusInstance($busType, $busName, $emitter);

            $this->addPlugins($bus, $config);
            $this->addRouter($bus, $config);

            $this->buses[$id] = $bus;
        }

        return $this->buses[$id];
    }

    public function command(string $name = null): NamedMessageBus
    {
        return $this->make($name ?? 'default', 'command');
    }

    public function event(string $name = null): NamedMessageBus
    {
        return $this->make($name ?? 'default', 'event');
    }

    public function query(string $name = null): NamedMessageBus
    {
        return $this->make($name ?? 'default', 'query');
    }

    protected function addPlugins(NamedMessageBus $bus, array $config): void
    {
        $messageContext = $this->app->make($config['message_context'] ?? FQCNMessageFactory::class);
        $messageFactoryPlugin = new MessageFactoryPlugin($messageContext);
        $bus->addPlugin($messageFactoryPlugin);


        $serviceLocator = new LaravelContainerResolver($this->app);
        $bus->addPlugin($serviceLocator);

        foreach ($config['plugins'] ?? [] as $plugin) {
            $bus->addPlugin($this->app->make($plugin));
        }
    }

    protected function addRouter(NamedMessageBus $bus, array $config): void
    {
        $router = $config['router']['concrete'] ?? null;

        if (!$router || !class_exists($router)) {
            throw new RuntimeException(
                sprintf('Unable to locate router class for bus type %s and name %s', $bus->busType(), $bus->busName())
            );
        }

        $routerInstance = new $router($config['router']['routes'] ?? []);

        if ($asyncSwitchId = $config['async_switch_id'] ?? null) {
            $producer = $this->app->make($config['async_switch_id']);

            $routerInstance = new AsyncSwitchMessageRouter($routerInstance, $producer);
        }

        $routerInstance->attachToMessageBus($bus);
    }

    protected function getConfigForBus(string $type, string $name): array
    {
        $id = sprintf('service_bus.buses.%s.%s', $type, $name);

        $config = $this->app->make('config')->get($id);

        if (!$config || empty($config)) {
            throw new RuntimeException(
                sprintf('Unable to locate config for bus type %s and bus name %s', $type, $name)
            );
        }

        return $config;
    }

    protected function getBusInstance(string $type, string $name, string $emitter): NamedMessageBus
    {
        $emitter = $this->app->make($emitter);

        switch ($type) {
            case 'command':
                $busType = CommandBus::class;
                break;

            case 'event':
                $busType = EventBus::class;
                break;

            case 'query':
                $busType = QueryBus::class;
                break;

            default:
                throw new RuntimeException(
                    sprintf('Unable to determine bus type %s for bus name %s', $type, $name)
                );
        }

        $bus = new $busType($emitter);
        $bus->setBusType($type);
        $bus->setBusName($name);

        return $bus;
    }
}