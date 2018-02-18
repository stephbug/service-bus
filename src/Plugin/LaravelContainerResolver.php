<?php

declare(strict_types=1);

namespace StephBugDev\ServiceBus\Plugin;

use Illuminate\Contracts\Foundation\Application;
use Prooph\Common\Event\ActionEvent;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\Plugin\AbstractPlugin;

class LaravelContainerResolver extends AbstractPlugin
{
    /**
     * @var Application
     */
    private $app;

    /**
     * LaravelContainerResolver constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function attachToMessageBus(MessageBus $messageBus): void
    {
        $this->listenerHandlers[] = $messageBus->attach(
            MessageBus::EVENT_DISPATCH,
            function (ActionEvent $actionEvent): void {
                $messageHandlerAlias = $actionEvent->getParam(MessageBus::EVENT_PARAM_MESSAGE_HANDLER);

                if (is_string($messageHandlerAlias)) {
                    $actionEvent->setParam(MessageBus::EVENT_PARAM_MESSAGE_HANDLER, $this->app->make($messageHandlerAlias));
                }

                // for event bus only
                $currentEventListeners = $actionEvent->getParam(EventBus::EVENT_PARAM_EVENT_LISTENERS, []);

                $newEventListeners = [];

                foreach ($currentEventListeners as $key => $eventListenerAlias) {
                    if (is_string($eventListenerAlias)) {
                        $newEventListeners[$key] = $this->app->make($eventListenerAlias);
                    }
                }

                $actionEvent->setParam(EventBus::EVENT_PARAM_EVENT_LISTENERS, $newEventListeners + $currentEventListeners);
            },
            MessageBus::PRIORITY_LOCATE_HANDLER
        );
    }
}