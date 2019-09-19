<?php
return [
    /*
     * To work with Twitter's Powertrack API you'll need some credentials.
     *
     * If you don't have credentials yet, head over to https://apps.twitter.com/
     */
    'twitter_gnip_username' => env('TWITTER_GNIP_USERNAME'),
    'twitter_gnip_password' => env('TWITTER_GNIP_PASSWORD'),
    'twitter_gnip_dev_url' => env('TWITTER_GNIP_DEV_URL'),
    'twitter_gnip_prod_url' => env('TWITTER_GNIP_PROD_URL'),
    'twitter_gnip_replay_url' => env('TWITTER_GNIP_REPLAY_URL'),
    'twitter_gnip_30_days_url' => env('TWITTER_GNIP_STREAMING_30_DAYS_URL'),
];
