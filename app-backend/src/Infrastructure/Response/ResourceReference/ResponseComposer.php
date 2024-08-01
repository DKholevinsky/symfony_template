<?php declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference;

use App\Infrastructure\Response\ResourceReference\Exception\IncorrectNumberOfResolvedResources;
use App\Infrastructure\Response\ResourceReference\Exception\UnknownResourceType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ResponseComposer
{
    private array $providers = [];

    public function __construct(
        private readonly NormalizerInterface $normalizer,
        private readonly PromiseCollection $promises,
    ) {}

    public function registerProvider(string $resourceType, callable $provider): void
    {
        $this->providers[$resourceType] = \Closure::fromCallable($provider);
    }

    public function process($data): mixed
    {
        $result = $this->normalizer->normalize($data);
        $this->processResources();

        return $result;
    }

    private function processResources(): void
    {
        $promises = $this->promises->release();
        if (0 === count($promises)) {
            return;
        }

        $groupedPromises = $this->groupByResourceTypes($promises);
        foreach ($groupedPromises as $resourceType => $group) {
            $this->handleGroup($resourceType, $group);
        }

        $this->processResources();
    }

    private function handleGroup(string $resourceType, array $group): void
    {
        if (!isset($this->providers[$resourceType])) {
            throw new UnknownResourceType($resourceType);
        }
        $promises = array_column($group, 0);
        $resources = array_column($group, 1);
        $data = ($this->providers[$resourceType])(...$resources);
        if (count($data) !== count($resources)) {
            throw new IncorrectNumberOfResolvedResources($resourceType, count($resources), count($data));
        }

        foreach (array_values($data) as $i => $item) {
            /** @var Promise $promise */
            $promise = $promises[$i];
            $promise->resolve($this->normalizer->normalize($item));
        }
    }

    private function groupByResourceTypes(array $promises): array
    {
        $groups = [];
        foreach ($promises as [$promise, $resource]) {
            $groups[$resource::class][] = [$promise, $resource];
        }

        return $groups;
    }
}
