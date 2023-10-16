<?php

namespace Dashx\Php;

use Ramsey\Uuid\Uuid;

use Dashx\Php\Interfaces\ClientInterface;
use Error;

class Client extends ApiClient implements ClientInterface {
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
     * Target environment
     * 
     * @var
     */
    private $target_environment;

    /**
     * Cart query
     * 
     * @var
     */
    public static $cart = [
        'id',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'gatewayMeta',
        'currencyCode',
        'orderItems' => [
            'id',
            'quantity',
            'unitPrice',
            'subtotal',
            'discount',
            'tax',
            'total',
            'custom',
            'currencyCode',
            'item' => [
                'id',
                'installationId',
                'name',
                'identifier',
                'description',
                'createdAt',
                'updatedAt',
                'pricings' => [
                    'id',
                    'kind',
                    'amount',
                    'originalAmount',
                    'isRecurring',
                    'recurringInterval',
                    'recurringIntervalUnit',
                    'appleProductIdentifier',
                    'googleProductIdentifier',
                    'currencyCode',
                    'createdAt',
                    'updatedAt',
                ]
            ]
        ],
        'couponRedemptions' => [
            'coupon' => [
                'name',
                'identifier',
                'discountType',
                'discountAmount',
                'currencyCode',
                'expiresAt',
            ]
        ]
    ];

