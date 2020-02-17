<?php
namespace Mezon\CustomClient;

/**
 * Class CustomClient
 *
 * @package Mezon
 * @subpackage CustomClient
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/07)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Custom API client class
 */
class CustomClient
{

    /**
     * Server host
     *
     * @var string
     */
    protected $url = false;

    /**
     * Headers
     *
     * @var array
     */
    protected $headers = false;

    /**
     * Idempotence key
     *
     * @var string
     */
    protected $idempotencyKey = '';

    /**
     * Constructor
     *
     * @param string $uRL
     *            Service URL
     * @param array $headers
     *            HTTP headers
     */
    public function __construct(string $uRL, array $headers = [])
    {
        if ($uRL === false || $uRL === '') {
            throw (new \Exception(
                'Service URL must be set in class ' . __CLASS__ . ' extended in ' . get_called_class() .
                ' and called from ' . ($_SERVER['SERVER_NAME'] ?? 'console') . ($_SERVER['REQUEST_URI'] ?? ''),
                - 23));
        }

        $this->url = rtrim($uRL, '/');

        $this->headers = $headers;
    }

    /**
     * Method send request to the URL
     *
     * @param string $uRL
     *            URL
     * @param array $headers
     *            Headers
     * @param string $method
     *            Request HTTP Method
     * @param array $data
     *            Request data
     * @return array Response body and HTTP code
     * @codeCoverageIgnore
     */
    protected function sendRequest(string $uRL, array $headers, string $method, array $data = []): array
    {
        return \Mezon\CustomClient\CurlWrapper::sendRequest($uRL, $headers, $method, $data);
    }

    /**
     * Method gets result and validates it.
     *
     * @param string $uRL
     *            Request URL
     * @param int $code
     *            Response HTTP code
     * @return mixed Request result
     */
    protected function dispatchResult(string $uRL, int $code)
    {
        if ($code == 404) {
            throw (new \Exception("URL: $uRL not found"));
        } elseif ($code == 400) {
            throw (new \Exception("Bad request on URL $uRL"));
        } elseif ($code == 403) {
            throw (new \Exception("Auth error"));
        }
    }

    /**
     * Method returns common headers
     *
     * @return array Headers
     */
    protected function getCommonHeaders(): array
    {
        $result = [];

        if ($this->headers !== false) {
            $result = $this->headers;
        }

        if ($this->idempotencyKey !== '') {
            $result[] = 'Idempotency-Key: ' . $this->idempotencyKey;
        }

        $result[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0';

        return $result;
    }

    /**
     * Method compiles post headers
     *
     * @return array Header
     */
    protected function getFormHeaders(): array
    {
        $fullHeaders = $this->getCommonHeaders();

        $fullHeaders[] = 'Content-type: application/x-www-form-urlencoded';

        return $fullHeaders;
    }

    /**
     * Method sends request on server
     *
     * @param string $method
     *            HTTP method (POST|PUT|DELETE)
     * @param string $endpoint
     *            Calling endpoint
     * @param array $data
     *            Request data
     */
    protected function sendFormRequest(string $method, string $endpoint, array $data = [])
    {
        $fullURL = $this->url . '/' . ltrim($endpoint, '/');

        list ($body, $code) = $this->sendRequest($fullURL, $this->getFormHeaders(), $method, $data);

        $this->dispatchResult($fullURL, $code);

        return $body;
    }

    /**
     * Method sends POST request to server
     *
     * @param string $endpoint
     *            Calling endpoint
     * @param array $data
     *            Request data
     * @return mixed Result of the request
     */
    public function sendPostRequest(string $endpoint, array $data = [])
    {
        return $this->sendFormRequest('POST', $endpoint, $data);
    }

    /**
     * Method sends PUT request to server
     *
     * @param string $endpoint
     *            Calling endpoint
     * @param array $data
     *            Request data
     * @return mixed Result of the request
     */
    public function sendPutRequest(string $endpoint, array $data = [])
    {
        return $this->sendFormRequest('PUT', $endpoint, $data);
    }

    /**
     * Method sends DELETE request to server
     *
     * @param string $endpoint
     *            Calling endpoint
     * @param array $data
     *            Request data
     * @return mixed Result of the request
     */
    public function sendDeleteRequest(string $endpoint, array $data = [])
    {
        return $this->sendFormRequest('DELETE', $endpoint, $data);
    }

    /**
     * Method sends GET request to server.
     *
     * @param string $endpoint
     *            Calling endpoint.
     * @return mixed Result of the remote call.
     */
    public function sendGetRequest(string $endpoint)
    {
        $fullURL = $this->url . '/' . ltrim($endpoint, '/');

        $fullURL = str_replace(' ', '%20', $fullURL);

        list ($body, $code) = $this->sendRequest($fullURL, $this->getCommonHeaders(), 'GET');

        $this->dispatchResult($fullURL, $code);

        return $body;
    }

    /**
     * Method sets idempotence key.
     * To remove the key just call this method the second time with the '' parameter
     *
     * @param string $key
     *            Idempotence key
     */
    public function setIdempotencyKey(string $key)
    {
        $this->idempotencyKey = $key;
    }

    /**
     * Method returns idempotency key
     *
     * @return string Idempotency key
     */
    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    /**
     * Method returns URL
     *
     * @return string URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Method returns headers
     *
     * @return array Headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
