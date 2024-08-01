<?php declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference;

class ReferenceLink
{
    private \Closure $transformer;
    private \Closure $defaultValue;

    public function __construct(private readonly iterable $references,  private readonly iterable $referencedData)
    {
        /*
         * @psalm-suppress MissingClosureReturnType
         * @psalm-suppress MissingClosureParamType
         */
        $this->transformer = fn($data) => $data;
        $this->defaultValue = fn() => null;
    }

    public function withTransformation(\Closure $transformer): ReferenceLink
    {
        $copy = new self($this->references, $this->referencedData);
        $copy->defaultValue = $this->defaultValue;
        $copy->transformer = $transformer;

        return $copy;
    }

    public function withDefaultValue(\Closure $defaultValue): ReferenceLink
    {
        $copy = new self($this->references, $this->referencedData);
        $copy->transformer = $this->transformer;
        $copy->defaultValue = $defaultValue;

        return $copy;
    }

    public function oneToOne(\Closure $referenceKey, \Closure $referenceDataKey): array
    {
        $map = [];
        foreach ($this->referencedData as $referencedData) {
            $map[$referenceDataKey($referencedData)] = $referencedData;
        }

        return $this->map($referenceKey, $map);
    }

    public function oneToMany(\Closure $referenceKey, \Closure $referenceDataKey): array
    {
        $map = [];
        foreach ($this->referencedData as $referencedData) {
            $map[$referenceDataKey($referencedData)][] = $referencedData;
        }
        foreach ($this->references as $reference) {
            $key = $referenceKey($reference);
            if (!array_key_exists($key, $map)) {
                $map[$key] = [];
            }
        }

        return $this->map($referenceKey, $map);
    }

    private function map(\Closure $referenceKey, array $referencedDataMap):array
    {
        $transformer = $this->transformer;
        $result = [];
        foreach ($this->references as $reference) {
            $key = $referenceKey($reference);

            $result[] = array_key_exists($key, $referencedDataMap)
                ? $transformer($referencedDataMap[$key], $reference)
                : ($this->defaultValue)($reference);
        }

        return $result;
    }
}
