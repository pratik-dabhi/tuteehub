<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_plan_rate_limit_enforced()
    {
        $this->seed(\Database\Seeders\SubscriptionPlanSeeder::class);
        
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Subscribe user to Basic (3 per minute)
        $this->withHeader('Authorization',"Bearer $token")->postJson('/api/subscribe',['plan'=>'Basic']);

        // 3 allowed
        for ($i=0;$i<3;$i++) {
            $r = $this->withHeader('Authorization',"Bearer $token")->getJson('/api/users/'.$user->id);
            $r->assertStatus(200);
        }
        // 4th should be throttled
        $r = $this->withHeader('Authorization',"Bearer $token")->getJson('/api/users/'.$user->id);
        $r->assertStatus(429)->assertJson(['message'=>'API rate limit exceeded for your subscription plan.']);
    }
}
