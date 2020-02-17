<?php

class CustomClientTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing get method
     */
    public function testGetMethod()
    {
        $client = new \Mezon\CustomClient\CustomClient('http://yandex.ru/');

        $client->getRequest('unexisting');

        $this->addToAssertionCount(1);
    }

    /**
     * Testing post metthod
     */
    public function testPostMethod()
    {
        $client = new \Mezon\CustomClient\CustomClient('http://yandex.ru/');

        $client->postRequest('unexisting');

        $this->addToAssertionCount(1);
    }
}
