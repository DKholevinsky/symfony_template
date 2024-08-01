<?php declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference\Exception;

use App\Infrastructure\Exception\InvalidArgumentException;

class PromiseAlreadyResolved extends InvalidArgumentException {}
