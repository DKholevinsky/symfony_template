<?php

declare(strict_types=1);

namespace App\Controller;

use App\Home\HomeDto;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class ExampleController
{
    #[Route(path: "/example")]
    public function example(): HomeDto
    {
        return HomeDto::create('123');
    }
}