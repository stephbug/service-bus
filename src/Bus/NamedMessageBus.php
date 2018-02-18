<?php

declare(strict_types=1);

namespace StephBug\ServiceBus\Bus;

use Prooph\ServiceBus\Plugin\Plugin;

interface NamedMessageBus
{
    public function setBusName(string $busName): void;

    public function busName(): string;

    public function setBusType(string $busType): void;

    public function busType(): string;

    public function addPlugin(Plugin $plugin, string $serviceId = null): void;

    public function plugins(): array;
}