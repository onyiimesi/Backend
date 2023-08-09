<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\StoreTalentRequest;
use App\Http\Resources\V1\LoginUserResource;
use App\Mail\V1\TalentVerifyEmail;
use App\Models\V1\Talent;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request){

        $request->validated($request->all());

        $talentGuard = Auth::guard('talents');
        // $studGuard = Auth::guard('studs');

        if ($talentGuard->attempt($request->only(['email_address', 'password']))) {
            $user = Talent::where('email_address', $request->email_address)->first();
            $users = new LoginUserResource($user);
            $token = $user->createToken('API Token of '. $user->first_name);

            return $this->success([
                'user' => $users,
                'token' => $token->plainTextToken,
            ]);
        }

        // elseif ($studGuard->attempt($request->only(['username', 'password']))) {
        //     $stud = Student::where('username', $request->username)->first();
        //     $studs = new StudentLoginResource($stud);
        //     $token = $stud->createToken('API Token of '. $stud->username);

        //     return $this->success([
        //         'user' => $studs,
        //         'token' => $token->plainTextToken,
        //         'expires_at' => $token->accessToken->expires_at
        //     ]);
        // }

        return $this->error('', 'Credentials do not match', 401);

    }

    public function talentRegister(StoreTalentRequest $request) {

        $request->validated($request->all());

        $user = Talent::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_address' => $request->email_address,
            'password' => Hash::make($request->password),
            'hear_about_us' => $request->hear_about_us,
            'otp' => Str::random(60),
            'status' => 'Inactive',
        ]);

        Mail::to($request->email_address)->send(new TalentVerifyEmail($user));

        return $this->success([
            'talent' => $user,
        ]);
    }

    public function verify($token)
    {
        // Find the user with the provided token
        $user = Talent::where('otp', $token)->first();

        // Check if the user with the token exists
        if (!$user) {
            // Token not found or invalid
            return $this->error('', 'Error', 422);
        }

        // Update the status and remove the verification token
        $user->status = 'Active';
        $user->otp = null;
        $user->save();

        // You can redirect the user to a success page or any other desired destination
        return [
            "status" => 'true',
            "message" => 'Verification successful'
        ];
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {

        try {

            $user = Socialite::driver('google')->user();
            $finduser = Talent::where('email_address', $user->getEmail())->first();

            if ($finduser){
                return 'Hii';
            }else {
                $newUser = Talent::create([
                    'first_name' => $user->name,
                    'email_address' => $user->email,

                    'password' => encrypt('123456dummy')
                ]);
                return 'Hiaaai';
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }

    public function register(Request $request)
    {
        $user = Talent::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_address' => $request->email_address,
            'password' => Hash::make($request->password),
            'hear_about_us' => $request->hear_about_us,
            'otp' => Str::random(60),
            'status' => 'Inactive',
        ]);

        return $this->success([
            'talent' => $user,
            'token' => $user->createToken('API Token of '. $user->first_name)->plainTextToken
        ]);
    }
}
