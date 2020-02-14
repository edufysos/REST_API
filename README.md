# REST API Plugin

![screenshot](https://gitlab.com/francoisjacquet/REST_API/raw/master/screenshot.png?inline=false)

https://gitlab.com/francoisjacquet/REST_API/

Version 1.1 - August, 2017

Author François Jacquet

License MIT

## Description

This RosarioSIS plugin provides a REST API (Application Programming Interface).
Access and explore the API using the example client. Authentication is done with JWT (JSON Web Token).

It is based on the [PHP-CRUD-API](https://github.com/mevdschee/php-crud-api) package, see `README_API.md` for more info.
Please check your server meets the minimum requirements below.

Translated in [French](https://www.rosariosis.org/fr/rest-api-plugin/) & [Spanish](https://www.rosariosis.org/es/rest-api-plugin/).

## Content

Plugin Configuration
- API URL
- Authentication URL
- Example client
- User token

## Install

Copy the `REST_API/` folder (if named `REST_API-master`, rename it) and its content inside the `plugins/` folder of RosarioSIS.

Go to _School > Configuration > Plugins_ and click "Activate".

Edit your `config.in.php` file and add the following line (change the passphrase):
```php
define( 'ROSARIO_REST_API_SECRET', 'thisIsMyPassphraseChangeMe' );
```

Requires RosarioSIS 5.0+, PHP **7.0**+, PostgreSQL **9.1**+
