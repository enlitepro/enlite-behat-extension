<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Application\Features\Context;


use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use EnliteBehatExtension\Context\ApplicationAwareInterface;
use EnliteBehatExtension\Context\ApplicationAwareTrait;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext implements
    ApplicationAwareInterface,
    ServiceLocatorAwareInterface
{

    /**
     * @var Application
     */
    private $application;

    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

//    For PHP >=5.4
//    use ApplicationAwareTrait,
//        ServiceLocatorAwareTrait;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $parameterKey;

    /**
     * @Given /^I have an application instance$/
     */
    public function iHaveAnApplicationInstance()
    {
        assertInstanceOf('Zend\Mvc\Application', $this->getApplication());
    }

    /**
     * @When /^I get container parameters from it$/
     */
    public function iGetContainerParametersFromIt()
    {
        $this->config = $this->getServiceLocator()->get('config');
    }

    /**
     * @Then /^there should be "([^"]*)" parameter$/
     */
    public function thereShouldBeParameter($key)
    {
        assertArrayHasKey($key, $this->config);
        $this->parameterKey = $key;
    }

    /**
     * @Given /^it should be set to "([^"]*)" value$/
     */
    public function itShouldBeSetToValue($key)
    {
        assertSame($key, $this->config[$this->parameterKey]);
    }

    /**
     * @Given /^there should not be "([^"]*)" parameter$/
     */
    public function thereShouldNotBeParameter($key)
    {
        assertArrayNotHasKey($key, $this->config);
    }

    /**
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

}