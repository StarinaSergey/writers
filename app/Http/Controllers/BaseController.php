<?php


namespace App\Http\Controllers;


use App\Helpers\DomainsHelper;

class BaseController extends Controller
{
    public DomainsHelper $domainHelper;

    public function __construct()
    {
        $this->domainHelper = new DomainsHelper();
    }
}
