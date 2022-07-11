<?php

namespace Dashx\Php\Interfaces;

interface ClientInterface {
    // TODO: Fix options with Record
    /**
     * @param string $urn
     * @param array $options
     * @param array $selectors
     * 
     * @return
     */
    public function deliver(string $urn, array $options = [], array $selectors = []);

    /**
     * @param string|int $uid
     * @param array $options
     * @param array $selectors
     * 
     * @return
     */
    public function identify(string|int|array $uid, array $options, array $selectors);

    // TODO: Fix options with GenerateIdentityTokenOptions
    /**
     * @param string|int $uid
     * @param null $options
     * 
     * @return
     */
    public function generateIdentityToken(string|int $uid, $options);

    // TODO: Fix data with Record
    /**
     * @param string $event
     * @param string|null $accountUid
     * @param $data
     * @param array $selectors
     * 
     * @return
     */
    public function track(string $event, string|int $accountUid, $data, array $selector);

    // TODO: Fix data with Record
    /**
     * @param string $urn
     * @param $data
     * 
     * @return
     */
    public function addContent(string $urn, $data);

    // TODO: Fix data with Record
    /**
     * @param string $urn
     * @param $data
     * 
     * @return
     */
    public function editContent(string $urn, $data);

    // TODO: Fix options with ContentOptions
    /**
     * @param string $contentType
     * @param $options
     * 
     * @return
     */
    public function searchContent(string $contentType, $options = null);

    // TODO: Fix option with FetchContentOptions
    /**
     * @param string $urn
     * @param array $options
     * 
     * @return
     */
    public function fetchContent(string $urn, array $options);

    /**
     * @param string $identifier
     * 
     * @return
     */
    public function fetchItem(string $identifier);

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $orderId
     * 
     * @return
     */
    public function fetchCart(string|int|null $uid, ?string $anonymousUid, ?string $orderId);

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $gateway
     * @param $gatewayOptions
     * @param string|null $orderId
     * 
     * @return
     */
    public function checkoutCart(string|int|null $uid, ?string $anonymousUid, ?string $gateway, $gatewayOptions, ?string $orderId);

     /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param $gatewayResponse
     * @param string|null $orderId
     * 
     * @return
     */
    public function capturePayment(string|int|null $uid, ?string $anonymousUid, $gatewayResponse, ?string $orderId);
}
