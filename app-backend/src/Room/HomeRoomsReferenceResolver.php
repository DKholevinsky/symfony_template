<?php

declare(strict_types=1);

namespace App\Room;

use App\Infrastructure\Response\ResourceReference\ReferenceLink;

class HomeRoomsReferenceResolver
{
    public function provide(HomeRoomsReference ...$references): array
    {
        $ids = array_map(fn(HomeRoomsReference $reference) => $reference->homeId, $references);

        //$result = $this->db->fetchAllAssociative(
        //    'SELECT id, home_id, name FROM rooms WHERE home_id IN (:homeIds)',
        //    [
        //        'homeIds' => array_unique($ids),
        //    ],
        //    ['productIds' => ArrayParameterType::STRING]
        //);

        $result = [
            ['id' => '1', 'home_id' => '123', 'name' => 'Name 1'],
            ['id' => '2', 'home_id' => '123', 'name' => 'Name 2']
        ];

        return (new ReferenceLink($references, $result))
            ->withTransformation(fn(array $row) => array_map(fn(array $item) => RoomDto::fromArray($item), $row))
            ->oneToMany(
                fn(HomeRoomsReference $reference) => $reference->homeId,
                fn(array $row) => $row['home_id']
            )
            ;
    }
}