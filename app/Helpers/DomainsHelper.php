<?php


namespace App\Helpers;


class DomainsHelper
{
    public function getDomain()
    {
        return env('APP_DOMAIN'); //str_replace('www.', '', request()->getHost());
    }
}
