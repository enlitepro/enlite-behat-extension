<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Context;


use Zend\Mvc\Application;

interface ApplicationAwareInitializerInterface
{

    public function setApplication(Application $application);

}