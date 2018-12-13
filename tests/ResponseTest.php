<?php
namespace Test;

use InterfaceApi\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testResponse()
    {
        $response = new Response('someContent', 200);
        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('someContent', $response);
    }
}
