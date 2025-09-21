<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Resources\GeneralError;
use App\Http\Resources\GeneralResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();
           $data = $request->validated();

            $user = User::create([
                'name'=>$data['name'],
                'email'=>$data['email'],
                'password'=>Hash::make($data['password']),
                'mobile'=>$data['mobile'] ?? null,
                'address'=>$data['address'] ?? null,
            ]);

            $token = $user->createToken('api-token')->plainTextToken;
            DB::commit();
            return new GeneralResponse([
                'message'=>'User registered successfully',
                'data' => [
                    'user'=> ['id'=>$user->id,'name'=>$user->name,'email'=>$user->email],
                    'token'=>$token
                ],
                'toast' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in register user : ' . $e->getMessage());
            return new GeneralError(['message'=>'Something went wrong', 'toast' => true]);
        }
        
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::where('email',$data['email'])->first();

            if (!$user || !Hash::check($data['password'],$user->password)) {
                return new GeneralError(['message'=>'Invalid credentials']);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return new GeneralResponse([
                'message'=>'Login successful',
                'data' => ['token'=>$token],
                'toast' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Error in login user : ' . $e->getMessage());
            return new GeneralError(['message'=>'Something went wrong', 'toast' => true]);
        }
        
    }
}
