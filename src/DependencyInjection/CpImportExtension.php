<?php

namespace BenTools\CpImportBundle\DependencyInjection;

use BenTools\CpImportBundle\CpImportFileWatcher;
use BenTools\CpImportBundle\CpImportProcessBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CpImportExtension extends Extension
{

    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $this->loadProcessBuilder($container, $config);
        $this->loadFileWatcher($container, $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    private function loadProcessBuilder(ContainerBuilder $container, array $config): void
    {
        $definition = $container->getDefinition(CpImportProcessBuilder::class);
        $definition->setArgument(0, $config['cpimport_bin']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    private function loadFileWatcher(ContainerBuilder $container, array $config): void
    {
        $definition = $container->getDefinition(CpImportFileWatcher::class);
        foreach ($config['watch'] as $directory => $params) {
            if (!is_dir($directory)) {
                throw new \LogicException(sprintf('%s is not a valid directory.', $directory));
            }
            $definition->addMethodCall('registerImportAction', [$directory, $params['database'], $params['table'], $params['options'] ?? []]);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAlias()
    {
        return 'cpimport';
    }
}
