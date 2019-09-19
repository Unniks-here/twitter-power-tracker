<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitterPowerTrackerStream extends Model
{
    // Power Track Dev URL data will arrive here
    public static function getPowerTrack($data)
	{
        echo $data;
    }
}
