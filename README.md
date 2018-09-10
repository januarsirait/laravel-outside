# laravel-outside
Package to use laravel command package from out of laravel framework. This package use fully illuminate/database package from laravel.

## Requirement
- PHP >=5.4

## Installation
### Install Composer

laravel-outside utilizes [Composer](http://getcomposer.org/) to manage its dependencies. First, download a copy of the `composer.phar`. Once you have the PHAR archive, you can either keep it in your local project directory or move to `usr/local/bin` to use it globally on your system. On Windows, you can use the Composer [Windows installer](https://getcomposer.org/Composer-Setup.exe).

### Install laravel-outside

The best way to install laravel-outside is quickly and easily with Composer.
To install the most recent version, run the following command.

```bash
composer require januar/laravel-outside
```

After that, commnad will be place in composer vendor folder. So, to run the command file will be like this

```bash
php vendor/bin/js
```

### Configuration
This package using .env file configuration. So, you must create .env file in root of project. After that, write following config to the .env file base on your configuration.

```env
DB_DEFAULT=mysql
DB_HOST=localhost
DB_DATABASE=laravel-outside
DB_USERNAME=
DB_PASSWORD=
DB_CHARSET=utf8
DB_COLLATION=utf8_unicode_ci
DB_PREFIX=''
DB_STRICT=false
DB_PATH=database
```

### Update composer.json

To using seeder functionality, you need to setup composer classmap in composer.json file. Autoload classmap refer to folder that seeder file is placed. You can follow this example:
````
"autoload":{
     "classmap":[
        "database/seeds"
     ]
}
````

After that, run composer dump-autoload

### Credits

All the credits for the laravel-outside goes to the Laravel Framework developers. We are only putting the pieces together here

### License

The laravel-outside is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
