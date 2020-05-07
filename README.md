# Custom client for external services [![Build Status](https://travis-ci.com/alexdodonov/mezon-custom-client.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-custom-client) [![codecov](https://codecov.io/gh/alexdodonov/mezon-custom-client/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-custom-client) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-custom-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-custom-client/?branch=master)

## Installation

Just print

```
composer require mezon/custom-client
```

## Sending requests

You can send different types of requests.

``PHP
$client = new \Mezon\CustomClient\CustomClient();

// sending get request
$client->sendGetRequest('https://your-api/end/point/?param=1');

// sending post request
$client->sendPostRequest('https://your-api/end/point/', ['param' => 1]);

// sending put request
$client->sendPutRequest('https://your-api/end/point/', ['param' => 1]);

// sending delete request
$client->sendDeleteRequest('https://your-api/end/point/', ['param' => 1]);
```

## Idempotence keys

tba