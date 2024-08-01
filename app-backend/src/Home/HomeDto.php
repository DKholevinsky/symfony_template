<?php

declare(strict_types=1);

namespace App\Home;

use App\City\CityReference;
use App\Room\HomeRoomsReference;

class HomeDto
{
    private function __construct(
        public readonly string             $id,
        public readonly HomeRoomsReference $rooms,
        public readonly CityReference      $city,
    )
    {
    }

    public static function create(string $id): HomeDto
    {
        return new self($id, new HomeRoomsReference($id), new CityReference('1'));
    }
}