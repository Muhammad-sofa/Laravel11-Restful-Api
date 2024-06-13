<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\ContactSeeder;
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

    public function getGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts'. $contact->id,[
            'Authorization' => 'test'
        ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'first_name' => 'test',
                'last_name' => 'test',
                'email' => 'test@gmail.com',
                'phone' => '08123456789'
            ]
        ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts'. ($contact->id + 1), [
            'Authorization' => 'test'
        ])->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => 'Not found'
            ]
        ]);
    }

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts'. $contact->id,[
            'Authorization' => 'test2'
        ])->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => 'Not found'
            ]
        ]);
    }
}
