<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
* @OA\Info(
*      version="1.0.0",
*      title="Essay Writer API",
*      description="Description of API for the Writer module ",
*      @OA\Contact(
*          email="ruslanpanasovskyi@gmail.com"
*      ),
*      @OA\License(
*          name="Apache 2.0",
*          url="http://www.apache.org/licenses/LICENSE-2.0.html"
*      )
* )
*/

/**
*  @OA\SecurityScheme(
 *     type="http",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 * )
*/


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
