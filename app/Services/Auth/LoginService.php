<?php


namespace App\Services\Auth;


use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\WriterRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

class LoginService
{
    private WriterRepository $writerRepository;

    public function __construct(WriterRepository $writerRepository)
    {
        $this->writerRepository = $writerRepository;
    }

    public function handler(LoginRequest $request)
    {
        // Check if a user with the specified email exists
        $writer = $this->writerRepository->getClientForEmail($request->input('email'));

        // If a user with the email was found - check if the specified password
        // belongs to this user
        if ($request->input('password') != $writer->password) {
            return response()->json([
                'message' => 'Wrong password',
                'status' => 422
            ], 422);
        }

        // Send an internal API request to get an access token
        $oauthClient = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        // Make sure a Password Client exists in the DB
        if (!$oauthClient) {
            return response()->json([
                'message' => 'Not set up properly.',
                'status' => 500
            ], 500);
        }

        if ($request->input("remember_me") == true) {
            $this->rememberMyTime();
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $oauthClient->id,
            'client_secret' => $oauthClient->secret,
            'username' => $request->input('email') ,
            'password' => $request->input('password'),
        ];
        $request = Request::create(secure_url('/') . '/oauth/token', 'POST', $data);
        $response = app()->handle($request);

        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => $response,
                'status' => 422
            ], 422);
        }

        // Get the data from the response
        $data = json_decode($response->getContent());

        // throw event
        //event(new LoggedIn($writer));

        // prepare a new collection for output to the frontend
        $clientShortSelection = $this->writerRepository->getWriter($writer->field_id);

        // prepare token data
        $tokenData = new class{};
        $tokenData->token = $data->access_token;
        $tokenData->refresh_token = $data->refresh_token;
        $tokenData->expires_date = Carbon::now('UTC')->addSeconds($data->expires_in)->toISOString();
        $tokenData->expires_in = $data->expires_in;


        // Format the final response in a desirable format
        return response()->json([
            'writer' => $clientShortSelection,
            'token' => $tokenData,
            'status' => 200
        ]);
    }
    private function rememberMyTime()
    {
        Passport::personalAccessTokensExpireIn(now()->addDays(env('TOKEN_EXPIRE_REMEMBER_DAY', 7)));
        Passport::tokensExpireIn(now()->addDays(env('TOKEN_EXPIRE_REMEMBER_DAY', 7)));
        Passport::refreshTokensExpireIn(now()->addDays(env('TOKEN_EXPIRE_REMEMBER_DAY', 7)));
    }
}
