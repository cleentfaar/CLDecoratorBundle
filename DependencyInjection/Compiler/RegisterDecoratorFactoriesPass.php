<?php

namespace CL\Bundle\DecoratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterDecoratorFactoriesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('cl_decoration.delegating_decorator_factory');

        foreach ($container->findTaggedServiceIds('cl_decoration.decorator_factory') as $id => $factories) {
            foreach ($factories as $factory) {
                if (!isset($factory['class'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "class" attribute on "cl_decoration.decorator_factory" tags.', $id));
                }

                $definition->addMethodCall('registerFactory', array(new Reference($id), $factory['class']));
            }
        }
    }
}
