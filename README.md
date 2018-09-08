# wp2firestore

My goal here is to create a middleware to publish my wordpress posts into a Firestore database that I'm using on an Ionic mobile project



## Dependencies

sudo apt-get install php-dev php-pear phpunit

sudo apt-get install g++
sudo apt-get install libz-dev
sudo apt-get install libz-dev
sudo pecl install grpc

Add this line to your php.ini file, e.g. /etc/php/7.2/apache2/php.ini and /etc/php/7.2/cli/php.ini

extension=grpc.so

composer require "grpc/grpc:^v1.1.0"

composer require google/cloud-firestore

composer require kreait/firebase-php ^4.16.0

