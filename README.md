# PhpBoot Example

This example shows how to use the PhpBoot framework.


## Download the code

Download the phpboot-example code to the specified directory by git clone or by downloading the zip package.


## installation

Execute the following command in the phpboot-example directory to install the dependencies.


```
curl -s http://getcomposer.org/installer | php
composer install
```

## Webserver Configuration

Please [refer](https://caoym.gitbooks.io/phpboot/content/kuai-su-kai-shi/webserver-pei-zhi.html) to the configuration document to configure Webserver

## Initialize the database

Run 'init.mysql.sql' to initialize the test database.



## Change setting

Modify config.php database configuration

## test

Visit http: //your-host/docs/swagger.json , if you can access normally, go to the address http://petstore.swagger.io/?url=http://your-host/docs/swagger.json, the interface for testing.

