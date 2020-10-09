<?php
namespace Mezon\CustomClient\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CustomClient;

class CustomClientTest extends TestCase
{

    /**
     * Testing get method
     */
    public function testGetMethod()
    {
        $client = new CustomClient('http://yandex.ru/');

        $client->sendGetRequest('unexisting');

        $this->addToAssertionCount(1);
    }

    /**
     * Testing post metthod
     */
    public function testPostMethod()
    {
        $client = new CustomClient('http://yandex.ru/');

        $client->sendPostRequest('unexisting');

        $this->addToAssertionCount(1);
    }
}
