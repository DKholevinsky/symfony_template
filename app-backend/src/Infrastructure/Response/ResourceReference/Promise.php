<?php declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference;

use App\Infrastructure\Response\ResourceReference\Exception\AttemptToSerializeUnresolvedPromise;
use App\Infrastructure\Response\ResourceReference\Exception\PromiseAlreadyResolved;

/**
 * @psalm-suppress MissingTemplateParam
 */
class Promise extends \ArrayObject implements \JsonSerializable
{
    private mixed $data;
    private bool $resolved = false;

    public function resolve($data): void
    {
        if ($this->resolved) {
            throw new PromiseAlreadyResolved();
        }

        $this->data = $data;
        $this->resolved = true;
    }

    public function jsonSerialize(): mixed
    {
        if (!$this->resolved) {
            throw new AttemptToSerializeUnresolvedPromise();
        }

        return $this->data;
    }
}
