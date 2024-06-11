<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'Sofa',
            'password' => 'password',
            'name' => 'Sofa Yuliansyah'
        ])->assertStatus(201)->assertJson([
            "data" => [
                'username' => 'Sofa',
                'name' => 'Sofa Yuliansyah'
            ]
        ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertJson([
                    "errors" => [
                        'username' => [
                            "The username field is required."
                        ],
                        'password' => [
                            "The password field is required."
                        ],
                        'name' => [
                            "the name field is required."
                        ]
                    ]
                ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'Sofa',
            'password' => 'password',
            'name' => 'Sofa Yuliansyah'
        ])->assertStatus(400)->assertJson([
            "errors" => [
                'username' => [
                    "Username already exists"
                ]
            ]
        ]);
    }
}