<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PublicIdUtil;
use App\Helpers\Response;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\{
     JWTException,
     TokenExpiredException,
     TokenInvalidException
    };
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    const ACCOUNT_NOT_FOUND = 'ACCOUNT_NOT_FOUND';
    const ACCOUNT_NOT_ACTIVE = 'ACCOUNT_NOT_ACTIVE';

    public function login(Request $request)
    {
        $validate = $this->validateError($request, [
            'username'   => 'required',
            'password'   => 'required|string',
        ]);

        if ($validate !== true) return $validate;

        $username = $request->username;

        $checkAccount = DB::table('users')
            ->where(function($query) use($username) {
                $query->where('email', $username)
                    ->orWhere('username', $username);
            })->first();

        if(empty($checkAccount)) {
            return Response::message(self::ACCOUNT_NOT_FOUND);
        }

        $credentials = [
            "email"    => $checkAccount->email,
            "password" => $request->password
        ];

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return Response::send(401, null, 'invalid_credentials');
            }
        } catch (JWTException $e) {
            return Response::send(500, null, 'could_not_create_token');
        }

        return Response::send(200, [
            "token" => $token,
        ]);
    }

    public function register(Request $request)
    {
        $validate = $this->validateError($request, [
            'username'  => 'required|unique:users',
            'fullname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'phone'     => 'required|unique:users'
        ]);

        if ($validate !== true) return $validate;

        $uniqueID = PublicIdUtil::unique('users', 'id');

        $user = User::create([
            'id'                => $uniqueID,
            'fullname'          => $request->fullname,
            'username'          => $request->username,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'phone'             => $request->phone,
            'remember_token'    => Str::random(60),
        ]);

        $credentials = [
            "email"    => $request->email,
            "password" => $request->password
        ];

        $token = JWTAuth::attempt($credentials);

        return Response::send(200, [
            "token" => $token,
            "user"  => $user
        ]);
    }

    public function updateAccount(Request $request)
    {
        $validate = $this->validateError($request, [
            'fullname'=> 'sometimes|string|max:255',
            'email'   => 'sometimes|string|email|max:255|unique:users',
        ]);

        if ($validate !== true) return $validate;

        $user = User::where('id', Auth::user()->id)->first();

        if(!empty($request->Username) && $request->Username !== $user->Username) {
            $user->Username  = $request->Username;
        }

        if(!empty($request->Email) && $request->Email !== $user->email) {
            $user->email  = $request->Email;
        }

        $user->fullname = $request->fullname ?? Auth::user()->fullname;
        $user->save();

        return Response::send(200, $user);

    }

    public function updatePassword(Request $request)
    {
        $validate = $this->validateError($request, [
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validate !== true) return $validate;

        $user = User::where('id', Auth::user()->id)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        return Response::send(200);

    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return Response::send(404, null, 'user_not_found');
            }
        } catch (TokenExpiredException $e) {

            return Response::send(401, null, 'token_expired');

        } catch (TokenInvalidException $e) {

            return Response::send(401, null, 'token_invalid');

        } catch (JWTException $e) {

            return Response::send(401, null, 'token_absent');
        }

        return Response::send(200, [
            'user' => $user,
        ]);
    }
}
