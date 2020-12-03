<?php
namespace Mezon\CustomClient\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\CustomClient\CustomClient;

class CustomClientIdempotencyKeyUnitTest extends TestCase
{

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
