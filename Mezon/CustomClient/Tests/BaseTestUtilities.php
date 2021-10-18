<?php
namespace Mezon\CustomClient\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CustomClient;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BaseTestUtilities extends TestCase
{

    /**
     * Creating mock
     *
     * @return object mock object
     */
    protected function getMock(array $methods = [
        'sendRequest'
    ]): object
    {
        return $this->getMockBuilder(CustomClient::class)
            ->onlyMethods($methods)
            ->setConstructorArgs([
            'http://ya.ru'
        ])
            ->getMock();
    }
}
