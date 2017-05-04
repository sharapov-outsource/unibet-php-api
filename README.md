Unibet PHP API
======================

This is a simple PHP implementation for [Unibet API](https://developer.unibet.com/docs).

Installation
------------

You can either get the files from GIT or you can install the library via [Composer](getcomposer.org). To use Composer, simply add the following to your `composer.json` file.

```json
{
    "require": {
        "sharapov/unibet-php-api": "dev-master"
    }
}
```

How to use it?
--------------

To initialize the API, you'll need to pass an array with your `application key` and `application_id`.

```php
require_once "../vendor/autoload.php";

$api = new \Sharapov\UnibetPHP\UnibetAPI( [
                                            'app_id'  => 'APP_ID',
                                            'app_key' => 'APP_KEY'
                                          ] );

// Request examples

// /sportsbook/groups
$response = $api->sportsbook()->groups()->json();

// /sportsbook/betoffer/event/{eventId}.{responseformat}
$response = $api->sportsbook()->betoffer()->event('EVENT_ID')->json();

// More examples are on https://developer.unibet.com/docs

print '<pre>';
print_r( json_decode($response->getBody()->getContents()) );
print '</pre>';
```