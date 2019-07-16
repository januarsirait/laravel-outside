<?php
/**
 * Created by PhpStorm.
 * User: januar
 * Date: 8/20/18
 * Time: 5:06 PM
 */

namespace LaravelOutside\Console;


use Illuminate\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    const NAME = 'Laravel Outside - by Januar Sirait';

    /**
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * @var string
     */
    protected $laravelVersion;

    /**
     * @var array
    */
    protected $config;

    /**
     * @param \LaravelOutside\Application $app
    */
    public function __construct($app)
    {
        $this->config = $app->config();
        parent::__construct($app, $app['events'], self::VERSION);
        $this->setCatchExceptions(true);
        $this->setName(self::NAME);
        $this->resolveDbPath($app);
        $this->loadMigrations($app);
    }

    /**
     * @param \LaravelOutside\Application $container
     */
    private function loadMigrations(\LaravelOutside\Application $container)
    {
        $files = glob($container->databasePath() . '/migrations/*.php');
        foreach ($files as $file) {
            require_once $file;
        }
    }

    private function resolveDbPath(\LaravelOutside\Application $container){
        if (!$container['files']->isDirectory($container->databasePath())){
            $container['files']->makeDirectory($container->databasePath());
        }

        if (!$container['files']->isDirectory($container->databasePath() .'/migrations')){
            $container['files']->makeDirectory($container->databasePath() .'/migrations');
        }

        if (!$container['files']->isDirectory($container->databasePath() .'/seeds')){
            $container['files']->makeDirectory($container->databasePath() .'/seeds');
        }
    }
}