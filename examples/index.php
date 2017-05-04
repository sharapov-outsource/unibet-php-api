<?php
/**
 * Unibet PHP implementation.
 *
 * (c) Alexander Sharapov <alexander@sharapov.biz>
 * http://sharapov.biz/
 *
 */

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