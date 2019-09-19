<?php
namespace unniks\TwitterPowertracker;
use Illuminate\Support\ServiceProvider;
class PowerTrackerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/power-tracker-config.php' => config_path('power-tracker-config.php'),
            ], 'config');
            $this->publishes([
                __DIR__.'/../example/TwitterPowerTrackerStream.php' => app_path('TwitterPowerTrackerStream.php')
            ],'app');
        }
    }
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/power-tracker-config.php', 'power-tracker-config.php');
        $this->app->bind('power-tracker-config.php', TwitterPowerTracker::class);
    }
}
