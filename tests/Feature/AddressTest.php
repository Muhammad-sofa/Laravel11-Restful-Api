<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . $contact->id . '/addresses', 
        [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => 'test',
            'postal_code' => '12345678',
        ],
        [
            'Authorization' => 'test'
        ]
        )->assertStatus(201)
        ->assertJson([
            'data' => [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country' => 'test',
                'postal_code' => '12345678',
            ]
        ]);
    }

    public  function testCreateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . $contact->id . '/addresses', 
        [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => '',
            'postal_code' => '12345678',
        ],
        [
            'Authorization' => 'test'
        ]
        )->assertStatus(400)
        ->assertJson([
            'errors' => [
                'country' => ['The country field is required.']
            ]
        ]);
    }

    public function testCreateContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . ($contact->id + 1) . '/addresses', 
        [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => 'test',
            'postal_code' => '12345678',
        ],
        [
            'Authorization' => 'test'
        ]
        )->assertStatus(404)
        ->assertJson([
            'data' => [
                'message' => ['Not found']
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country' => 'test',
                'postal_code' => '11111'
            ]
        ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1), [
            'Authorization' => 'test'
        ])->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => ['not found']
            ]
        ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
        [
            'street' => 'update',
            'city' => 'update',
            'province' => 'update',
            'country' => 'update',
            'postal_code' => '222222',
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'street' => 'update',
                'city' => 'update',
                'province' => 'update',
                'country' => 'update',
                'postal_code' => '222222',
            ]
        ]);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
        [
            'street' => 'update',
            'city' => 'update',
            'province' => 'update',
            'country' => '',
            'postal_code' => '222222',
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'country' => ['The country field is required.']
            ]
        ]);
    }

    public function testUpdateNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1),
        [
            'street' => 'update',
            'city' => 'update',
            'province' => 'update',
            'country' => 'update',
            'postal_code' => '222222',
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => ['Not found']
            ]
        ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
        [
            
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(200)
        ->assertJson([
            'data' => true
        ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1),
        [
            
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => ['Not found']
            ]
        ]);
    }
}
