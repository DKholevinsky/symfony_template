<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference\Exception;

class IncorrectNumberOfResolvedResources extends \LogicException
{
    public function __construct(
        string $resourceType,
        int $numberOfResource,
        int $numberOfResolvedResources
    ) {
        parent::__construct(
            sprintf(
                'Number of resolved resources of type "%s" does not match, expected %d, but got %d',
                $resourceType,
                $numberOfResource,
                $numberOfResolvedResources
            )
        );
    }
}
