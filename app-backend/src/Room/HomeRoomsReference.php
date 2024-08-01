<?php

declare(strict_types=1);

namespace App\Room;

use App\Infrastructure\Response\ResourceReference\ResourceReference;

class HomeRoomsReference implements ResourceReference
{

    public function __construct(
        public readonly string $homeId,
    )
    {
    }
}