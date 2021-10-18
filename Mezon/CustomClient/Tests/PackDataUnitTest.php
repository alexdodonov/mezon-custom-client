<?php
namespace Mezon\CustomClient\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CurlWrapper;

/**
 * 
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PackDataUnitTest extends TestCase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function packDataDataProvider(): array
    {
        return [
            // #0, list of scalars
            [
                [
                    'a' => 1,
                    'b' => 2
                ],
                'a=1&b=2'
            ],
            // #1, nested array
            [
                [
                    'arr' => [
                        'key' => 'value'
                    ]
                ],
                'arr[key]=value'
            ],
            // #2, deeper nested arrays
            [
                [
                    'field' => [
                        [
                            'key' => 'value'
                        ]
                    ]
                ],
                'field[0][key]=value'
            ]
        ];
    }

    /**
     * Testing method packData
     *
     * @param array $data
     *            data to be packed
     * @param string $expected
     *            expected result
     * @dataProvider packDataDataProvider
     */
    public function testPackData(array $data, string $expected): void
    {
        // test body
        $result = CurlWrapper::packData($data);

        // assertions
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider
     *
     * @return array Testing data
     */
    public function packDataExceptionDataProvider(): array
    {
        return [
            // #0, the nearest object
            [
                [
                    'obj' => new \stdClass()
                ]
            ],
            // #1, deeper object
            [
                [
                    'obj1' => [
                        'obj2' => new \stdClass()
                    ]
                ]
            ]
        ];
    }

    /**
     * Testing exception throwing
     *
     * @param array $data
     *            test data
     * @dataProvider packDataExceptionDataProvider
     */
    public function testExceptionWhilePackingData(array $data): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // test body
        CurlWrapper::packData($data);
    }
}
