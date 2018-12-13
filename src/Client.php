<?php
namespace InterfaceApi;

use InterfaceApi\Exception\InvalidMethodException;
use GuzzleHttp\Client as Guzzle;

class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * 'RequestOptions: Headers, Auth, Json/XML, etc...'
     * @var array
     */
    public $options = [];
    /**
     * accepted methods InterfaceApi API
     * @var array
     */
    private $methods = ['GET', 'POST', 'DELETE', 'PUT'];

    /**
     * Set baseUrl
     * @param  string $baseUri
     * @return void
     */
    public function setup($baseUri, $handler = false)
    {
        $options = [ 'base_uri' => $baseUri ];

        if ($handler) {
            $options['handler'] = $handler;
        }

        $this->client = new Guzzle($options);
    }

    public function setAuth($key)
    {
        $auth = [$key, ''];
        $this->setOptions($auth, 'auth');
    }

    public function setOptions($value, $option)
    {
        $this->options[$option] = $value;
    }

    public function getOptions($option)
    {
        return $this->options[$option];
    }

    /**
     * Attempt to do a request to given method and path.
     * @param  string $method
     * @param  string $path
     * @param  array  $params
     *
     * @throws InvalidMethodException
     * @return \stdClass
     */
    public function request($method, $path)
    {

        $method = strtoupper($method);
        if (!in_array($method, $this->methods)) {
            throw new InvalidMethodException('"'.$method.'" is a invalid method');
        }

        $response = $this->client->request($method, $path, $this->options);

        $contents = $response->getBody()->getContents();
        $code = $response->getStatusCode();
        $headers = $response->getHeaders();

        return new Response($contents, $code, $headers);
    }
}
