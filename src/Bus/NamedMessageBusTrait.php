<?php

declare(strict_types=1);

namespace StephBug\ServiceBus\Bus;

use Prooph\ServiceBus\Plugin\Plugin;

trait NamedMessageBusTrait
{
    /**
     * @var string
     */
    private $busName;

    /**
     * @var string
     */
    private $busType;

    /**
     * @var string[]
     */
    private $plugins = [];

    public function setBusName(string $busName): void
    {
        $this->busName = $busName;
    }

    public function busName(): string
    {
        return $this->busName;
    }

    public function setBusType(string $busType): void
    {
        $this->busType = $busType;
    }

    public function busType(): string
    {
        return $this->busType;
    }

    public function plugins(): array
    {
        return $this->plugins;
    }

    public function addPlugin(Plugin $plugin, string $serviceId = null): void
    {
        $plugin->attachToMessageBus($this);

        $this->plugins[] = ['plugin' => $plugin, 'service_id' => $serviceId];
    }

    public function removePlugin(Plugin $toRemove, string $serviceId = null): void
    {
        $toRemove->detachFromMessageBus($this);

        // checkMe exception if failed ?
        foreach ($this->plugins as $plugin) {
            if ($serviceId) {
                if ($plugin['service_id'] === $serviceId) {
                    unset($plugin['service_id']);
                }
            } else {
                if ($plugin['plugin'] === $toRemove) {
                    unset($plugin['plugin']);
                }
            }
        }
    }
}