<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralError;
use App\Http\Resources\GeneralResponse;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function show($id)
    {
        try {
            $user = User::find($id, ['id', 'name', 'email']);

            if (!$user) {
                return new GeneralError(['message'=>'User not found', 'code' => 404]);
            }
            
            return new GeneralResponse([
                'data' => [
                    'id'=>$user->id,
                    'name'=>$user->name,
                    'email'=>$user->email
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in show User : ' . $e->getMessage());
            return new GeneralError(['message'=>'Something went wrong', 'toast' => true]);
        }
        
    }
}
