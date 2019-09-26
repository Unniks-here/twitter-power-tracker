
## Twitter Power Tracker

Twitter Power Tracker is the package for connecting and streaming data with twitter's enterprise streaming plans.

## Installation

```sh
$ composer require unniks/twitter-powertracker
```

### Don't forget to add service provider
unniks\TwitterPowertracker\PowerTrackerServiceProvider::class,

### Twitter Power Tracker comes with facade. Add following lines in aliases
```sh
'TwitterPowertracker' => unniks\TwitterPowertracker\PowerTrackFacade::class,
```

### Publish Vendor Files for configuration
```sh
$ php artisan vendor:publish --provider="unniks\TwitterPowertracker\PowerTrackerServiceProvider"
```

## Usage

We need GNIP account before using with this feature. If you have GNIP username and password, add following variables in your .env file

```sh
TWITTER_GNIP_USERNAME=test@test.xyz <br>
TWITTER_GNIP_PASSWORD=xxxxx <br>
TWITTER_GNIP_URL=https://gnip-stream.twitter.com/stream/powertrack/accounts/{username}/publishers/twitter/{variable}.json <br>
TWITTER_GNIP_REPLAY_URL=https://gnip-stream.gnip.com/replay/powertrack/accounts/{username}/publishers/twitter/{variabale}.json <br>
TWITTER_GNIP_STREAMING_30_DAYS_URL=https://gnip-api.twitter.com/search/30day/accounts/{username}/{variabale}.json <br>
TWITTER_GNIP_RULES_URL=https://gnip-api.twitter.com/rules/powertrack/accounts/{username}/publishers/twitter/{variabale}.json 
```


### For Rule Creation
```sh
use TwitterPowertracker; 
TwitterPowertracker::ruleCreation($json); //pass json values of rules to create
```


### For Rule Deletion 
```sh
use TwitterPowertracker;
TwitterPowertracker::ruleDeletion($json); //pass json values of rules to delete
```

### Rules format

```sh
JSON Format 
        {<br>
        "rules" :
            [
                {
                "value" : "keyword (contains:substring OR \"this phrase\")",
                "tag" : "Customer_1"
                },
                {
                "value" : "keyword lang:en profile_locality:\"New York City\""
                }
            ]
        }
```

Find rules documentation from here: https://developer.twitter.com/en/docs/tweets/filter-realtime/overview/powertrack-api

### For streaming data

```sh
use TwitterPowertracker; 
TwitterPowertracker::powerStream();
```
By calling powerStream() data will be continuously streamed the model app/TwitterPowerTrackerStream.php 

```sh
public static function getPowerTrack($data)
{
    //do some stuff with incoming $data
}
```

### For force stopping the live streaming

```sh
//return "exit" in the function "app/TwitterPowerTrackerStream.php"

public static function getPowerTrack($data)
{
    //some code
    if(//some condition)
    {
      return "exit";
    }
}
```

******** Awaiting Brilliant Contributions for this simple Package **********

## License

The unniks/twitter-powertracker is open-sourced software licensed under the [MIT license]
