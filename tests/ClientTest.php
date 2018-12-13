<?php
namespace Test;

use InterfaceApi\Client;
use PHPUnit\Framework\TestCase;
use Mockery;

class ClientTest extends TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new Client();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException \InterfaceApi\Exception\InvalidMethodException
     */
    public function testInvalidMethodException()
    {
        $this->client->request('move', '');
    }

    public function testRequest()
    {
        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, ['X-Foo' => 'Bar'], \GuzzleHttp\Psr7\stream_for('{foo: bar}'))
        ]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);

        $this->client->setup('https://google.com', $handler);
        $response = $this->client->request('GET', '/');

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('{foo: bar}', $response);
        $this->assertTrue($response->hasHeader('X-Foo'));
        $this->assertFalse($response->hasHeader('Missing-Header'));
        $this->assertEquals('Bar', $response->getHeader('X-Foo'));
        $this->assertNull($response->getHeader('that does not exists'));
        $headers = $response->getHeaders();
        $this->assertArrayHasKey('X-Foo', $headers);
        $this->assertCount(1, $headers);
        $this->assertEquals('Bar', $headers['X-Foo']);
    }

    public function testSetAndGetOptions()
    {
        $type = 'json';
        $values = [
            'name' => 'Mc Donalds',
            'whatever' => true
        ];
        $expectedResult = [$type => $values];
        $this->client->setOptions($values, $type);
        $this->assertEquals($values, $this->client->getOptions($type));
        $this->assertEquals($expectedResult, $this->client->options);
    }

    public function testSetAuth()
    {
        $this->client->setAuth('SomeKey');
        $this->assertEquals(['SomeKey', ''], $this->client->getOptions('auth'));
    }
}
