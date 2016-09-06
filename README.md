# phalcon-swoole
Phalcon 2.0.13 with Swoole Http_Server

# requirements
PHP 5.5+
Phalcon 2.0.13
Swoole 1.7.16
Linux and basic Windows support (Thinks to cygwin)

# Installation Swoole
Install via pecl
pecl install swoole

Install from source

sudo apt-get install php5-dev
git clone https://github.com/swoole/swoole-src.git
cd swoole-src
phpize
./configure --with-php-config=/usr/local/php/bin/php-config
make && make install

# How to run

# Stress testing
