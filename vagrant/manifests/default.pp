exec { "apt-get update":
  path => "/usr/bin",
}

class { 'php':
  service_autorestart => false,
  module_prefix       => '',
}

class { 'composer':
  require => Package['php5', 'curl'],
}