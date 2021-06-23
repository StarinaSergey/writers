<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App;
use DB;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\WriterRepository;
use App\Services\Auth\LoginService;


class Login extends Controller
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function __invoke(LoginRequest $request)
    {
        return $this->loginService->handler($request);
    }
}
