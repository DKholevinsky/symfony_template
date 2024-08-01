<?php declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ResourceNormalizer implements NormalizerInterface
{
    public function __construct(
        private PromiseCollection $promises
    ) {}

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): Promise
    {
        return $this->promises->remember($object);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ResourceReference;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => null,
            ResourceReference::class => true,
        ];
    }
}
