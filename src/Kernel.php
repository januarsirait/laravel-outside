<?php
/**
 * Created by PhpStorm.
 * User: januar
 * Date: 9/6/18
 * Time: 5:26 PM
 */

namespace Januar\LaravelOutside;

use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Januar\LaravelOutside\Console\Application as Js;
use Januar\LaravelOutside\Exception\AppException;
use Januar\LaravelOutside\Exception\ConfigNotExistsException;
use Januar\LaravelOutside\Provider\ConsoleServiceProvider;


class Kernel
{
    /**
     * @var \Januar\LaravelOutside\Console\Application
    */
    protected $js;

    /**
     * @var Application
    */
    protected $app;

    /**
     * @var array
    */
    protected $config;

    /**
     * @var string
    */
    protected $basePath;

    /**
     * @param string $basePath
    */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Run the console application.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    public function handle($input, $output = null){
        try {
            $this->loadConfig();
            $this->initApp();
            return $this->getJs()->run($input, $output);
        }catch (\Exception $e){
            if (is_subclass_of($e, AppException::class)){
                $e->handle();
            }else{
                echo($e->getMessage());
                echo($e->getTraceAsString());
            }
            return 1;
        }
    }

    /**
     * Get the Artisan application instance.
     *
     * @return \Illuminate\Console\Application
     */
    protected function getJs()
    {
        if (is_null($this->js)) {
            return $this->js = (new Js($this->app));
        }

        return $this->js;
    }

    protected function loadConfig(){
        $config = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR . 'config/config.php';
        if (file_exists($config)){
            $this->config = require $config;
        }else{
            throw (new ConfigNotExistsException($config));
        }
    }

    protected function initApp(){
        if (interface_exists('Illuminate\Contracts\Foundation\Application')) {
            $this->app = new Application();
            $this->app['config'] = $this->config;
            $this->app['path.database'] = $this->config['database']['path'];
        }else{
            die("This package need laravel package. Please install through composer." . PHP_EOL.
                'See http://getcomposer.org/download/'.PHP_EOL);
        }

        $this->app->singleton('events', function () {
            return new Dispatcher();
        });
        $this->app->singleton('files', function () {
            return new Filesystem();
        });

        $this->app->singleton('config', function (){
            return new Repository(['database' => $this->config['database']]);
        });

        $this->app->singleton('composer', function () {
            $composer = \Mockery::mock('\Illuminate\Support\Composer');

            $composer->shouldReceive('dumpAutoloads');

            return $composer;
        });

        $databaseServiceProvider = new DatabaseServiceProvider($this->app);
        $databaseServiceProvider->register();

        $migrationServiceProvider = new MigrationServiceProvider($this->app);
        $migrationServiceProvider->register();

        $this->app->register(new ConsoleServiceProvider($this->app));

    }
}