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
"required": {
    "enlitepro/enlite-behat-extension": "1.0.*"
}
```

and add in your `behat.yml`

```yaml
default:
  extensions:
    EnliteBehatExtension\Zf2Extension:
      module: Application
      config: config/application.config.php
```

Credits
=======

Inspired by [Symfony2Extension](https://github.com/Behat/Symfony2Extension)