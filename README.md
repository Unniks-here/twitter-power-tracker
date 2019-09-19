
**Service Provider**
unniks\TwitterPowertracker\PowerTrackerServiceProvider::class,

**Facade**
'TwitterPowertracker' => unniks\TwitterPowertracker\PowerTrackFacade::class,

**Publish Vendor Files**
php artisan vendor:publish --provider="unniks\TwitterPowertracker\PowerTrackerServiceProvider" --tag="config"

**Copy Paste**
cp vendor/unniks/twitter-powertracker/example/TwitterPowerTrackerStream.php app/TwitterPowerTrackerStream.php
