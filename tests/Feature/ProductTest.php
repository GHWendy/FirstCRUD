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
    //SUCCESS: HTTP Code, Body
    //FAIL: HTTP Code, Body w/error Code and title
     use RefreshDatabase;

     /**
     * CREATE-1
     **/
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

    /**
     * CREATE-2
     **/
     public function test_client_can_not_create_a_product_when_name_is_not_given()
    {
        // Given
        $productData = [
            'price' => 26.50
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-1",
            //'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * CREATE-3
     **/
     public function test_client_can_not_create_a_product_when_price_is_not_given()
    {
        // Given
        $productData = [
            'name' => 'Porta Taquitos'
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-1",
            //'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * CREATE-4
     **/
     public function test_client_can_not_create_a_product_when_price_is_not_numeric()
    {
        // Given
        $productData = [
            'price' => "abs"
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-1",
            //'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * CREATE-5
     **/
     public function test_client_can_not_create_a_product_when_price_is_less_than_one()
    {
        // Given
        $productData = [
            'price' => 0
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-1",
            //'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * UPDATE-1
     **/
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
        $response = $this->json('PUT', '/api/products/'.$id, $productDataUpdate); 
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
      //AssertBody Response
        $response->assertJsonFragment($productDataUpdate);
    }

     /**
     * UPDATE-2
     **/
    public function test_client_can_not_update_a_product_when_price_is_not_numeric() {
         $productData = factory(Product::class)->create([
            'name' => 'Porta Taquitos',
            'price' => 26.50
        ]);

      // Given
        $id = $productData['id'];
        $productDataUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => "abs"
        ];
        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productDataUpdate); 
        // Then
        // Assert it sends the correct HTTP Status 
        $response->assertStatus(422);         
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-1",
            //'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * UPDATE-3
     **/
    public function test_client_can_not_update_a_product_when_price_is_not_in_valid_range() {
         $productData = factory(Product::class)->create([
            'name' => 'Porta Taquitos',
            'price' => 26.50
        ]);

      // Given
        $id = $productData['id'];
        $productDataUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => 0
        ];
        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productDataUpdate); 
        // Then
        // Assert it sends the correct HTTP Status 
        $response->assertStatus(422);         
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-1",
            //'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * UPDATE-4
     **/
    public function test_client_can_not_update_a_product_when_product_not_exists() {

      // Given
        $id = 0;
        $productDataUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => 12.34
        ];
        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productDataUpdate); 
        // Then
        // Assert it sends the correct HTTP Status 
        $response->assertStatus(404);         
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-2",
            //'title' => "Not Found"
        ]);
    }

    /**
     * SHOW-1
     **/
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

    /**
     * SHOW-2
     **/
     public function test_client_can_not_show_a_product_when_not_exists() {
        //Given
        $id = 0;   
        // When
        $response = $this->json('GET', '/api/products/'. $id); 
       // Then
        // Assert it sends the correct HTTP Status 
        $response->assertStatus(404);         
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-2",
            //'title' => "Not Found"
        ]);
    }

    /**
     * DELETE-1
     **/
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
        $response->assertStatus(204); 
    }

     /**
     * DELETE-2
     **/
    public function test_client_can_not_delete_a_product_when_product_not_exists() {
       //Given
        $id = 0;   
      // When
        $response = $this->json('DELETE', '/api/products/'.$id); 

       // Then
        // Assert it sends the correct HTTP Status 
        $response->assertStatus(404);         
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             //'code' => "ERROR-2",
            //'title' => "Not Found"
        ]);
    }

    /**
     * LIST-1
     **/
    public function test_client_can_list_all_products () {
        //Given
        $products = factory(Product::class,2)->create([
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
        $response->assertJsonFragment(
            [
            'name' => 'Porta Taquitos',
            'price' => "9.50"
            ],
            [
            'name' => 'Porta Taquitos',
            'price' => "9.50"
            ]
        );
    //AssertJsonStructure o del count.
        $response->assertJsonCount( count($products));
    }
     /**
     * LIST-2
     **/
    public function test_client_can_list_all_products_when_there_are_none () {
         //When
        $response = $this -> json('GET', 'api/products/');
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200); 
        // Assert the products were RETURNED with the correct data
        $response->assertJsonFragment(
            []
        );
    //AssertJsonStructure o del count.
        $response->assertJsonCount( 0);
    }

}
