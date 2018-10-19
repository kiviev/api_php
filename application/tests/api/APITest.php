<?php
namespace tests\api;

use tests\BaseTest;
use Api\Config\Env;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException ;


class APITest extends BaseTest
{
    private $http;
    private $api_path = 'api/';
    private $search = 'search/';

    public function setUp(){
        $this->http = new Client(['base_uri' => Env::TEST_HOST_CONFIG]);
    }

    public function tearDown(){
        $this->http = null;
    }

    private function getSearchPath(){
        return $this->api_path . $this->search;
    }

    public function test_search_term_exist() {
        $term = 'azul';
        $response = $this->http->request('GET', $this->getSearchPath() . $term);

        $this->assertEquals(200, $response->getStatusCode(),'Error statuscode');

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, 'Error json headers');

        $json = json_decode($response->getBody(),true);

        $this->assertTrue((count($json) > 0 ),'Array json is Empty');
        $propertiesExist =  array_key_exists('properties', $json[1]);
        
        $this->assertTrue($propertiesExist,'Array key properties not exist');
    }


    public function test_search_term_not_exist() {
        $term = 'notExist';
        try{
            $response = $this->http->request('GET', $this->getSearchPath() . $term);

        }catch(BadResponseException $e){
             $responseCode = $e->getResponse()->getStatusCode();
             $this->assertEquals(400, $responseCode, "Server Error");
        }

    }

}