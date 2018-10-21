<?php

namespace Laravelcity\FileManager;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravelcity\FileManager\Lib\Repository;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot (Router $router)
    {


        //migrations
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        // views
        $this->loadViewsFrom(__DIR__ . '/Views' , 'FileManager');

        //trans
        $this->loadTranslationsFrom(__DIR__ . '/Lang/' , 'FileManager');

        //bind
        $this->app->bind('FileManagerClass' , function () {
            return new Repository();
        });

        $config = $this->app['config']->get('filemanager.route' , []);
        $config['namespace'] = 'Laravelcity\FileManager';

        $router->group($config , function ($router) {
            $router->get('index/{type}' , 'Controller@index')->name('filemanager.index');
            $router->post('upload' , 'Controller@upload')->name('filemanager.upload');
            $router->any('index/list/{type}' , 'Controller@list')->name('filemanager.list');
            $router->post('index/update/{id}' , 'Controller@update')->name('filemanager.update');
            $router->any('index/delete/{id}' , 'Controller@delete')->name('filemanager.delete');
            $router->any('index/actions/send' , 'Controller@actions')->name('filemanager.actions');
            $router->any('index/post/search' , 'Controller@searchPost')->name('filemanager.searchpost');
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register ()
    {
        //configs
        $this->mergeConfigFrom(
            __DIR__ . '/Config/filemanager.php' , 'filemanager'
        );
        $this->publishes([
            __DIR__ . '/Config/filemanager.php' => config_path('filemanager.php') ,
        ] , 'filemanager');

        $this->publishes([
            __DIR__ . '/Lang/' => resource_path('lang/vendor/filemanager') ,
        ]);

        $this->publishes([
            __DIR__ . '/Views/assets/' => public_path('vendor/lydaweb/filemanager') ,
        ] , 'filemanager');
    }
}
