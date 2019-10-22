<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductTest extends TestCase
{
    //SUCCESS: HTTP Code, Body
    //FAIL: HTTP Code, Body w/error Code and title
    //onvert a response from JSON into an array by calling decodeResponseJson() 
     use RefreshDatabase;

     /**
     * CREATE-1
     **/
     public function test_client_can_create_a_product()
    {
        // Given
        $attributes = [
            'name' => 'Porta Taquitos',
            'price' => 26.50
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributes
            ]
        ];
       
        // When
        $response = $this->json('POST', '/api/products', $productRequest); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([    
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links' => ['self']
            
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
                'name' =>  $body['attributes']['name'],
                'price' => $body['attributes']['price']
            ]
        );
    }

    /**
     * CREATE-2
     **/
     public function test_client_can_not_create_a_product_when_name_is_not_given()
    {
        // Given
        $attributes = [
            'price' => 26.50
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributes
            ]
        ];
        // When
        $response = $this->json('POST', '/api/products', $productRequest); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             'code' => "ERROR-1",
            'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * CREATE-3
     **/
     public function test_client_can_not_create_a_product_when_price_is_not_given()
    {
        // Given
        $attributes = [
            'name' => 'Porta Taquitos',
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributes
            ]
        ];
        // When
        $response = $this->json('POST', '/api/products', $productRequest); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             'code' => "ERROR-1",
            'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * CREATE-4
     **/
     public function test_client_can_not_create_a_product_when_price_is_not_numeric()
    {
        // Given
        $attributes = [
            'name' => 'Porta Taquitos',
            'price' => "bas"
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributes
            ]
        ];
        // When
        $response = $this->json('POST', '/api/products', $productRequest); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             'code' => "ERROR-1",
            'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * CREATE-5
     **/
     public function test_client_can_not_create_a_product_when_price_is_less_than_one()
    {
        // Given
        $attributes = [
            'name' => 'Porta Taquitos',
            'price' => 0
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributes
            ]
        ];
        // When
        $response = $this->json('POST', '/api/products', $productRequest); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             'code' => "ERROR-1",
            'title' => "Unprocessable Entity"
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
        $attributesUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => 130.60
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributesUpdate
            ]
        ];

        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productRequest); 
        //Then
        $response->assertStatus(200);         

         $response->assertJsonStructure([
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links' => ['self']
        ]);
        
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $productData['id'],
                'name' => $attributesUpdate['name'],
                'price' => $attributesUpdate['price']
            ]
        );
      //AssertBody Response
        $response->assertJsonFragment([
                'type' => 'products',
                'id'=> $productData['id'], 
                'attributes' => [
                     'name' => $attributesUpdate['name'],
                    'price' => $attributesUpdate['price']
                ],
                'links' => ['self' => 'http://firstcrud.test/api/products/'.$productData['id']]
        ]);
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
        $attributesUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => "abs"
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributesUpdate
            ]
        ];
        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productRequest); 
        // Then
        $response->assertStatus(422);
        $response->assertJsonFragment([
             'code' => "ERROR-1",
            'title' => "Unprocessable Entity"
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
        $attributesUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => 0
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributesUpdate
            ]
        ];
        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productRequest); 
        // Then
        $response->assertStatus(422);         
        $response->assertJsonFragment([
             'code' => "ERROR-1",
            'title' => "Unprocessable Entity"
        ]);
    }

    /**
     * UPDATE-4
     **/
    public function test_client_can_not_update_a_product_when_product_not_exists() {
        // Given
        $id = 0;
        $attributesUpdate = [
            'name' => 'Porta Taquitos updated',
            'price' => 130.60
        ];
        $productRequest = [
            'data' => [
                'type' => "products",
                'attributes' => $attributesUpdate
            ]
        ];
        // When
        $response = $this->json('PUT', '/api/products/'.$id, $productRequest); 
        // Then
        // Assert it sends the correct HTTP Status 
        $response->assertStatus(404);         
        // Assert the body of the error with the correct data
        $response->assertJsonFragment([
             'code' => "ERROR-2",
            'title' => "Not Found"
        ]);
    }

    /**
     * SHOW-1
     **/
     public function test_client_can_show_a_product() {
        //Given (update creation)
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
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links'
        ]);
        // Assert the product was RETURNED
        // with the correct data
        $response->assertJsonFragment([
                'type' => 'products',
                'id' => $productData['id'],
                'attributes' => ['name' => 'Porta Taquitos',
                                  'price' => "26.50"
                                ],
                'links' => [ "self" => "http://firstcrud.test/api/products/".$productData['id']]
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
             'code' => "ERROR-2",
            'title' => "Not Found"
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
             'code' => "ERROR-2",
            'title' => "Not Found"
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
        $data = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                     'name' => 'Porta Taquitos',
                     'price' => "9.50"
                ],
                'type' => 'products',
                'attributes' => [
                    'name' => 'Porta Taquitos',
                     'price' => "9.50"
                ]
            ]
        ];

        // $response->assertJsonFragment([
        //        ['type' => 'products',
        //         'attributes' => [
        //              'name' => 'Porta Taquitos',
        //              'price' => "9.50"
        //         ]],

        //        ['type' => 'products', 
        //         'attributes' => [
        //             'name' => 'Porta Taquitos',
        //              'price' => "9.50"
        //         ]]
        // ]);

        $response->assertJsonStructure([
            'data' => [
                ['type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links' => ['self']],

                ['type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links' => ['self']]
            ]
        ]);
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
            ['data' => [] ]
        );
    //AssertJsonStructure o del count.
    }

}
