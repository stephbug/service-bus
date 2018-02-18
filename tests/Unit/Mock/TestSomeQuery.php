<?php

declare(strict_types=1);

namespace StephBugTest\ServiceBus\Unit\Mock;

use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\Common\Messaging\Query;

class TestSomeQuery extends Query implements PayloadConstructable
{
    use PayloadTrait;
}