
## Twitter Power Tracker

Twitter Power Tracker is the package for connecting and streaming data with twitter's enterprise streaming plans.

## Installation

```sh
$ composer require unniks/twitter-powertracker
```

#### Don't forget to add service provider
```sh
unniks\TwitterPowertracker\PowerTrackerServiceProvider::class,
```
#### Twitter Power Tracker comes with facade. Add following lines in aliases
```sh
'TwitterPowertracker' => unniks\TwitterPowertracker\PowerTrackFacade::class,
```

#### Publish Vendor Files for configuration
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
#### Use this facade
```sh
use TwitterPowertracker; 
```

#### For Rule Creation

###### if you can pass json format recomended by GNIP console
_
```sh
 $json =  {"rules" :[{"value" : "from:foo","tag" : "some_foo_tag"},{"value" : "foo:keyword"}]} ;
TwitterPowertracker::ruleCreationByJson($json);
```
###### or just pass array of rules like this using ruleCreationByArray()
_
```sh
$rules =['from:rahul','@shami'];
TwitterPowertracker::ruleCreationByArray($rules); 
```
###### if you need to pass tags as array just use like this
_
```sh
$rules = ['from:rahul','@shami'];
$tags = ['rahul_tag','sham_tag'];
TwitterPowertracker::ruleCreationByArray($rules,$tags);
```
note: array index of $tags should have matched to array index of $rules 
#### For Rule Deletion 
###### you can use above mentioned same techniques for
_
```sh
TwitterPowertracker::ruleDeletionByJson($json); 
```
###### and
_
##### 
```sh
TwitterPowertracker::ruleDeletionByJson($json); 
TwitterPowertracker::ruleDeletionByArray($rules,$tags); 
```


What is rules? Please go through this documentation: https://developer.twitter.com/en/docs/tweets/filter-realtime/overview/powertrack-api

#### For streaming data

```sh
use TwitterPowertracker; 
TwitterPowertracker::powerStream();
```
By calling powerStream() data will be continuously streamed to the model app/TwitterPowerTrackerStream.php 

```sh
public static function getPowerTrack($data)
{
    //do some stuff with incoming $data
}
```

#### For force stopping the live streaming

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
#### For 30 days twitter data 
##### Method 1
```sh
$results = TwitterPowertracker::thirtyDaysGet($rule);
``` 
##### Method 2
```sh
$results = TwitterPowertracker::thirtyDaysGet($rule,$maxResults);
```
##### Method 3
```sh
$results = TwitterPowertracker::thirtyDaysGet($rule,$maxResults,$startDate,$endDate);
```
##### Method 4
```sh
$results = TwitterPowertracker::thirtyDaysGet($rule,$maxResults,$startDate,$endDate,$next);
```
(use https://support.gnip.com/apis/search_api2.0/api_reference.html for reference)
#### ********* Awaiting Brilliant Contributions for this simple Package **********

## License

The unniks/twitter-powertracker is open-sourced software licensed under the [MIT license]
