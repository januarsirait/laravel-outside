# laravel-outside
Package to use laravel command from out of laravel framework. This package use fully illuminate/database package from laravel.

## Requirement
- PHP >= 7.1.3

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
DB_PATH is configuration to set where your seeder and migration file will be place.

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

### Finally, Elequent and Make Model Command Can Used Now

Laravel make:model command can used now. Available options is -m (migration) and -p (pivot). The documentation of this command, you can read from [Laravel Elequent documentation](https://laravel.com/docs/5.8/eloquent). 
We sugest to used psr-4 autoload, so your model will placed in autoload path. Example :

````
"autoload":{
    "psr-4": {
        "Your\\Application\\Package\\": "src/"
    },
    "classmap":[
        "database/seeds"
    ]
}
````

So, if you run create model command like this "php vendor/bin/js make:model Model/Example -m", 
model will placed in your_application_path/src/Model and migration file will placed in your_application_path/[DB_PATH].

##### Using Eloquent

To use Eloquent in your application, just init laravel application with:
````
require 'vendor/autoload.php';

$kernel = new \LaravelOutside\Kernel(realpath(__DIR__));
$kernel->init();
````

You need pass base path as param to \LaravelOutside\Kernel. Base path is your
root application path.

### Credits

All the credits for the laravel-outside goes to the Laravel Framework developers. We are only putting the pieces together here

### License

The laravel-outside is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
