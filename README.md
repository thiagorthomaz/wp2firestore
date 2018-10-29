# WP2FS

#### Send your Wordpress post blog to your Firestore database

My goal here is to create a middleware to publish my wordpress posts into a Firestore database that I'm using on an Ionic mobile project.


## Dependencies

### PHP
sudo apt-get install php-dev php-pear phpunit


### Others
sudo apt-get install g++ libz-dev libz-dev
sudo pecl install grpc


## PHP.INI

In your php.ini file located in /etc/php/7.2/apache2/php.ini and /etc/php/7.2/cli/php.ini

Append the following line:

extension=grpc.so


## Composer

composer require "grpc/grpc:^v1.1.0"
composer require google/cloud-firestore
composer require kreait/firebase-php ^4.16.0

## Configuration

Write your database configurations in .env file
Run: php artisian migrate

Create a firestore_credentials.json in your root folder and paste your google credential.

Now just edit your .env file to include your credentials.
GOOGLE_APPLICATION_CREDENTIALS=/var/www/html/wp2firestore/firestore_credentials.json






