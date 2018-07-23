<?php

    /*
    |----------------------------------------------------------------------------------------------------
    |      __                               __   __  __________  _____       _______  ____________  ___    
    |     / /  ____ __________ __   _____  / /  / / / /_  __/  |/  / /      / ____| |/ /_  __/ __ \/   |
    |    / /  / __ `/ ___/ __ `| | / / _ \/ /  / /_/ / / / / /|_/ / /      / __/  |   / / / / /_/ / /| |
    |   / /__/ /_/ / /  / /_/ /| |/ /  __/ /  / __  / / / / /  / / /___   / /___ /   | / / / _, _/ ___ |
    |  /_____\__,_/_/   \__,_/ |___/\___/_/  /_/ /_/ /_/ /_/  /_/_____/  /_____//_/|_|/_/ /_/ |_/_/  |_|
    |----------------------------------------------------------------------------------------------------
    | Laravel HTML Extra - By Peter Keogan - Link:https://github.com/pkeogan/laravel-html-extra
    |----------------------------------------------------------------------------------------------------
    |   Title : Service Provider
    |   Desc  : This service provider injects the Form blade directives for views to be able to render the views.
    |   Useage: Please Refer to readme.md 
    | 
    |
    */


namespace Pkeogan\LaravelDatatables;

use Form;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class LaravelDatatablesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services and add all the directives. 
     * 
     *
     * @return void
     */
    public function boot()
    {
      //Publish Config File
        $this->publishes([
          __DIR__.'/laravel-datatables.php' => config_path('laravel-datatables.php'),
         ]);
      // adds our custom views to laravel can call them with adminlte::example.page
      view()->addNamespace('datatables', base_path('/vendor/pkeogan/laravel-datatables/src/views'));

        //yummy sauceee https://stackoverflow.com/questions/38135455/how-to-have-one-time-push-in-laravel-blade 
        // Lets us push scripts and styles only as componets are loaded. 
        Blade::directive('pushonce', function ($expression) {
            $domain = explode(':', trim(substr($expression, 1, -1)));
            $push_name = $domain[0];
            $push_sub = $domain[1];
            $isDisplayed = '__pushonce_'.$push_name.'_'.$push_sub;
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush('{$push_name}'); ?>";
        });
        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });

      
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('datatables', function ($app) {
            return new Datatables($app['view']);
        });
    }
}