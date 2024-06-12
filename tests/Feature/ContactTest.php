<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => 'sofa',
            'last_name' => 'yuliansyah',
            'email' => 'qQpWQ@example.com',
            'phone' => '08123456789'
        ])->assertStatus(201)
        ->assertJson([
            'data' => [
                'first_name' => 'sofa',
                'last_name' => 'yuliansyah',
                'email' => 'qQpWQ@example.com',
                'phone' => '08123456789'
            ]
        ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class]);
        
        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'yuliansyah',
            'email' => 'qQpWQ',
            'phone' => '08123456789'
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'first_name' => [
                    'The first name field is required.'
                ],
                'email' => [
                    'The email field must be a valid email address.'
                ]
            ]
        ]);
    }

    public function testCreateUnauthorized()
    {
        $this->seed([UserSeeder::class]);
        
        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'yuliansyah',
            'email' => 'qQpWQ',
            'phone' => '08123456789'
        ], [
            'Authorization' => 'salah'
        ]
        )->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => 'Unauthenticated.'
            ]
        ]);
    }
}
