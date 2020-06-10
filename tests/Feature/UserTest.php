<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Log;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker; // , DatabaseMigrations;

    /**
     * Assert it can get root.
     *
     * @return void
     */
    public function testGetRoot()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    /**
     * Assert log in HTTP.
     *
     * @return void
     */
    public function testApiLoginWithSudo()
    {
        $this->seed();
        $data = [
            'email' => getenv('SUDO_EMAIL'),
            'password' => getenv('SUDO_PASS')
        ];

        $response = $this->postJson(route('login_api'), $data);
        $response->assertStatus(200);
    }


    /**
     * Assert create user throug HTTP.
     *
     * @return void
         */

    public function testCreateUserFromSudo()
    {
        $this->seed();
        $faker = Faker\Factory::create();
        $sudo = User::where('email', getenv('SUDO_EMAIL'))->first();
        $response = $this->actingAs($sudo)
            ->postJson(route('user.upsert'), [
                'id' => 0,
                'name' => 'Test User',
                'email' => $faker->email,
                'passwords' => bcrypt($faker->password)
            ]);
        Log::info($response->json());
        $response->assertStatus(200);
    }
}
