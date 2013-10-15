<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension;


use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\Application;

class ModuleManager
{

    /**
     * @var Application
     */
    protected $application;

    /**
     * @param Application $application
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * @param string $moduleName
     *
     * @return null|string
     */
    public function getModulePath($moduleName)
    {
        $modules = $this->getModules();

        if (isset($modules[$moduleName])) {
            $module = $modules[$moduleName];

            if ($module instanceof AutoloaderProviderInterface || method_exists($module, 'getAutoloaderConfig')) {
                $config = $module->getAutoloaderConfig();
                if (isset($config['Zend\Loader\StandardAutoloader']['namespaces'][$moduleName])) {
                    return $config['Zend\Loader\StandardAutoloader']['namespaces'][$moduleName];
                }
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getModules()
    {
        /** @var $moduleManager \Zend\ModuleManager\ModuleManager */
        $moduleManager = $this->application->getServiceManager()->get('ModuleManager');
        return $moduleManager->getLoadedModules();
    }
}