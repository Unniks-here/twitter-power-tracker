<?php
namespace unniks\TwitterPowertracker;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Spatie\LaravelTwitterStreamingApi\LaravelTwitterStreamingApiClass
 */
class PowerTrackFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'unniks\TwitterPowertracker\TwitterPowerTracker';
    }
}
