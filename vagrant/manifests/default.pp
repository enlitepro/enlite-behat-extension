Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }
File { owner => 0, group => 0, mode => 0644 }

exec { "apt-get update":
  path => "/usr/bin",
}

package { [
    'build-essential',
    'curl',
    'git-core'
  ]:
  ensure  => 'installed',
}

class { 'php':
  service_autorestart => false,
  module_prefix       => '',
}

php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }

class { 'composer':
  require => Package['php5', 'curl'],
}