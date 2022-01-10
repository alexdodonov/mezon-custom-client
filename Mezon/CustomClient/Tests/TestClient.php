<?php
namespace Mezon\CustomClient\Tests;

use Mezon\CustomClient\CustomClient;

class TestClient extends CustomClient
{

    /**
     * HTTP code
     *
     * @var integer
     */
    public $httpCode = 0;

    /**
     * Getting headers
     *
     * @return array
     */
    public function getCommonHeadersPublic(): array
    {
        return parent::getCommonHeaders();
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
        return [
            '',
            $this->httpCode
        ];
    }
}
