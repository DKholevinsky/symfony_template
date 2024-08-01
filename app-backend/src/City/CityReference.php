<?php

declare(strict_types=1);

namespace App\City;

use App\Infrastructure\Response\ResourceReference\ResourceReference;

class CityReference implements ResourceReference
{

    public function __construct(
        public readonly string $id,
    )
    {
    }
}