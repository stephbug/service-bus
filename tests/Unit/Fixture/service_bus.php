<?php

return [

    'buses' => [

        'command' => [

            'default' => [
                'message_context' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
                'emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
                'plugins' => [
                    \StephBugTest\ServiceBus\Unit\Mock\TestBusPlugin::class
                ],
                'router' => [
                    'concrete' => \Prooph\ServiceBus\Plugin\Router\CommandRouter::class,
                    'routes' => [
                        \StephBugTest\ServiceBus\Unit\Mock\TestSomeCommand::class => \StephBugTest\ServiceBus\Unit\Mock\TestMessageHandler::class,

                    ]
                ]
            ],

            'another' => [
                'message_context' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
                'emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
                'plugins' => [

                ],
                'router' => [
                    'concrete' => \Prooph\ServiceBus\Plugin\Router\CommandRouter::class,
                    'routes' => [
                        \StephBugTest\ServiceBus\Unit\Mock\TestSomeCommand::class => \StephBugTest\ServiceBus\Unit\Mock\TestMessageHandler::class,

                    ]
                ]
            ]
        ],
    ]
];