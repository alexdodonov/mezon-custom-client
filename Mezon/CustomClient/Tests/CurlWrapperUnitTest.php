<?php
namespace Mezon\CustomClient\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CurlWrapper;

class CurlWrapperUnitTest extends TestCase
{

    /**
     * URL for testing purposes
     *
     * @var string
     */
    private $url = 'http://google.com';

    /**
     * Testing GET requests
     */
    public function testGetRequest()
    {
        // test body
        list ($body, $code) = CurlWrapper::sendRequest($this->url, [], 'GET');

        // assertions
        $this->assertStringContainsString('', $body, 'Invalid HTML was returned');
        $this->assertEquals(301, $code, 'Invalid HTTP code');
    }

    /**
     * Testing POST requests
     */
    public function testPostRequest()
    {
        // test body
        list ($body, $code) = CurlWrapper::sendRequest($this->url, [], 'POST', [
            'data' => 1
        ]);

        // assertions
        $this->assertStringContainsString('', $body, 'Invalid HTML was returned');
        $this->assertEquals(405, $code, 'Invalid HTTP code');
    }

    /**
     * Testing JSON request
     */
    public function testJsonRequest(): void
    {
        // test body
        list ($body, $code) = CurlWrapper::sendRequest($this->url, [
            'Content-type: application/json'
        ], 'POST', [
            'data' => 1
        ]);
        $body = json_decode($body, false);

        // assertions
        $this->assertEquals(405, $code);
    }
}
