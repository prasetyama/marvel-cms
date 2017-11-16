<?php

namespace Marvel\CoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class MarvelCoreExtension extends Extension{
    public function load(array $configs, ContainerBuilder $container){
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('marvel.core.upload_logo_developer', $config['upload']['logoDeveloper']);
        $container->setParameter('marvel.core.upload_logo_company', $config['upload']['logoCompany']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
?>
