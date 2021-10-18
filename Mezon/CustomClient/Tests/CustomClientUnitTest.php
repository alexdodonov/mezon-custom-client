<?php
namespace Mezon\CustomClient\Tests;

use Exception;
use Mezon\CustomClient\CustomClient;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CustomClientUnitTest extends BaseTestUtilities
{

    /**
     * Testing invalid construction
     */
    public function testConstructorInvalid(): void
    {
        // setup and test body
        $client = new CustomClient('');

        $this->assertEquals('', $client->getUrl());
    }

    /**
     * Testing valid construction
     */
    public function testConstructorValid(): void
    {
        $client = new CustomClient('http://yandex.ru/', [
            'header'
        ]);

        $this->assertEquals('http://yandex.ru', $client->getUrl(), 'Invalid URL');
        $this->assertEquals(1, count($client->getHeaders()), 'Invalid headers');
    }

    /**
     * Data provider for sending requests tests
     *
     * @return array test data
     */
    public function sendRequestDataProvider(): array
    {
        return [
            [
                'sendGetRequest'
            ],
            [
                'sendPostRequest'
            ],
            [
                'sendPutRequest'
            ],
            [
                'sendDeleteRequest'
            ]
        ];
    }

    /**
     * Testing sendGetRequest method
     *
     * @param string $methodName
     *            Method name to be tested
     * @dataProvider sendRequestDataProvider
     */
    public function testSendRequest(string $methodName): void
    {
        // setup
        $client = $this->getMock();
        $client->method('sendRequest')->willReturn([
            'return',
            1
        ]);

        // test body
        $result = $client->$methodName('/end-point/');

        // assertions
        $this->assertEquals('return', $result);
    }

    /**
     * Data provider for the test testResultDispatching
     *
     * @return array testing data
     */
    public function resultDispatchingDataProvider(): array
    {
        $codes = [
            400,
            403,
            404,
            0
        ];
        $methods = [
            'sendGetRequest',
            'sendPostRequest',
            'sendPutRequest',
            'sendDeleteRequest'
        ];
        $return = [];

        foreach ($codes as $code) {
            foreach ($methods as $method) {
                $return[] = [
                    $code,
                    $method
                ];
            }
        }

        return $return;
    }

    /**
     * Testing result dispatcher
     *
     * @param int $httpCode
     *            code of the response
     * @param string $methodName
     *            name of the testing methodd
     * @dataProvider resultDispatchingDataProvider
     */
    public function testResultDispatching(int $httpCode, string $methodName): void
    {
        // setup
        $client = $this->getMock();
        $client->method('sendRequest')->willReturn([
            'result',
            $httpCode
        ]);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $client->$methodName('/end-point/');
    }

    /**
     * Testing method assertUrl
     */
    public function testInvalidCall(): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup
        $client = new CustomClient('');

        // test body
        $client->sendGetRequest('some-end-point');
    }

    /**
     * Testing method
     */
    public function testSetUrl(): void
    {
        // setup
        $client = new CustomClient();

        // test body
        $client->setUrl('testing-url');

        // assertions
        $this->assertEquals('testing-url', $client->getUrl());
    }
}
