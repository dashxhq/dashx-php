<?php

namespace Dashx\Php;

class Client extends ApiClient {
    use Graphql;

    /**
     * Base uri
     * 
     * @var
     */
    private $base_uri;

    /**
     * Public key
     * 
     * @var
     */
    private $public_key;

    /**
     * Private key
     * 
     * @var
     */
    private $private_key;

    /**
     * Target installation
     * 
     * @var
     */
    private $target_installation;

    /**
     * Target environment
     * 
     * @var
     */
    private $target_environment;

    /**
     * Create a new client instance.
     *
     * @return void
     */
    public function __construct($public_key, $private_key, $target_environment, $base_uri = 'https://api.dashx-staging.com', $target_installation = null) {
        $this->base_uri = $base_uri;
        $this->public_key = $public_key;
        $this->private_key = $private_key;
        $this->target_installation = $target_installation;
        $this->target_environment = $target_environment;
    }

    /**
     * Set the default guzzle config options.
     *
     * @return array
     */
    protected function clientConfig() {
        return [
            'base_uri' => $this->base_uri,
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
        $options = array_merge($options, [
            'headers' => [
                'User-Agent' => 'dashx-php',
                'X-Public-Key' => $this->public_key,
                'X-Private-Key' => $this->private_key,
                'X-Target-Environment' => $this->target_environment,
                'Content-Type' => 'application/json',
            ]
        ]);

        // return the final request option set
        return $options;
    }

    /**
     * @param string $urn
     * @param null $options
     * 
     * @return
     */
    public function deliver(string $urn, $options = null) {

    }

    /**
     * @param string|int $uid
     * @param null $options
     * 
     * @return
     */
    public function identify(string|int $uid, array $options = [], array $selectors = [
        'id',
        'firstName',
        'lastName',
        'email',
        'createdAt',
        'updatedAt'
    ]) {
        $options = array_merge([
            'uid' => strval($uid)
        ], $options);

        $body = json_encode([
            'query' => $this->mutation('identifyAccount', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ])->getBody()->getContents();
    }

    /**
     * @param string|int $uid
     * @param null $options
     * 
     * @return
     */
    public function generateIdentityToken(string|int $uid, $options) {

    }

    /**
     * @param string $event
     * @param string|null $accountUid
     * @param $data
     * 
     * @return
     */
    public function track(string $event, string|int $accountUid, $data) {

    }

    /**
     * @param string $urn
     * @param $data
     * 
     * @return
     */
    public function addContent(string $urn, $data) {

    }

    /**
     * @param string $urn
     * @param $data
     * 
     * @return
     */
    public function editContent(string $urn, $data) {

    }

    /**
     * @param string $contentType
     * @param $options
     * 
     * @return
     */
    public function searchContent(string $contentType, $options = null) {

    }

    /**
     * @param string $urn
     * @param array $options
     * 
     * @return
     */
    public function fetchContent(string $urn, array $options) {

    }

    /**
     * @param string $identifier
     * 
     * @return
     */
    public function fetchItem(string $identifier) {

    }

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $orderId
     * 
     * @return
     */
    public function fetchCart(string|int|null $uid, ?string $anonymousUid, ?string $orderId) {

    }

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $gateway
     * @param $gatewayOptions
     * @param string|null $orderId
     * 
     * @return
     */
    public function checkoutCart(string|int|null $uid, ?string $anonymousUid, ?string $gateway, $gatewayOptions, ?string $orderId) {

    }

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param $gatewayResponse
     * @param string|null $orderId
     * 
     * @return
     */
    public function capturePayment(string|int|null $uid, ?string $anonymousUid, $gatewayResponse, ?string $orderId) {

    }
}
