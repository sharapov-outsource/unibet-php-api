<?php
/**
 * Unibet PHP implementation.
 *
 * (c) Alexander Sharapov <alexander@sharapov.biz>
 * http://sharapov.biz/
 *
 */

namespace Sharapov\UnibetPHP;

use GuzzleHttp\Client;

class UnibetAPI {

  /**
   * Default API options
   */
  private $_clientOptions = [
    'base_uri' => "http://api.unicdn.net/v1/feeds/",
    'timeout'  => 5
  ];

  private $_queryParams = [];

  private $_endpoint;

  /**
   * UnibetAPI constructor.
   *
   * @param array $params
   *
   * @throws \Sharapov\UnibetPHP\UnibetAPIException
   */
  public function __construct( array $params = [], $endpoint = null ) {
    if ( ! is_null( $endpoint ) ) {
      $this->_endpoint = $endpoint;
    }
    $this->_queryParams = $this->_arrayMerge( $this->_queryParams, $params );
  }

  public function setClientOptions( array $options ) {
    $this->_clientOptions = $options;
  }

  public function getClientOptions( $option = null ) {
    return ( $option ) ? $this->_clientOptions[ $option ] : $this->_clientOptions;
  }

  public function __call( $endpoint, $b ) {
    $this->_endpoint = $this->_endpoint . '/' . $endpoint;
    if ( $b ) {
      foreach ( $b as $i => $a ) {
        if ( is_array( $a ) ) {
          $b = $this->_arrayMerge( $b[ $i ], $a );
        } else {
          $this->_endpoint = $this->_endpoint . '/' . $a;
        }
      }
    }

    return new UnibetAPI( $this->_arrayMerge( $this->_queryParams, $b ), $this->_endpoint );
  }

  /**
   * Get JSON response
   *
   * @return null|\Psr\Http\Message\ResponseInterface
   * @throws \Sharapov\UnibetPHP\UnibetAPIException
   */
  public function json() {
    return $this->_request();
  }

  /**
   * Get XML response
   *
   * @return null|\Psr\Http\Message\ResponseInterface
   * @throws \Sharapov\UnibetPHP\UnibetAPIException
   */
  public function xml() {
    return $this->_request('xml');
  }

  /**
   * Send request
   *
   * @param string $responseFormat
   *
   * @return null|\Psr\Http\Message\ResponseInterface
   * @throws \Sharapov\UnibetPHP\UnibetAPIException
   */
  private function _request($responseFormat = 'json') {
    if ( ! isset( $this->_queryParams['app_id'] ) or ! isset( $this->_queryParams['app_key'] ) ) {
      throw new UnibetAPIException( "AppId/AppKey are missing from the params array" );
    }

    try {
      $client = new Client( $this->_clientOptions );

      $response = $client->get( $this->getClientOptions( 'base_uri' ) . trim( $this->_endpoint, '/' ) . '.'.ltrim($responseFormat, '.'), [ 'query' => $this->_queryParams ] );

      if ( $response->getStatusCode() == 200 ) {
        return $response;
      }

      return null;

    } catch ( \Exception $e ) {
      throw new UnibetAPIException( $e->getMessage() );
    }
  }

  /**
   * Merge two arrays - but if one is blank or not an array, return the other.
   *
   * @param $a      array First array, into which the second array will be merged
   * @param $b      array Second array, with the data to be merged
   * @param $unique boolean If true, remove duplicate values before returning
   *
   * @return array
   */
  private function _arrayMerge( &$a, $b, $unique = false ) {
    if ( empty( $b ) ) {
      return $a;  // No changes to be made to $a
    }
    if ( empty( $a ) ) {
      $a = $b;

      return $a;
    }
    $a = array_merge( $a, $b );
    if ( $unique ) {
      $a = array_unique( $a );
    }

    return $a;
  }
}