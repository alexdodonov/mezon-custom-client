<?php
namespace Mezon\CustomClient\Tests\SendRequestException;

use Exception;
use Mezon\CustomClient\Tests\TestClient;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SendPostRequestExceptionUnitTest extends SendRequestException
{

    /**
     * Testing result dispatcher
     *
     * @param int $httpCode
     *            code of the response
     * @dataProvider resultDispatchingDataProvider
     */
    public function testSendPostRequestException(int $httpCode): void
    {
        // setup
        $client = new TestClient('http://ya.ru');
        $client->httpCode = $httpCode;

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $client->sendPostRequest('/end-point/');
    }
}
