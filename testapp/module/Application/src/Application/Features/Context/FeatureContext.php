<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Application\Features\Context;


use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use EnliteBehatExtension\Context\ApplicationAwareInterface;
use EnliteBehatExtension\Context\ApplicationAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext implements
    ApplicationAwareInterface,
    ServiceLocatorAwareInterface
{
    use ApplicationAwareTrait,
        ServiceLocatorAwareTrait;

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

}