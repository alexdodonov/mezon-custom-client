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
    protected $url = '';

    /**
     * Headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Idempotence key
     *
     * @var string
     */
    protected $idempotencyKey = '';

    /**
     * Trait PUT requests as POST
     *
     * @var boolean
     */
    private $traitPutAsPost = false;

    /**
     * Trait DELETE requests as POST
     *
     * @var boolean
     */
    private $traitDeleteAsPost = false;

    /**
     * Constructor
     *
     * @param string $url
     *            service URL
     * @param array $headers
     *            HTTP headers
     */
    public function __construct(string $url = '', array $headers = [])
    {
        if ($url !== '') {
            $this->url = rtrim($url, '/');
        } else {
            $this->url = '';
        }

        $this->headers = $headers;
    }

    /**
     * Method send request to the URL
     *
     * @param string $url
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
    protected function sendRequest(string $url, array $headers, string $method, array $data = []): array
    {
        return CurlWrapper::sendRequest($url, $headers, $method, $data);
    }

    /**
     * Method gets result and validates it.
     *
     * @param string $url
     *            request URL
     * @param int $code
     *            response HTTP code
     * @param mixed $body
     *            response body
     * @return mixed Request result
     */
    protected function dispatchResult(string $url, int $code, $body)
    {
        // TODO send body in exception because even if the error code was returned the body may not be nulled
        if ($code == 0) {
            throw (new \Exception("No response from URL : " . $url));
        } elseif ($code == 404) {
            throw (new \Exception("URL: $url not found"));
        } elseif ($code == 400) {
            throw (new \Exception("Bad request on URL $url"));
        } elseif ($code == 403) {
            throw (new \Exception("Auth error"));
        }

        return $body;
    }

    /**
     * Method returns common headers
     *
     * @return array Headers
     */
    protected function getCommonHeaders(): array
    {
        $result = $this->headers;

        if ($this->idempotencyKey !== '') {
            $result[] = 'Idempotency-Key: ' . $this->idempotencyKey;
        }

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

        if (! CurlWrapper::isHeaderExists($this->headers, 'Content-type')) {
            $fullHeaders[] = 'Content-type: application/x-www-form-urlencoded';
        }

        return $fullHeaders;
    }

    /**
     * Method asserts that $url is set
     */
    private function assertUrl(): void
    {
        if ($this->url == '') {
            throw (new \Exception('Service URL must be set in class ' . __CLASS__ . ' extended in ' . get_called_class() . ' and called from ' . ($_SERVER['SERVER_NAME'] ?? 'console') . ($_SERVER['REQUEST_URI'] ?? ''), - 23));
        }
    }

    /**
     * Method sends request on server
     *
     * @param string $method
     *            HTTP method (POST|PUT|DELETE)
     * @param string $endpoint
     *            Calling endpoint
     * @param mixed $data
     *            Request data
     *            @return mixed request result
     */
    protected function sendFormRequest(string $method, string $endpoint, array $data = [])
    {
        $this->assertUrl();

        $fullUrl = $this->url . '/' . ltrim($endpoint, '/');

        list ($body, $code) = $this->sendRequest($fullUrl, $this->getFormHeaders(), $method, $data);

        return $this->dispatchResult($fullUrl, $code, $body);
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
        if ($this->traitPutAsPost) {
            return $this->sendPostRequest($endpoint, $data);
        } else {
            return $this->sendFormRequest('PUT', $endpoint, $data);
        }
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
        if ($this->traitDeleteAsPost) {
            return $this->sendPostRequest($endpoint, $data);
        } else {
            return $this->sendFormRequest('DELETE', $endpoint, $data);
        }
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
        $this->assertUrl();

        $fullUrl = $this->url . '/' . ltrim($endpoint, '/');

        $fullUrl = str_replace(' ', '%20', $fullUrl);

        list ($body, $code) = $this->sendRequest($fullUrl, $this->getCommonHeaders(), 'GET');

        return $this->dispatchResult($fullUrl, $code, $body);
    }

    /**
     * Method sets idempotence key.
     * To remove the key just call this method the second time with the '' parameter
     *
     * @param string $key
     *            Idempotence key
     */
    public function setIdempotencyKey(string $key): void
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
     * Method sets URL
     *
     * @param string $url
     *            URL
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
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

    /**
     * Method traits PUT as POST
     *
     * @param bool $flag
     */
    public function setPutTraitMethod(bool $flag): void
    {
        $this->traitPutAsPost = $flag;
    }

    /**
     * Method traits DELETE as POST
     *
     * @param bool $flag
     */
    public function setDeleteTraitMethod(bool $flag): void
    {
        $this->traitDeleteAsPost = $flag;
    }
}
