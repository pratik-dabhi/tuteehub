<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\SubscribeSubscriptionRequest;
use App\Http\Resources\GeneralError;
use App\Http\Resources\GeneralResponse;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function subscribe(SubscribeSubscriptionRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $plan = SubscriptionPlan::where('name', $data['plan'])->firstOrFail();
            $user = $request->user();

            // Cancel existing active subscription if different
            $existing = $user->activeSubscription()->first();
            if ($existing) {
                if ($existing->plan_id == $plan->id) {
                    return new GeneralError(['message'=>'You already have this active plan.' , 'toast' => true]);
                }
                $existing->update(['status'=>'cancelled','cancelled_at'=>now()]);
            }

            $sub = Subscription::create([
                'user_id'=>$user->id,
                'plan_id'=>$plan->id,
                'status'=>'active',
                'started_at'=>now(),
                'cancelled_at'=>null,
            ]);

            DB::commit();

            return new GeneralResponse([
                'message'=>'Subscribed successfully',
                'data' => [
                    'subscription'=>[
                        'plan'=>$plan->name,
                        'calls_per_minute'=>$plan->calls_per_minute,
                        'started_at'=>$sub->started_at,
                    ]
                ],
                'toast' => true

            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in subscribe subscription : ' . $e->getMessage());
            return new GeneralError(['message'=>'Something went wrong', 'toast' => true]);
        }
        
    }

    public function status(Request $request)
    {
        try {
            $user = $request->user();
            $active = $user->activeSubscription()->with('plan')->first();
            if (!$active) {
                return new GeneralError(['message'=>'No active subscription']);
            }
            return new GeneralResponse([
                'data' => [
                    'plan'=>$active->plan->name,
                    'calls_per_minute'=>$active->plan->calls_per_minute,
                    'started_at'=>$active->started_at,
                    'status'=>$active->status
                ],
                'message' => 'Status fetch successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Errr in check status subscription : ' . $e->getMessage());
            return new GeneralError(['message'=>'Something went wrong', 'toast' => true]);
        }
        
    }

    public function cancel(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $request->user();
            $active = $user->activeSubscription()->first();

            if (!$active) {
                return new GeneralError(['message'=>'No active subscription to cancel', 'toast' => true]);
            }

            $active->update(['status'=>'cancelled','cancelled_at'=>now()]);
            DB::commit();

            return new GeneralResponse(['message'=>'Subscription cancelled successfully' , 'toast' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in cancel subscription : ' . $e->getMessage());
            return new GeneralError(['message'=>'Something went wrong', 'toast' => true]);
        }
        
    }
}
