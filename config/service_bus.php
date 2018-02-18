<?php

return [

    'buses' => [

        'command' => [

            'default' => [
                'message_context' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
                'emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
                'plugins' => [

                ],
                'router' => [
                    'concrete' => \Prooph\ServiceBus\Plugin\Router\CommandRouter::class,
                    'routes' => []
                ]
            ]
        ],

        'event' => [

            'default' => [
                'message_context' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
                'emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
                'plugins' => [

                ],
                'router' => [
                    'concrete' => \Prooph\ServiceBus\Plugin\Router\EventRouter::class,
                    'routes' => []
                ]
            ]
        ],

        'query' => [

            'default' => [
                'message_context' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
                'emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
                'plugins' => [

                ],
                'router' => [
                    'concrete' => \Prooph\ServiceBus\Plugin\Router\QueryRouter::class,
                    'routes' => []
                ]
            ]
        ],
    ]
];