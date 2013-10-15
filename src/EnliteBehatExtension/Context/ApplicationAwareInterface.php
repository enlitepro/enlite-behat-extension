<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Context;


use Zend\Mvc\Application;

interface ApplicationAwareInterface
{

    public function setApplication(Application $application);

}