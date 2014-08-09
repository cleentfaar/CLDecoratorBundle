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
        $definition = $container->getDefinition('cl_decorator.delegating_decorator');

        foreach ($container->findTaggedServiceIds('cl_decorator.decorator') as $id => $factories) {
            $definition->addMethodCall('registerDecorator', array(new Reference($id)));
        }
    }
}
