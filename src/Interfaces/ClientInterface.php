<?php

namespace Dashx\Php\Interfaces;

interface ClientInterface {
    /**
     * @param string $urn
     * @param array $options
     * @param array $selectors
     *
     * @return
     */
    public function deliver(string $urn, array $options, array $selectors);

    /**
     * @param string|int $uid
     * @param array $options
     * @param array $selectors
     *
     * @return
     */
    public function identify(string|int|array $uid, array $options, array $selectors);

    /**
     * @param string $event
     * @param string|null $accountUid
     * @param $data
     * @param array $selectors
     *
     * @return
     */
    public function track(string $event, string|int $accountUid, $data, array $selectors);

    /**
     * @param string $urn
     * @param $data
     * @param array $selectors
     *
     * @return
     */
    public function addContent(string $urn, $data, array $selectors);

    /**
     * @param string $urn
     * @param $data
     * @param array $selectors
     *
     * @return
     */
    public function editContent(string $urn, $data, array $selectors);

    /**
     * @param string $contentType
     * @param array $options
     *
     * @return
     */
    public function searchContent(string $contentType, array $options);

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
     * @param array $selectors
     *
     * @return
     */
    public function fetchCart(string|int|null $uid, ?string $anonymousUid, ?string $orderId, array $selectors);

    /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $gateway
     * @param string|null $orderId
     * @param array $gatewayOptions
     * @param array $selectors
     *
     *
     * @return
     */
    public function checkoutCart(string|int|null $uid, ?string $anonymousUid, ?string $gateway, ?string $orderId, array $gatewayOptions, array $selectors);

     /**
     * @param string|int|null $uid
     * @param string|null $anonymousUid
     * @param string|null $orderId
     * @param array $gatewayResponse
     * @param array $selectors
     *
     * @return
     */
    public function capturePayment(string|int|null $uid, ?string $anonymousUid, ?string $orderId, array $gatewayResponse, array $selectors);

    /**
     * @param string|int $uid
     *
     * @return
     */
    public function fetchContacts(string|int $uid);

    /**
     * @param string|int $uid
     * @param array $selectors
     *
     * @return
     */
    public function fetchStoredPreferences(string|int $uid, array $selectors);

    /**
     * @param string|int $uid
     * @param $preferenceData
     * @param array $selectors
     *
     * @return
     */
    public function saveStoredPreferences(string|int $uid, $preferenceData, array $selectors);
}
