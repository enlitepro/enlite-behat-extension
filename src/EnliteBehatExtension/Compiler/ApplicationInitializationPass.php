<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Compiler;

use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zend\Loader\StandardAutoloader;

class ApplicationInitializationPass implements CompilerPassInterface
{

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        $applicationPath = $container->getParameter('behat.paths.base');
        $configPath = $container->getParameter("behat.zf2_extension.config");
        $configPath = $applicationPath . DIRECTORY_SEPARATOR . $configPath;

        if (!file_exists($configPath) || !is_file($configPath) || !is_readable($configPath)) {
            return;
        }

        if (false === ($config = @include $configPath)) {
            return;
        }

        $container->setParameter("behat.zf2_extension.config_data", $config);

        /** @var \EnliteBehatExtension\ModuleManager $manager */
        $manager = $container->get("behat.zf2_extension.modulemanager");

        if ($module = $container->getParameter('behat.zf2_extension.module')) {
            $path = $manager->getModulePath($module);

            if ($path) {
                $path .= DIRECTORY_SEPARATOR . $container->getParameter("behat.zf2_extension.context.path_suffix");
                $container->setParameter("behat.paths.features", $path);
            }
        }
    }
}