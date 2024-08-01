<?php

declare(strict_types=1);

namespace App\Room;

class RoomDto
{

    private function __construct(
        public readonly string $id,
        public readonly string $name,
    )
    {
    }

    public static function fromArray(array $data): RoomDto
    {
        return new self($data['id'], $data['name']);
    }
}