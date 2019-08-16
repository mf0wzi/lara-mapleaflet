<?php namespace Noonenew\LaravelLeafLet\Providers;

use Noonenew\LaravelLeafLet\Builder;
use Illuminate\Support\ServiceProvider;

class LeafletServiceProvider extends ServiceProvider
{

    /**
     * Array with colours configuration of the chartjs config file
     * @var array
     */
    protected $colours = [];

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'map-template');
        $this->colours = config('mapleaflet.colours');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mapleaflet', function() {
            return new Builder();
        });
    }
}
