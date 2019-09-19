<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitterPowerTrackerStream extends Model
{
    // Power Track Dev URL data will arrive here
    public static function getPowerTrackDev($data)
	{
        echo $data;
    }

    // Power Track Prod URL data will arrive here
    public static function getPowerTrackProd($data)
	{
        echo $data;
    }
}
