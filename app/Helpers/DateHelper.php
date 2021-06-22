<?php


namespace App\Helpers;


use Carbon\Carbon;
use DateTimeZone;

class DateHelper
{
    protected $timezoneHouston = 'US/Central';

    public function getDateCreatedHouston()
    {
        $obj = Carbon::now(new DateTimeZone($this->timezoneHouston));

        return $obj->toDateTimeString();
    }

    public function getDateCreatedTimezone($timezone)
    {
        $obj = Carbon::now(new DateTimeZone($timezone));

        return $obj->toDateTimeString();
    }

    public function getDateCreatedUtc()
    {
        $obj = Carbon::now('UTC');

        return $obj->toDateTimeString();
    }

}
