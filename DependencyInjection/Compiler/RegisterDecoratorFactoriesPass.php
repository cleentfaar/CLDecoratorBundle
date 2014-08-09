<?php

namespace CL\Bundle\DecoratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterDecoratorFactoriesPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('cl_decorator.delegating_decorator_factory');

        foreach ($container->findTaggedServiceIds('cl_decorator.decorator_factory') as $id => $factories) {
            $definition->addMethodCall('registerFactory', array(new Reference($id)));
        }
    }
}
