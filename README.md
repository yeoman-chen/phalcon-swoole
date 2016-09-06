phalcon-swoole
==============

* Phalcon 2.0.13 with Swoole Http_Server

## requirements
* PHP 5.5+
* Phalcon 2.0.13
* Swoole 1.7.16
* Linux and Windows support (Thinks to cygwin)

## Installation Phalcon
	```
	git clone --depth=1 git://github.com/phalcon/cphalcon.git
	cd cphalcon/build
	sudo ./install
	extension=phalcon.so
	```

## Installation Swoole

1.Install via pecl
	```
	pecl install swoole
	```
2.Install from source
	```
	sudo apt-get install php5-dev
	git clone https://github.com/swoole/swoole-src.git
	cd swoole-src
	phpize
	./configure --with-php-config=/usr/local/php/bin/php-config
	make && make install
	extension=swoole.so
	```
	
## How to run
	```
	/usr/local/php/bin/php /mnt/hgfs/www/phalcon-swoole/public/server.php
	Open your browser and enter http://ip:9501
	```
## run with php-fpm
1. Set nginx root dir with phalcon-swoole/public/
2. Open browser and enter http://ip/index.php

## Stress testing
1. Centos 7.0 , CPU 2 Core ,Memory 2 GB
2. php-fpm : pm = dynamic ,pm.max_children = 100,pm.max_requests = 1000
3. Swoole_Http_Server : worker_num = 16,max_request = 10000
4. ab -c 100 -n 10000 url

## php-fpm
	```
	ab -c 100 -n 10000 http://ylo.phalcon-swoole.com/

	Server Software:        nginx/1.6.0
	Server Hostname:        ylo.phalcon-swoole.com
	Server Port:            80

	Document Path:          /
	Document Length:        50 bytes

	Concurrency Level:      100
	Time taken for tests:   24.502 seconds
	Complete requests:      10000
	Failed requests:        0
	Write errors:           0
	Total transferred:      2120000 bytes
	HTML transferred:       500000 bytes
	Requests per second:    408.13 [#/sec] (mean)
	Time per request:       245.018 [ms] (mean)
	Time per request:       2.450 [ms] (mean, across all concurrent requests)
	Transfer rate:          84.50 [Kbytes/sec] received

	Connection Times (ms)
				  min  mean[+/-sd] median   max
	Connect:        0    1   1.1      0       9
	Processing:    19  243  35.1    239     559
	Waiting:       19  242  35.1    239     557
	Total:         28  244  34.7    240     563

	Percentage of the requests served within a certain time (ms)
	  50%    240
	  66%    245
	  75%    250
	  80%    255
	  90%    273
	  95%    306
	  98%    352
	  99%    379
	 100%    563 (longest request)

	```
	
## swoole_http_server
	```
	ab -c 100 -n 10000 http://127.0.0.1:9501/

	Server Software:        swoole-http-server
	Server Hostname:        127.0.0.1
	Server Port:            9501

	Document Path:          /
	Document Length:        15 bytes

	Concurrency Level:      100
	Time taken for tests:   6.914 seconds
	Complete requests:      10000
	Failed requests:        0
	Write errors:           0
	Total transferred:      1630000 bytes
	HTML transferred:       150000 bytes
	Requests per second:    1446.27 [#/sec] (mean)
	Time per request:       69.143 [ms] (mean)
	Time per request:       0.691 [ms] (mean, across all concurrent requests)
	Transfer rate:          230.22 [Kbytes/sec] received

	Connection Times (ms)
				  min  mean[+/-sd] median   max
	Connect:        0    2   1.9      1      14
	Processing:     1   66  34.9     73     252
	Waiting:        1   64  34.8     71     250
	Total:          2   68  34.7     75     252

	Percentage of the requests served within a certain time (ms)
	  50%     75
	  66%     85
	  75%     91
	  80%     95
	  90%    105
	  95%    117
	  98%    153
	  99%    167
	 100%    252 (longest request)

	```
## How URL request
  ```
  URL request demo : http://127.0.0.1:9501/front/index/test
  
  ```
