<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit\Mock;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

class TestSomeCommand extends Command implements PayloadConstructable
{
    use PayloadTrait;
}