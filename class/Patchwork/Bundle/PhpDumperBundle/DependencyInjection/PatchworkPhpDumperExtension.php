<?php

namespace Patchwork\Bundle\PhpDumperBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PatchworkPhpDumperExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->getDefinition('patchwork.dumper')
            ->setProperty('maxLength',         $config['max_length'])
            ->setProperty('maxDepth',          $config['max_depth'])
            ->setProperty('maxString',         $config['max_string'])
            ->setProperty('checkInternalRefs', $config['check_internal_refs']);

        $container->getDefinition('patchwork.dumper.cli')
            ->setProperty('maxStringWidth', $config['max_string_width']);
            ->setProperty('colors',         $config['colors']);
    }
}
