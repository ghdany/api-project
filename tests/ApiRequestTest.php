<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ApiRequestTest extends TestCase
{

    public function test_river_get_call(): void
    {

        $res = $this->client->get( env('API_URL') . 'sample/thejournal', ['auth' =>  [env('API_USERNAME'), env('API_PASSWORD')]]);

        $this->assertEquals(200, $res->getStatusCode());


        $contentType = $res->getHeaders()["Content-Type"][0];

        $this->assertEquals("application/json", $contentType);


        $data = json_decode($res->getBody()->getContents(), true)["response"]["articles"];

        $this->assertArrayHasKey('title', $data)
            ->assertArrayHasKey('excerpt', $data);

    }


}