    /**
     * Create a new client instance.
     *
     * @return void
     */
    public function __construct($public_key, $private_key, $target_environment = 'staging', $base_uri = 'https://api.dashx-staging.com') {
        $this->base_uri = $base_uri;
        $this->public_key = $public_key;
        $this->private_key = $private_key;
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
     * Format api response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * 
     * @return array
     */
    protected function formatResponse($response): array {
        $code = $response->getStatusCode();
        $response = json_decode($response->getBody()->getContents(), true);

        if($code === 200) {
            return $response;
        }

        return [
            'data' => null,
            'errors' => [
                [
                    'message' => 'Something went wrong!'
                ]
            ]
        ];
    }

    /**
     * @param string $urn
     * @param null $options
     * @param array $selectors
     * 
     * @return array
     */
    public function deliver($urn, $options = [], $selectors = [
        'id',
        'contentId',
        'content',
        'data',
        'status',
        'sentNotificationsCount',
        'deliveredNotificationsCount',
        'createdAt',
        'updatedAt',
        'name',
        'contentTypeSystemIdentifier',
        'failureReason',
        'isTest',
        'scheduledAt']
    ) {
        $urns = explode('/', $urn);

        $contentTypeIdentifier = $urns[0];
        $contentIdentifier = !empty($urns[1]) ? $urns[1] : null;

        $content = (isset($options['content'])) ? $options['content'] : [];
        $to = (isset($options['to'])) ? $options['to'] : null;
        $cc = (isset($options['cc'])) ? $options['cc'] : null;
        $bcc = (isset($options['bcc'])) ? $options['bcc'] : null;

        $rest = array_diff_key($options, array_flip(['content', 'to', 'cc', 'bcc']));

        if(isset($content['to']) || $to) {
            $content['to'] = isset($content['to']) ? array_merge(...[$content['to']]) : array_merge(...[$to]);
        }

        if(isset($content['cc']) || $cc) {
            $content['cc'] = isset($content['cc']) ? array_merge(...[$content['cc']]) : array_merge(...[$cc]);
        }

        if(isset($content['bcc']) || $bcc) {
            $content['bcc'] = isset($content['bcc']) ? array_merge(...[$content['bcc']]) : array_merge(...[$bcc]);
        }

        if(isset($content['html_body'])) {
            $content['html_body'] = str_replace(["\n", "\r"], '', $content['html_body']);
        }

        $options = array_merge(
            $rest,
            array_merge(
                [
                    'contentTypeIdentifier' => $contentTypeIdentifier,
                    'content' => $content
                ],
                ($contentIdentifier) ? [ 'contentIdentifier' => $contentIdentifier ] : []
            )
        );

        $body = json_encode([
            'query' => $this->mutation('createDelivery', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string|int $uid
     * @param array $options
     * @param array $selectors
     * 
     * @return array
     */
    public function identify(string|int|array $uid, array $options = [], array $selectors = [
        'id',
        'environmentId',
        'email',
        'phone',
        'fullName',
        'name',
        'firstName',
        'lastName',
        'avatar',
        'gender',
        'dateOfBirth',
        'timeZone',
        'uid',
        'anonymousUid',
        'createdAt',
        'updatedAt',
        'hasApiAccess',
        'hasGuiAccess',
        'custom',
        'status',
        'invitationToken',
        'unconfirmedEmail',
        'confirmationDigest',
        'confirmationExpiresAt',
        'regularAccountId',
        'scope'
    ]) {
        if(is_string($uid) || is_numeric($uid)) {
            $options = array_merge($options, [
                'uid' => strval($uid)
            ]);
        }else {
            // uid, anonymousUid, email, phone, name, firstName, lastName, scope
            $options = array_merge([
                'anonymousUid' => Uuid::uuid4()
            ], $uid);
        }

        $body = json_encode([
            'query' => $this->mutation('identifyAccount', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string|int $uid
     * @param array $options
     *
     * @return string
     */
    public function generateIdentityToken(string|int $uid, $options = []) {
        if(!$this->private_key) {
            throw new Error('Private key is not set!');
        }

        $kind = $options['kind'] ?? 'regular';
        $plain_text = 'v1;' . $kind . ';' . $uid;
        $key = $this->private_key;

        $cipher = 'aes-256-gcm';
        $iv_len = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($iv_len);
        $tag = '';

        $ciphertext = openssl_encrypt($plain_text, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);

        return strtr(base64_encode($iv.$ciphertext.$tag), '+/=', '._-');
    }

    /**
     * @param string $event
     * @param string|null $accountUid
     * @param $data
     * @param array $selectors
     * 
     * @return array
     */
    public function track(string $event, string|int $accountUid, $data, $selectors = ['success']) {
        $options = [
            'event' => $event,
            'accountUid' => strval($accountUid),
            'data' => $data,
        ];

        $body = json_encode([
            'query' => $this->mutation('trackEvent', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string $urn
     * @param $data
     * @param array $selectors
     * 
     * @return array
     */
    public function addContent(string $urn, $data, $selectors = ['id', 'identifier', 'position', 'data']) {
        $options = [
            'content' => null,
            'contentType' => null,
            'data' => is_array($data) ? str_replace('"', '\"', json_encode($data)) : $data
        ];

        if(str_contains($urn, '/')) {
            $urns = explode('/', $urn);

            $options['contentType'] = $urns[0];
            $options['content'] = $urns[1];
        }else {
            $options['contentType'] = $urn;
        }

        $body = json_encode([
            'query' => $this->mutation('addContent', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string $urn
     * @param $data
     * @param array $selectors
     * 
     * @return array
     */
    public function editContent(string $urn, $data, $selectors = ['id', 'identifier', 'position', 'data']) {
        $options = [
            'content' => null,
            'contentType' => null,
            'data' => is_array($data) ? str_replace('"', '\"', json_encode($data)) : $data
        ];

        if(str_contains($urn, '/')) {
            $urns = explode('/', $urn);

            $options['contentType'] = $urns[0];
            $options['content'] = $urns[1];
        }else {
            $options['contentType'] = $urn;
        }

        $body = json_encode([
            'query' => $this->mutation('editContent', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string $contentType
     * @param array $options
     * 
     * @return array
     */
    public function searchContent(string $contentType, $options = []) {
        // TODO: implement and test filters
        $options = array_merge($options, [
            'contentType' => $contentType
        ]);

        $body = json_encode([
            'query' => $this->query('searchContent', $options, []),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string $urn
     * @param array $options
     * 
     * @return array
     */
    public function fetchContent(string $urn, array $options = []) {
        if(!str_contains($urn, '/')) {
            throw new Error(`URN must be of form: {contentType}/{content}`);
        }

        $urns = explode('/', $urn);

        $options = array_merge($options, [
            'contentType' => $urns[0],
            'content' => $urns[1]
        ]);

        $body = json_encode([
            'query' => $this->query('fetchContent', $options, []),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string $identifier
     * @param array $selectors
     * 
     * @return array
     */
    public function fetchItem(string $identifier, array $selectors = [
        'id',
        'installationId',
        'kind',
        'name',
        'identifier',
        'description',
        'createdAt',
        'itemCategoryMemberships' => [
            'id'
        ],
        'updatedAt',
    ]) {
        $options = [
            'identifier' => $identifier
        ];

        $body = json_encode([
            'query' => $this->query('fetchItem', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);

        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $orderId
     * @param array $selectors
     * 
     * @return array
     */
    public function fetchCart(string|int|null $uid, ?string $anonymousUid, ?string $orderId, array $selectors = []) {
        if(empty($selectors)) {
            $selectors = self::$cart;
        }

        $options = [
            'accountUid' => strval($uid)
        ];

        if($anonymousUid) {
            $options['accountAnonymousUid'] = $anonymousUid;
        }

        if($orderId) {
            $options['orderId'] = $orderId;
        }

        $body = json_encode([
            'query' => $this->query('fetchCart', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);
        
        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $gateway
     * @param string|null $orderId
     * @param array $gatewayOptions
     * @param array $selectors
     * 
     * @return array
     */
    public function checkoutCart(string|int|null $uid, ?string $anonymousUid, ?string $gateway, ?string $orderId, array $gatewayOptions = [], array $selectors = []) {
        if(empty($selectors)) {
            $selectors = self::$cart;
        }

        $options = [
            'accountUid' => strval($uid)
        ];

        if($anonymousUid) {
            $options['accountAnonymousUid'] = $anonymousUid;
        }

        if($orderId) {
            $options['orderId'] = $orderId;
        }

        if($gateway) {
            $options['gatewayIdentifier'] = $gateway;
        }

        if(!empty($gatewayOptions)) {
            $options['gatewayOptions'] = $gatewayOptions;
        }

        $body = json_encode([
            'query' => $this->mutation('checkoutCart', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);
        
        return $this->request([
            'body' => $body
        ]);
    }

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $orderId
     * @param array $gatewayResponse
     * @param array $selectors
     * 
     * @return array
     */
    public function capturePayment(string|int|null $uid, ?string $anonymousUid, ?string $orderId, array $gatewayResponse = [], array $selectors = []) {
        if(empty($selectors)) {
            $selectors = self::$cart;
        }

        $options = [
            'accountUid' => strval($uid)
        ];

        if($anonymousUid) {
            $options['accountAnonymousUid'] = $anonymousUid;
        }

        if($orderId) {
            $options['orderId'] = $orderId;
        }

        if(!empty($gatewayResponse)) {
            $options['gatewayResponse'] = $gatewayResponse;
        }

        $body = json_encode([
            'query' => $this->mutation('capturePayment', $options, $selectors),
            'variables' => [
                'input' => $options
            ]
        ]);
        
        return $this->request([
            'body' => $body
        ]);
    }
}
