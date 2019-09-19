
Service Provider
unniks\TwitterPowertracker\PowerTrackerServiceProvider::class,

Facade
'TwitterPowertracker' => unniks\TwitterPowertracker\PowerTrackFacade::class,

Publish Vendor Files
php artisan vendor:publish --provider="unniks\TwitterPowertracker\PowerTrackerServiceProvider" --tag="config"
