<?php declare(strict_types=1);

namespace App\Infrastructure\Response\ResourceReference;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CollectResourceProvidersCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $composer = $container->getDefinition(ResponseComposer::class);
        $taggedServices = $container->findTaggedServiceIds('resource_provider');
        foreach ($taggedServices as $serviceId => $tags) {
            $definition = $container->getDefinition($serviceId);
            foreach ($this->supportedResourceTypes($definition) as $resourceType => $method) {
                $composer->addMethodCall('registerProvider', [
                    $resourceType,
                    [new Reference($serviceId), $method],
                ]);
            }
        }
    }

    private function supportedResourceTypes(Definition $definition)
    {
        $className = $definition->getClass();
        if (!is_string($className)) {
            return;
        }

        /** @psalm-suppress ArgumentTypeCoercion */
        $reflection = new \ReflectionClass($className);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($publicMethods as $method) {
            $params = $method->getParameters();
            if (1 !== count($params) || !$params[0]->isVariadic() || !$params[0]->getType()) {
                continue;
            }

            /** @psalm-suppress PossiblyNullReference */
            /** @psalm-suppress UndefinedMethod */
            $resourceType = $params[0]->getType()->getName();
            if (is_a($resourceType, ResourceReference::class, true)) {
                yield $resourceType => $method->getName();
            }
        }
    }
}
