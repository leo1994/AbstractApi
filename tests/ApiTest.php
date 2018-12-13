<?php
namespace Test;

use InterfaceApi\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    /**
     * @expectedException \InterfaceApi\Exception\NoKeyException
     */
    public function testNoKeyException()
    {
        $api = new Api;
    }

    /**
     * @expectedException \InterfaceApi\Exception\ClassNotFoundException
     */
    public function testApiClassNotFoundException()
    {
        $api = new Api('key');
        $fakeClass = $api->apiClass();
    }
}
