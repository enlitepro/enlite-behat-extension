<?php

$config = array(
    'modules' => array(
        'Application'
      ),

    'module_listener_options' => array(
        'module_paths' => array(
            './testapp/module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);

return $config;
