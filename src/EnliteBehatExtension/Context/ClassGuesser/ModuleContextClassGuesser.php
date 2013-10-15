<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteBehatExtension\Context\ClassGuesser;


use Behat\Behat\Context\ClassGuesser\ClassGuesserInterface;

class ModuleContextClassGuesser implements ClassGuesserInterface
{

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * Class suffix
     *
     * @param string $suffix
     */
    public function __construct($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function guess()
    {
        if (class_exists($class = $this->namespace . '\\' . $this->suffix)) {
            return $class;
        }

        return null;
    }
}