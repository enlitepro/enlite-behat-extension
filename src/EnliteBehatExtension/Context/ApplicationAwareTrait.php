<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Context;


use Zend\Mvc\Application;

trait ApplicationAwareTrait
{

    /**
     * @var Application
     */
    private $application;

    /**
     * @param \Zend\Mvc\Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return \Zend\Mvc\Application
     */
    public function getApplication()
    {
        return $this->application;
    }

}