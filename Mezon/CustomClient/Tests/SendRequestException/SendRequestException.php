<?php
namespace Mezon\CustomClient\Tests\SendRequestException;

use Mezon\CustomClient\Tests\BaseTestUtilities;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SendRequestException extends BaseTestUtilities
{

    /**
     * Data provider for the test testResultDispatching
     *
     * @return array testing data
     */
    public function resultDispatchingDataProvider(): array
    {
        return [
            [
                400
            ],
            [
                403
            ],
            [
                404
            ],
            [
                0
            ]
        ];
    }
}
