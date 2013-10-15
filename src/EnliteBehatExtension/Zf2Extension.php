<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension;


use Behat\Behat\Extension\Extension;
use EnliteBehatExtension\Compiler\ApplicationInitializationPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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

        if (isset($config['environment'])) {
            if (!defined('APPLICATION_ENV')) {
                define('APPLICATION_ENV', $config['environment']);
            }

            $container->setParameter('behat.zf2_extension.config', $config['config']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder->children()->
            scalarNode('module')->
                defaultNull()->
            end()->
            scalarNode('config')->
                defaultNull()->
            end()->
            scalarNode('environment')->
                defaultNull()->
            end()->
        end();
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
