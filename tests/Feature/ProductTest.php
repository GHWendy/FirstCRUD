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
     use RefreshDatabase;

     public function test_client_can_create_a_product()
    {
        // Given
        $productData = [
            'name' => 'Porta Taquitos',
            'price' => 26.50
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
             'name' => 'Porta Taquitos',
            'price' => 26.50
        ]);
        
        $body = $response->decodeResponseJson();
        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Porta Taquitos',
                'price' => 26.50
            ]
        );
    }

     public function test_client_can_show_a_product() {
        //Given
        $productData = factory(Product::class)->create([
            'name' => 'Porta Taquitos',
            'price' => 26.50
        ]);   
        // When
        $response = $this->json('GET', '/api/products/'. $productData['id']); 
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
            'id' => $productData['id'],
            'name' => 'Porta Taquitos',
            'price' => "26.50"
        ]);
    }

    public function test_client_can_update_a_product() {
         $productData = factory(Product::class)->create([
            'name' => 'Porta Taquitos',
            'price' => 26.50
        ]);

      // Given
        $id = $productData['id'];
        $productDataUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => 130.60
        ];
        // When
        $response = $this->json('PATCH', '/api/products/'.$id, $productDataUpdate); 
        // Then
        // Assert it sends the correct HTTP Status 
        $response->assertStatus(200); 
        
        // Assert the response has the correct structure
      $response->assertJsonStructure([
           'id',
            'name',
            'price'
        ]);

        // Assert the product was updated with the correct data and it's on the database
      $this->assertDatabaseHas(
            'products',
            [
                'id' => $productData['id'],
                'name' => $productDataUpdate['name'],
                'price' => $productDataUpdate['price']
            ]
        );
        $response->assertJsonFragment($productDataUpdate);
    }

    public function test_client_can_delete_a_product() {
       //Given
       $productData = factory(Product::class)->create([
            'name' => 'Porta Taquitos',
            'price' => 26.50
        ]);
        
        // When
        $response = $this->json('DELETE', '/api/products/'.$productData['id']); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200); 
    }

    public function test_client_can_list_all_products () {
        //Given
        $products = factory(Product::class,4)->create([
            'name' => 'Porta Taquitos',
            'price' => 9.50
        ]);

        //Create product //Entonces pasarle los parametros.
         //When
        $response = $this -> json('GET', 'api/products/');
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200); 
        // Assert the products were RETURNED with the correct data
        $response->assertJsonCount( count($products));
    //AssertJsonStructure o del count.
    }

}
