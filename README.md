# Custom client for external services
[![Build Status](https://travis-ci.com/alexdodonov/mezon-custom-client.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-custom-client) [![codecov](https://codecov.io/gh/alexdodonov/mezon-custom-client/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-custom-client) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-custom-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-custom-client/?branch=master)

## Installation

Just print

```
composer require mezon/custom-client
```

## Reasons to use
- it will help ypu to create REST API clients lightning fast! Just look at this [example of creation Jira API Client](https://github.com/alexdodonov/mezon-jira-client)
- is has 100% code coverage
- it has 10.0 points on Scrutinizer

## Sending requests

You can send different types of requests.

```PHP
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

## Idempotency keys

To be sure that your data modification request are executed only once - use idempotency keys.

They are passed in the headers and can be set like this:

```php
$client->setIdempotencyKey('some hash, like md5 or GUID or something like that');
```

After that in all of your requests the header Idempotency-Key will be added.

Note that this key will not be dropped automatically. You shold drop it manually:

```php
$client->setIdempotencyKey(''); // this call drops the key
```