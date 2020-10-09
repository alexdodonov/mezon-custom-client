<?php
namespace Mezon\CustomClient\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CurlWrapper;

class CurlWrapperUnitTest extends TestCase
{

    /**
     * Testing GET requests
     */
    public function testGetRequest()
    {
        list ($body, $code) = CurlWrapper::sendRequest('http://google.com', [], 'GET');

        $this->assertStringContainsString('', $body, 'Invalid HTML was returned');
        $this->assertEquals(301, $code, 'Invalid HTTP code');
    }

    /**
     * Testing POST requests
     */
    public function testPostRequest()
    {
        list ($body, $code) = CurlWrapper::sendRequest('http://google.com', [], 'POST', [
            'data' => 1
        ]);

        $this->assertStringContainsString('', $body, 'Invalid HTML was returned');
        $this->assertEquals(405, $code, 'Invalid HTTP code');
    }
}
