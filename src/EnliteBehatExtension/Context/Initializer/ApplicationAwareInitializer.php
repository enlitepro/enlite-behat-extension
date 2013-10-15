<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Context\Initializer;


use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use EnliteBehatExtension\Context\ApplicationAwareInitializerInterface;
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
        if ($context instanceof ApplicationAwareInitializerInterface) {
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
        if ($context instanceof ApplicationAwareInitializerInterface) {
            $context->setApplication($this->application);
        }

        if ($context instanceof ServiceLocatorAwareInterface) {
            $context->setServiceLocator($this->application->getServiceManager());
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
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