<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Console\Processor;

use Behat\Behat\Console\Processor\LocatorProcessor as BehatProcessor;
use EnliteBehatExtension\Context\ClassGuesser\ModuleContextClassGuesser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LocatorProcessor extends BehatProcessor
{
    private $container;

    /**
     * Constructs processor.
     *
     * @param ContainerInterface $container Container instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Configures command to be able to process it later.
     *
     * @param Command $command
     */
    public function configure(Command $command)
    {
        $command->addArgument(
            'features',
            InputArgument::OPTIONAL,
            "Feature(s) to run. Could be:" .
            "\n- a dir (<comment>src/to/Module/Features/</comment>), " .
            "\n- a feature (<comment>src/to/Module/Features/*.feature</comment>), " .
            "\n- a scenario at specific line (<comment>src/to/Bundle/Module/*.feature:10</comment>). " .
            "\n- Also, you can use short bundle notation (<comment>@Module/*.feature:10</comment>)"
        );
    }

    public function process(InputInterface $input, OutputInterface $output)
    {
        $featuresPath = $input->getArgument('features');
        $pathSuffix = $this->container->getParameter('behat.zf2_extension.context.path_suffix');
        $modulePath = null;

        /** @var \EnliteBehatExtension\ModuleManager $manager */
        $manager = $this->container->get("behat.zf2_extension.modulemanager");

        // get bundle specified in behat.yml
        if ($moduleName = $this->container->getParameter('behat.zf2_extension.module')) {
            $modulePath = $manager->getModulePath($moduleName);
        }

        // get bundle from short notation if path starts from @
        if ($featuresPath) {
            if (preg_match('/^\@([^\/\\\\]+)(.*)$/', $featuresPath, $matches)) {
                $moduleName = $matches[1];
                $modulePath = $manager->getModulePath($moduleName);

                $featuresPath = str_replace(
                    '@' . $moduleName,
                    $modulePath . DIRECTORY_SEPARATOR . $pathSuffix,
                    $featuresPath
                );

                // get bundle from provided features path
            } elseif (!$modulePath) {

                $path = realpath(preg_replace('/\.feature\:.*$/', '.feature', $featuresPath));

                foreach ($manager->getModules() as $moduleName => $module) {
                    if (false !== strpos($path, realpath($manager->getModulePath($moduleName)))) {
                        $modulePath = $moduleName;
                        break;
                    }
                }

                // if bundle is configured for profile and feature provided
            } elseif ($modulePath) {
                $featuresPath = $modulePath . DIRECTORY_SEPARATOR . $pathSuffix . DIRECTORY_SEPARATOR . $featuresPath;
            }
        }

        if ($modulePath) {
            /** @var ModuleContextClassGuesser $guesser */
            $guesser = $this->container->get('behat.zf2_extension.context.class_guesser');
            $guesser->setNamespace($moduleName);
        }

        if (!$featuresPath) {
            $featuresPath = $this->container->getParameter('behat.paths.features');
        }

        $this->container
            ->get('behat.console.command')
            ->setFeaturesPaths($featuresPath ? array($featuresPath) : array());

    }
}