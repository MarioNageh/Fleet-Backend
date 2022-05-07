<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Utils\Printer;


class AuthTest extends TestCase
{
    private $token;
    protected function setUp(): void
    {
        parent::setUp();

        // Make A Temp User mm@mm.com
        $response = $this->postJson('/api/signUp', ['mail' => 'mm@mm.com', 'password' => '123456', 'name' => 'test_user']);
        // Login With User
        $response = $this->postJson('/api/login', ['password' => '123456', 'email' => 'mm@mm.com']);
        $this->token=$response->json("Token");
    }





    public function test_login()
    {
        $response = $this->postJson('/api/login', ['password' => '123456', 'email' => 'mm@mm.com']);

        Printer::printToConsole($response->json("Token"),"Login");
        $response->assertStatus(200);
        return $response->json("Token");
    }


    public function test_sign_up()
    {
        $response = $this->postJson('/api/signUp', ['mail' => 'mmm@mm.com', 'password' => '123456', 'name' => 'test_user']);
        Printer::printToConsole($response->getContent(),"Sign Up");
        $response
            ->assertStatus(201)
            ->assertJson([
                'MessageEn' => 'Successful Registration',
            ]);
    }



    public function test_user()
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $this->get('/api/user', $headers);


        Printer::printToConsole($response->getContent(),"User");

        $response->assertStatus(200);
    }


}
