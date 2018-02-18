<?php

declare(strict_types=1);

namespace StephBugDev\ServiceBus;

use Prooph\Common\Messaging\Message;

class ServiceBusDispatcher
{
    /**
     * @var ServiceBusManager
     */
    private $busManager;

    /**
     * ServiceBusDispatcher constructor.
     *
     * @param ServiceBusManager $busManager
     */
    public function __construct(ServiceBusManager $busManager)
    {
        $this->busManager = $busManager;
    }

    public function dispatch(Message $message, string $busName = null)
    {
        switch ($message->messageType()) {
            case 'command':
                $this->busManager->command($busName)->dispatch($message);
                break;

            case 'event':
                $this->busManager->event($busName)->dispatch($message);
                break;

            case 'query':
               return $this->busManager->query($busName)->dispatch($message);
        }
    }
}