<?php
require_once (__DIR__ . '/../CustomClient.php');

use Mezon\CustomClient\CustomClient;

class TestClient extends CustomClient
{

    public function getCommonHeadersPublic(): array
    {
        return parent::getCommonHeaders();
    }
}

class CustomClientUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing invalid construction
     */
    public function testConstructorInvalid(): void
    {
        // assertions
        $this->expectException(Exception::class);

        // setup and test body
        $client = new CustomClient('');
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
     * Testing getters/setters for the field
     */
    public function testIdempotencyGetSet(): void
    {
        // setup
        $client = new CustomClient('some url', []);

        // test bodyand assertions
        $client->setIdempotencyKey('i-key');

        $this->assertEquals('i-key', $client->getIdempotencyKey(), 'Invalid idempotency key');
    }

    /**
     * Creating mock
     */
    protected function getMock(): object
    {
        return $this->getMockBuilder(CustomClient::class)
            ->setMethods([
            'sendRequest'
        ])
            ->disableOriginalConstructor()
            ->getMock();
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
     * Testing setting idempotency key
     */
    public function testSetIdempotencyKey(): void
    {
        // setup
        $client = new TestClient('http://unit.test');
        $client->setIdempotencyKey('iKey');

        // test body and assertions
        $this->assertStringContainsString('iKey', implode('', $client->getCommonHeadersPublic()));
    }
}
