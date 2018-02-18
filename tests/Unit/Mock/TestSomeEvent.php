<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit\Mock;

use Prooph\Common\Messaging\DomainEvent;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

class TestSomeEvent extends DomainEvent implements PayloadConstructable
{
    use PayloadTrait;
}