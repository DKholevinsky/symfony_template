<?php

declare(strict_types=1);

namespace App\City;

use App\Infrastructure\Response\ResourceReference\ReferenceLink;
use App\Room\HomeRoomsReference;

class CityReferenceResolver
{
    public function provide(CityReference ...$references): array
    {
        $ids = array_map(fn(CityReference $reference) => $reference->id, $references);

        //$result = $this->db->fetchAllAssociative(
        //    'SELECT id, name FROM cities WHERE id IN (:cityIds)',
        //    [
        //        'cityIds' => array_unique($ids),
        //    ],
        //    ['cityIds' => ArrayParameterType::STRING]
        //);

        $result = [
            ['id' => '1', 'name' => 'City 1'],
            ['id' => '2', 'name' => 'City 2']
        ];

        return (new ReferenceLink($references, $result))
            ->withTransformation(fn(array $row) => CityDto::fromArray($row))
            ->oneToOne(
                fn(CityReference $reference) => $reference->id,
                fn(array $row) => $row['id']
            )
            ;
    }
}