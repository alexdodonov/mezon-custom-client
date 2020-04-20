<?php

require_once(__DIR__.'/../CustomClient.php');

class CustomClientTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing get method
     */
    public function testGetMethod()
    {
        $client = new \Mezon\CustomClient\CustomClient('http://yandex.ru/');

        $client->sendGetRequest('unexisting');

        $this->addToAssertionCount(1);
    }

    /**
     * Testing post metthod
     */
    public function testPostMethod()
    {
        $client = new \Mezon\CustomClient\CustomClient('http://yandex.ru/');

        $client->sendPostRequest('unexisting');

        $this->addToAssertionCount(1);
    }
}
