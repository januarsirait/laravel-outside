<?php
/**
 * Created by PhpStorm.
 * User: januar
 * Date: 9/6/18
 * Time: 5:26 PM
 */

namespace LaravelOutside;

use Dotenv\Dotenv;
use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use LaravelOutside\Console\Application as Js;
use LaravelOutside\Exception\AppException;
use LaravelOutside\Exception\ConfigNotExistsException;
use LaravelOutside\Provider\ConsoleServiceProvider;


class Kernel
{
    /**
     * @var \LaravelOutside\Console\Application
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
     * @var
    */
    protected $appPath;

    /**
     * @param string $basePath
    */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->appPath = __DIR__;
    }

    /**
     * Run the console application.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function handle($input, $output = null){
        $this->init();
        return $this->getJs()->run($input, $output);
    }

    /**
     * Initialization application for global using
    */
    public function init(){
        try {
            $this->loadConfig();
            $this->bootstrap();
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
        if (file_exists($this->basePath . '/.env')){
            (Dotenv::create($this->basePath))->load();
        }else{
            throw (new ConfigNotExistsException($this->basePath . '/.env'));
        }

        $config = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR . 'config/config.php';
        if (file_exists($config)){
            $this->config = require $config;
        }else{
            throw (new ConfigNotExistsException($config));
        }
    }

    protected function initApp(){
        if (interface_exists('Illuminate\Contracts\Foundation\Application')) {
            $this->app = new Application($this->basePath);
            $this->app['path.database'] = $this->config['database']['path'];
        }else{
            die("This package need laravel package. Please install through composer." . PHP_EOL.
                'See http://getcomposer.org/download/'.PHP_EOL);
        }

        $this->app->instance('config', $config = new Repository($this->config));
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
    }

    public function bootstrap(){
        $this->initApp();
        $this->app->setDeferredServices([
                'database' => '\Illuminate\Database\DatabaseServiceProvider',
                'migration' => '\Illuminate\Database\MigrationServiceProvider'
            ]
        );

        $this->app->boot();
        $this->app->loadDeferredProviders();
        $this->app->register(new ConsoleServiceProvider($this->app));

        Facade::setFacadeApplication($this->app);
    }
}