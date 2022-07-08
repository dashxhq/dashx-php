<?php

namespace Dashx\Php;

use Dashx\Php\Interfaces\ClientInterface;
use GuzzleHttp\Client;

abstract class ApiClient implements ClientInterface {
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
     * @return
     */
    protected function request($options = [], $url = '/graphql', $method = 'POST',) {
        return $this->client()->request($method, $url, $this->processRequest($options, $url));
    }
}