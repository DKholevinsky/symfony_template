<?php

declare(strict_types=1);

namespace App\City;

class CityDto
{
    private function __construct(
        public readonly string $id,
        public readonly string $name,
    )
    {
    }

    public static function fromArray(array $data): CityDto
    {
        return new self($data['id'], $data['name']);
    }
}