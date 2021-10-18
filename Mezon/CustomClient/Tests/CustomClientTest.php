<?php
namespace Mezon\CustomClient\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CustomClient;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CustomClientTest extends TestCase
{

    /**
     * Testing get method
     */
    public function testGetMethod(): void
    {
        $client = new CustomClient('http://yandex.ru/');

        $client->sendGetRequest('unexisting');

        // TODO refactor this test
        $this->assertTrue(true);
    }

    /**
     * Testing post metthod
     */
    public function testPostMethod(): void
    {
        $client = new CustomClient('http://yandex.ru/');

        $client->sendPostRequest('unexisting');

        // TODO refactor this test
        $this->assertTrue(true);
    }
}
