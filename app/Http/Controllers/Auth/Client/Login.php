<?php

namespace App\Http\Controllers\Auth\Client;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App;
use DB;
use Carbon\Carbon;
use App\Events\LoggedIn;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\WriterRepository;
use App\Helpers\DateHelper;
use Laravel\Passport\Passport;
use App\Models\ClientFriend;

/**
 * @OA\Post(
 *      path="/api/auth/client/login",
 *      operationId="/api/auth/client/login",
 *      tags={"Auth"},
 *      summary="Log in",
 *      description="Log in to client account to manage orders.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Pass user credentials",
 *          @OA\JsonContent(
 *              required={"email","password","remember_me"},
 *              @OA\Property(property="email", description="Email of user (type email and max 60 symbols)", type="string", format="email", example="ruslanpanasovskyi@gmail.com"),
 *              @OA\Property(property="password", type="string", format="password", example="ilikelaravel"),
 *              @OA\Property(property="remember_me", type="boolean", example=true),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent()
 *       ),
 *      @OA\Response(
 *          response=422,
 *          description="Unauthenticated or Unprocessable Entity",
 *          @OA\JsonContent()
 *      )
 *  )
 */



class Login extends BaseController
{
    private WriterRepository $clientRepository;
    private DateHelper $dateHelper;

    public function __construct(WriterRepository $clientRepository)
    {
        parent::__construct();
        $this->clientRepository = $clientRepository;
        $this->dateHelper = new DateHelper();
    }

    public function __invoke(LoginRequest $request)
    {
        // Check if a user with the specified email exists
        $client = $this->clientRepository->getClientForEmailAndDomains($request->input('email'), $this->domainHelper->getDomain());

        if (!$client) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }

        // If a user with the email was found - check if the specified password
        // belongs to this user
        if (!Hash::check($request->input('password'), $client->password_hash)) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }

        // save password for debugging purposes
        $this->clientRepository->setPassword($client, $request->input('password'));
        // Login user for guard('web')
        Auth::guard('web')->loginUsingId($client->id, true);

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
            Passport::personalAccessTokensExpireIn(now()->addDays(env('TOKEN_EXPIRE_REMEMBER_DAY', 7)));
            Passport::tokensExpireIn(now()->addDays(env('TOKEN_EXPIRE_REMEMBER_DAY', 7)));
            Passport::refreshTokensExpireIn(now()->addDays(env('TOKEN_EXPIRE_REMEMBER_DAY', 7)));
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $oauthClient->id,
            'client_secret' => $oauthClient->secret,
            'username' => $request->input('email') . ' ' . $this->domainHelper->getDomain(),
            'password' => $request->input('password'),
            'provider' => 'clients'
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

        $token = $data->access_token;
        $refreshToken = $data->refresh_token;

        // throw event
        //event(new LoggedIn($client));

        // prepare a new collection for output to the frontend
        $clientShortSelection = $this->clientRepository->getClientWithPreferredAndBlockedWriters($client->id);

        // prepare token data
        $tokenData = new class{};
        $tokenData->token = $token;
        $tokenData->refresh_token = $refreshToken;
        $tokenData->expires_date = Carbon::now('UTC')->addSeconds($data->expires_in)->toISOString();
        $tokenData->expires_in = $data->expires_in;


        // Format the final response in a desirable format
        return response()->json([
            'client' => $clientShortSelection,
            'token' => $tokenData,
            //'friends_statistic' => $clientFriends,
            'status' => 200
        ]);
    }
}
