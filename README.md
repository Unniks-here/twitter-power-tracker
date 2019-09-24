
## Twitter Power Tracker

Twitter Power Tracker is the package for connecting and streaming data with twitter's enterprise streaming plans.

## Installation

$ composer require unniks/twitter-powertracker

**Don't forget to add service provider** <br>
unniks\TwitterPowertracker\PowerTrackerServiceProvider::class,
<br>
**Twitter Power Tracker comes with facade. Add following lines in aliases** <br>
'TwitterPowertracker' => unniks\TwitterPowertracker\PowerTrackFacade::class,
<br>
**Publish Vendor Files for configuration** <br>
php artisan vendor:publish --provider="unniks\TwitterPowertracker\PowerTrackerServiceProvider"
<br>
<br>
## Usage
<br>
**We need GNIP account before using with this feature. If you have GNIP username and password, add following variables in your .env file** <br>

TWITTER_GNIP_USERNAME=test@test.xyz <br>
TWITTER_GNIP_PASSWORD=xxxxx <br>
TWITTER_GNIP_URL=https://gnip-stream.twitter.com/stream/powertrack/accounts/{username}/publishers/twitter/{variable}.json <br>
TWITTER_GNIP_REPLAY_URL=https://gnip-stream.gnip.com/replay/powertrack/accounts/{username}/publishers/twitter/{variabale}.json <br>
TWITTER_GNIP_STREAMING_30_DAYS_URL=https://gnip-api.twitter.com/search/30day/accounts/{username}/{variabale}.json <br>
TWITTER_GNIP_RULES_URL=https://gnip-api.twitter.com/rules/powertrack/accounts/{username}/publishers/twitter/{variabale}.json <br>

**For Rule Creation** <br>
use TwitterPowertracker; <br>
TwitterPowertracker::ruleCreation($json); //pass json values of rules to create<br>
**For streaming data**
use TwitterPowertracker; <br>
TwitterPowertracker::powerStream();<br>
Data will be available at the model app/TwitterPowerTrackerStream.php
<br>

<br>
<h4>******** Awaiting Brilliant Contributions for this simple Package **********</h4>

## License

The unniks/twitter-powertracker is open-sourced software licensed under the [MIT license]
