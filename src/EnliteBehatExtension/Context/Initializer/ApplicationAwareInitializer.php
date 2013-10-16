<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Context\Initializer;


use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use EnliteBehatExtension\Context\ApplicationAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class ApplicationAwareInitializer implements InitializerInterface, EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public function supports(ContextInterface $context)
    {
        if ($context instanceof ApplicationAwareInterface) {
            return true;
        }

        if ($context instanceof ServiceLocatorAwareInterface) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ContextInterface $context)
    {
        if ($context instanceof ApplicationAwareInterface) {
            $context->setApplication($this->application);
        }

        if ($context instanceof ServiceLocatorAwareInterface) {
            $context->setServiceLocator($this->application->getServiceManager());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
//            'beforeScenario'       => array('bootKernel', 15),
//            'beforeOutlineExample' => array('bootKernel', 15),
//            'afterScenario'        => array('shutdownKernel', -15),
//            'afterOutlineExample'  => array('shutdownKernel', -15)
        );
    }
}