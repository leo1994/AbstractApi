<?php
namespace InterfaceApi\Api;

use InterfaceApi\Api;
use InterfaceApi\Exception\HttpErrorException;
use Prophecy\Doubler\ClassPatch\DisableConstructorPatch;

abstract class AbstractApi
{
    /**
    * Provides access to the api object to perform requests on
    * different api endpoints.
    * @var \InterfaceApi\Api
    */
    protected $api;

    /**
     * List of params to send on request
     * @var array
     */
    protected $params = [];

    /**
     * List of http error response codes and associated erro
     * message with each code.
     *
     * @param array
     */
    protected $responseErrors = [
        '400' => 'Bad request.',
        '401' => 'Unauthorized.',
        '403' => 'Forbidden.',
        '404' => 'Resource not found.',
        '429' => 'Rate limit exceeded.',
        '500' => 'Internal server error.',
        '503' => 'Service unavailable.',
    ];

    /**
     * Default DI Constructo
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Set params
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * Get params
     * @param  string $name
     * @return string|array
     */
    public function __get($name)
    {
        return $this->params[$name];
    }

    /**
     * Wrap request of the Api
     *
     * @param  string $method
     * @param  string $path
     * @throws \InterfaceApi\Exception\HttpErrorException
     * @return \stdClass
     */
    public function request($method, $path)
    {
        $this->api->client->setup($this->api->url);
        $this->api->client->setAuth($this->api->token_api);
        $this->api->client->setOptions($this->params, 'json');

        $response = $this->api->client->request($method, $path);

        if ($response instanceof Response) {
            $code = $response->getCode();
            if ($code / 100 != 2 && isset($this->responseErrors[$code])) {
                throw new HttpErrorException($this->responseErrors[$code]);
            }
        }

        return json_decode($response);
    }
}
