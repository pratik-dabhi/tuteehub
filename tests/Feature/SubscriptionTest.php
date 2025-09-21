<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\SubscriptionPlan;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_and_status_and_cancel()
    {
        $this->seed(\Database\Seeders\SubscriptionPlanSeeder::class);
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        #subscribe the subscription
        $subscribeResponse = $this->withHeader('Authorization',"Bearer $token")->postJson('/api/subscribe',['plan'=>'Basic']);

        #verify the subscription
        $subscribeResponse->assertStatus(200)->assertJson([
            'data' => [
                'subscription' => [
                    'plan'=>'Basic'
                ]
            ],
            "message" => "Subscribed successfully"
        ]);

        #fetch the status of subscription
        $subscriptionStatusResponse = $this->withHeader('Authorization',"Bearer $token")->getJson('/api/subscription/status');

        #verify the status of subscription
        $subscriptionStatusResponse->assertStatus(200)->assertJson([
            'data' => [
                    'plan'=>'Basic'
            ],
            "message" => "Status fetch successfully"
        ]);

        #cancel the subscription
        $subscriptionCancelResponse = $this->withHeader('Authorization',"Bearer $token")->postJson('/api/subscription/cancel');

        #veirfy the cancelled subscription
        $subscriptionCancelResponse->assertStatus(200)->assertJsonFragment(['message'=>'Subscription cancelled successfully']);

        #check the current active subscription
        $subscriptionReStatusResponse = $this->withHeader('Authorization',"Bearer $token")->getJson('/api/subscription/status');
        $subscriptionReStatusResponse->assertStatus(200)->assertJson(['message'=>'No active subscription']);
    }
}
