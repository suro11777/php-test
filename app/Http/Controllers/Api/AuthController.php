<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Api\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $http = new Client();

        $response = $http->post(config('app.url').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('auth.passport.password.id'),
                'client_secret' => config('auth.passport.password.secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);

        return response()->json(['status' => 200, 'user' => $user, (string) $response->getBody()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $http = new Client();
        if (auth()->attempt($credentials)) {
            $response = $http->post(config('app.url').'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('auth.passport.password.id'),
                    'client_secret' => config('auth.passport.password.secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);

            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }
}
