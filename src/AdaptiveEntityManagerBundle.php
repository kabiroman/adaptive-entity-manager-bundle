<?php

namespace Kabiroman\AdaptiveEntityManagerBundle;

use Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection\Compiler\EntityManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AdaptiveEntityManagerBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new EntityManagerPass());
    }
}
