# /manifests/site.pp
exec {"apt-get update":
  path => "/usr/bin",
}

package { 'apache2':
  require => Exec['apt-get update'],
  ensure => installed,
}

service { 'apache2':
  ensure => running,
}

exec { "/usr/sbin/a2enmod rewrite" :
  unless => "/bin/readlink -e /etc/apache2/mods-enabled/rewrite.load",
}

file {"/var/www":
  ensure => "link",
  target => "/vagrant",
  replace => yes,
  force => true,
}

file { "/etc/apache2/sites-available/sudoku.conf":
  ensure => "link",
  target => "/vagrant/environments/sudoku/manifests/assets/vhost.conf",
  require => Package["apache2"],
  notify => Service["apache2"],
  replace => yes,
  force => true,
}

exec { "/usr/sbin/a2ensite sudoku":
  require => Package['apache2'],
}

exec { "/usr/sbin/a2dissite 000-default.conf":
  require => Package['apache2'],
}

exec { "ApacheUserChange" :
  command => "/bin/sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars",
  onlyif  => "/bin/grep -c 'APACHE_RUN_USER=www-data' /etc/apache2/envvars",
  require => Package["apache2"],
  notify  => Service["apache2"],
}

exec { "ApacheGroupChange" :
  command => "/bin/sed -i 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars",
  onlyif  => "/bin/grep -c 'APACHE_RUN_GROUP=www-data' /etc/apache2/envvars",
  require => Package["apache2"],
  notify  => Service["apache2"],
}
