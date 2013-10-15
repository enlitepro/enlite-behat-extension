<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Console\Processor;

use Behat\Behat\Console\Processor\InitProcessor as BehatProcessor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InitProcessor extends BehatProcessor
{

    /**
     * @var ContainerInterface
     */
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
     * {@inheritdoc}
     */
    public function process(InputInterface $input, OutputInterface $output)
    {
        // throw exception if no features argument provided
        if (!$input->getArgument('features') && $input->getOption('init')) {
            throw new \InvalidArgumentException('Provide features argument in order to init suite.');
        }

        // initialize bundle structure and exit
        if ($input->getOption('init')) {
            $this->initDirectoryStructure($input, $output);

            exit(0);
        }
    }


    /**
     * Inits bundle directory structure
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initDirectoryStructure(InputInterface $input, OutputInterface $output)
    {
        $featuresPath = $input->getArgument('features');
        $modulePath = null;

        /** @var \EnliteBehatExtension\ModuleManager $manager */
        $manager = $this->container->get("behat.zf2_extension.modulemanager");

        // get bundle specified in behat.yml
        if ($moduleName = $this->container->getParameter('behat.zf2_extension.module')) {
            $modulePath = $manager->getModulePath($moduleName);
        }

        if ($featuresPath) {
            // get bundle from short notation if path starts from @
            if (preg_match('/^\@([^\/\\\\]+)(.*)$/', $featuresPath, $matches)) {
                $modulePath = $manager->getModulePath($matches[1]);
                // get bundle from provided features path
            } elseif (file_exists($featuresPath)) {
                $featuresPath = realpath($featuresPath);
                foreach ($manager->getModules() as $moduleName => $module) {
                    if (false !== strpos($featuresPath, realpath($manager->getModulePath($moduleName)))) {
                        $modulePath = $moduleName;
                        break;
                    }
                }
            }

        }

        if (null === $modulePath) {
            throw new \InvalidArgumentException('Can not find bundle to initialize suite.');
        }

        $featuresPath = $modulePath . DIRECTORY_SEPARATOR . 'Features';
        $basePath = $this->container->getParameter('behat.paths.base') . DIRECTORY_SEPARATOR;
        $contextPath = $featuresPath . DIRECTORY_SEPARATOR . 'Context';

        if (!is_dir($featuresPath)) {
            mkdir($featuresPath, 0777, true);
            $output->writeln(
                '<info>+d</info> ' .
                str_replace($basePath, '', realpath($featuresPath)) .
                ' <comment>- place your *.feature files here</comment>'
            );
        }

        if (!is_dir($contextPath)) {
            mkdir($contextPath, 0777, true);

            file_put_contents(
                $contextPath . DIRECTORY_SEPARATOR . 'FeatureContext.php',
                strtr(
                    $this->getFeatureContextSkelet(),
                    array(
                         '%NAMESPACE%' => $moduleName
                    )
                )
            );

            $output->writeln(
                '<info>+f</info> ' .
                str_replace($basePath, '', realpath($contextPath)) . DIRECTORY_SEPARATOR .
                'FeatureContext.php <comment>- place your feature related code here</comment>'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getFeatureContextSkelet()
    {
        return <<<'PHP'
<?php

namespace %NAMESPACE%\Features\Context;

use EnliteBehatExtension\Context\ApplicationAwareInterface,
    EnliteBehatExtension\Context\ApplicationAwareTrait;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorAwareTrait;

use Behat\MinkExtension\Context\MinkContext,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException,
    Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends BehatContext //MinkContext if you want to test web
    ApplicationAwareInterface,
    ServiceLocatorAwareInterface
{
    use ApplicationAwareTrait,
        ServiceLocatorAwareTrait;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        $container = $this->kernel->getContainer();
//        $container->get('some_service')->doSomethingWith($argument);
//    }
//
}

PHP;
    }
}