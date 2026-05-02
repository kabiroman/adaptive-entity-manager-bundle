<?php

declare(strict_types=1);

namespace Kabiroman\AdaptiveEntityManagerBundle\Tests\Kernel;

use Kabiroman\AdaptiveEntityManagerBundle\AdaptiveEntityManagerBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

final class AEMKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new AdaptiveEntityManagerBundle();
    }

    public function getProjectDir(): string
    {
        return __DIR__.'/FixtureApp';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $dir = $this->getProjectDir().'/config';
        $loader->load($dir.'/packages/framework.yaml');
        $loader->load($dir.'/packages/adaptive_entity_manager.yaml');
        $loader->load($dir.'/services.yaml');
    }
}
