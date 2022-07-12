<?php

namespace Dashx\Php;

use GuzzleHttp\Client;

abstract class ApiClient {
    /**
     * Format api response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * 
     * @return array
     */
    abstract protected function formatResponse($response): array;

    /**
     * The guzzle client.
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Set the default guzzle config options.
     *
     * @return array
     */
    protected function clientConfig() {
        return [
            'base_uri' => null,
            'verify' => false,
        ];
    }

    /**
     * Process the api request before the api call is made.
     *
     * @param  array  $options
     * @param  string  $url
     * @return array
     */
    protected function processRequest($options, $url) {
        // request options can be added here.

        // return the final request option set
        return $options;
    }

    /**
     * Create and return the guzzle client.
     *
     * @return \GuzzleHttp\Client
     */
    private function client() {
        if($this->client === null) {
            $this->client = new Client($this->clientConfig());
        }

        return $this->client;
    }

    /**
     * Make an api request.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  array  $options
     *
     * @return array
     */
    protected function request($options = [], $url = '/graphql', $method = 'POST',) {
        $response = $this->client()
            ->request($method, $url, $this->processRequest($options, $url));

        return $this->formatResponse($response);
    }
}