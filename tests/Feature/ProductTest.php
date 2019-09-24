<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
//use Tests\TestCase;

class ProductTest extends TestCase
{
     //use RefreshDatabase;

     public function test_client_can_create_a_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );
    }

     public function test_client_can_show_a_product() {
      // Given
        $id = '2';
        
        // When
        $response = $this->json('GET', '/api/products/'.$id); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200); 
        
        // Assert the response has the correct structure
      $response->assertJsonStructure([
           'id',
            'name',
            'price'
        ]);

        // Assert the product was RETURNED
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Porta Taquitos updated',
            'price' => '130.60'
        ]);
    }

    public function test_client_can_update_a_product() {
      // Given
        $id = '2';
        $productData = [
            'name' => 'Porta Taquitos updated',
            'price' => '130.60'
        ];
        
        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200); 
        
        // Assert the response has the correct structure
      $response->assertJsonStructure([
           'id',
            'name',
            'price'
        ]);

        // Assert the product was updated
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Porta Taquitos updated',
            'price' => '130.60'
        ]);
    }

    public function test_client_can_delete_a_product() {
      // Given
        $id = '2';
        
        
        // When
        $response = $this->json('DELETE', '/api/products/'.$id); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200); 
    }

}
