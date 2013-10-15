<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension;


use Behat\Behat\Extension\Extension;
use EnliteBehatExtension\Compiler\ApplicationInitializationPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class Zf2Extension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/services'));
        $loader->load('core.xml');

        if (isset($config['module'])) {
            $container->setParameter('behat.zf2_extension.module', $config['module']);
        }

        if (isset($config['config'])) {
            $container->setParameter('behat.zf2_extension.config', $config['config']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPasses()
    {
        return array(
            new ApplicationInitializationPass()
        );
    }
}
