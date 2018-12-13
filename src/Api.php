<?php
namespace InterfaceApi;

use InterfaceApi\Client;
use InterfaceApi\Exception\NoKeyException;
use InterfaceApi\Exception\ClassNotFoundException;

class Api
{
    /**
     * This is the Token API
     * @var string
     */
    public $token_api;

    /**
     * The client used to connect with InterfaceApi API
     * @var Client
     */
    public $client;

    /**
     * BaseURI InterfaceApi API
     * @var string
     */
    public $url = 'https://api.InterfaceApi.com/v1/';

    /**
     * Initial class api
     * @param string $token_api
     *
     * @throws NoTokenApiException
     */
    public function __construct($token_api = null, $client = null)
    {
        if (is_null($token_api)) {
            throw new NoKeyException("Zzz... Need a Token");
        }

        $this->token_api = $token_api;
        if (is_null($client)) {
            $this->client = new Client();
        } else {
            $this->client = $client;
        }
    }

    /**
     * This is a service locater to load instance of the AbstractApi
     * @param  string $method
     * @param  array $arguments
     *
     * @throws ClassNotFoundException
     * @return AbstractApi
     */
    public function __call($method, $arguments)
    {
        $class = 'InterfaceApi\\Api\\'.ucfirst(strtolower($method));
        if (!class_exists($class)) {
            throw new ClassNotFoundException("Class \"" . $class . "\" was not found");
        }
        $classApi = new $class($this);

        return $classApi;
    }
}
