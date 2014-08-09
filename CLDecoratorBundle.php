<?php

namespace CL\Bundle\DecoratorBundle;

use CL\Bundle\DecoratorBundle\DependencyInjection\Compiler\RegisterDecoratorFactoriesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CLDecoratorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterDecoratorFactoriesPass());
    }
}
