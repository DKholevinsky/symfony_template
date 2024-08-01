<?php declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference;

class PromiseCollection
{
    private array $promises = [];

    public function remember(ResourceReference $placeholder): Promise
    {
        $promise = new Promise();
        $this->promises[] = [$promise, $placeholder];

        return $promise;
    }

    public function release(): array
    {
        [$promises, $this->promises] = [$this->promises, []];

        return $promises;
    }
}
