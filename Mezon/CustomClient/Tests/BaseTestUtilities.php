<?php
namespace Mezon\CustomClient\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CustomClient;

class BaseTestUtilities extends TestCase
{

    /**
     * Creating mock
     */
    protected function getMock(array $methods = [
        'sendRequest'
    ]): object
    {
        return $this->getMockBuilder(CustomClient::class)
            ->setMethods($methods)
            ->setConstructorArgs([
            'http://ya.ru'
        ])
            ->getMock();
    }
}
