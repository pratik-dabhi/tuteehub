<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token()
    {
        $this->seed(\Database\Seeders\SubscriptionPlanSeeder::class);

        #register the user
        $registerResponse = $this->postJson('/api/users/register', [
            'name'=>'Pratik Dabhi',
            'email'=>'pratik.dabhi@yopmail.com',
            'password'=>'Pratik@123',
            'mobile'=>'1234567890',
            'address'=>'Test address'
        ]);

        #verify the registered user
        $registerResponse->assertStatus(200)->assertJsonStructure([
            'message',
            'data' => ['user','token']
        ]);

        $this->assertDatabaseHas('users',['email'=>'pratik.dabhi@yopmail.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(['password'=>bcrypt('Pratik@123')]);

        #login the user
        $loginResponse = $this->postJson('/api/login', [
            'email'=>$user->email,
            'password'=>'Pratik@123'
        ]);

        #check the login status
        $loginResponse->assertStatus(200)->assertJsonStructure([
            'message',
            'data' => ['token']
        ]);
    }
}
