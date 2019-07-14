<?php
/**
 * Created by PhpStorm.
 * User: januar
 * Date: 8/20/18
 * Time: 5:06 PM
 */

namespace Januar\LaravelOutside\Console;


use Illuminate\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    const NAME = 'Laravel Outside - by Januar Sirait';

    /**
     * @var string
     */
    const VERSION = '0.9.1';

    /**
     * @var string
     */
    protected $laravelVersion;

    /**
     * @var array
    */
    protected $config;

    /**
     * @param \Januar\LaravelOutside\Application $app
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
     * @param \Januar\LaravelOutside\Application $container
     */
    private function loadMigrations(\Januar\LaravelOutside\Application $container)
    {
        $files = glob($container->databasePath() . '/migrations/*.php');
        foreach ($files as $file) {
            require_once $file;
        }
    }

    private function resolveDbPath(\Januar\LaravelOutside\Application $container){
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