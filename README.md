ZF2 Behat extension [![Build Status](https://travis-ci.org/enlitepro/enlite-behat-extension.png)](https://travis-ci.org/enlitepro/enlite-behat-extension)
===================

Provides integration layer for Zend Framework 2:

 * Integration into Zend Framework module structure - you can run an isolated module suite by name, classname and even
   full path

 * `ApplicationAwareInterface`, which provides booted application for your context

 * `Zend\ServiceManager\ServiceLocatorAwareInterface`, which provides service locator for your context


Installation
============

Add yo your `composer.json`

```json
{
    "require": {
        "enlitepro/enlite-behat-extension": "1.0.*"
    }
}
```

and add in your `behat.yml`

```yaml
default:
  extensions:
    EnliteBehatExtension\Zf2Extension: ~
      # module: moduleName
      # config: path_to_application.config.php
      # environment: testing
```

Usage
=====

```bash
# create directory structure and context file in module Application
behat --init "@Application"

# You can use short module notation to run features
behat "@Application"
```

There are two interface, which you can implement for your context

1. `EnliteBehatExtension\Context\ApplicationAwareInterface` - inject `Zend\Mvc\Application`. Your can use trait
   `EnliteBehatExtension\Context\ApplicationAwareTrait` to implement required methods

2. `Zend\ServiceManager\ServiceLocatorAwareInterface` - inject service manager.


Credits
=======

Inspired by [Symfony2Extension](https://github.com/Behat/Symfony2Extension)