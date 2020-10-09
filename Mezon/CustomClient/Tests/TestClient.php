<?php
namespace Mezon\CustomClient\Tests;

use Mezon\CustomClient\CustomClient;

class TestClient extends CustomClient
{

    public function getCommonHeadersPublic(): array
    {
        return parent::getCommonHeaders();
    }
}
