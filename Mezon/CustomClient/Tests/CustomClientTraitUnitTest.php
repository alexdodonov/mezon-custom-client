<?php
namespace Mezon\CustomClient\Tests;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CustomClientTraitUnitTest extends BaseTestUtilities
{

    /**
     * Data provider for sending requests tests
     *
     * @return array test data
     */
    public function sendRequestDataProvider(): array
    {
        return [
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
        $client = $this->getMock([
            'sendPostRequest'
        ]);
        $client->method('sendPostRequest')->willReturn([
            'return',
            1
        ]);
        $client->setPutTraitMethod(true);
        $client->setDeleteTraitMethod(true);

        // test body
        $result = $client->$methodName('/end-point/');

        // assertions
        $this->assertEquals('return', $result[0]);
    }
}
