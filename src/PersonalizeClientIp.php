<?php

namespace Drupal\personalization_algo;

use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use GeoIp2\Database\Reader;

/**
 * Class PersonalizeClientIp.
 *
 * @package Drupal\personalization_algo
 */
class PersonalizeClientIp {

  /**
   * The stream wrapper manager.
   *
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
   */
  protected $streamWrapperManager;

  /**
   * The request.
   *
   * @var \Symfony\Component\HttpFoundation\$requestStack
   */
  protected $requestStack;

  /**
   * Constructor.
   */
  public function __construct(StreamWrapperManagerInterface $stream_wrapper_manager, RequestStack $requestStack) {
    $this->streamWrapperManager = $stream_wrapper_manager;
    $this->requestStack = $requestStack;
  }

  public function getClientData() {
    $request = $this->requestStack->getCurrentRequest();
    $client_data = [];
    $uri = 'public://';
    $wrapper = $this->streamWrapperManager->getViaUri($uri);
    $geoip_file = $wrapper->realPath() . '/GeoLite2-City.mmdb';

    if (!file_exists($geoip_file))
      return [];

    $reader = new Reader($geoip_file);
    $client_ip = $request->getClientIp();

    // Remove it. 
    //JP : 221.111.201.173
    // US: 172.217.26.174
    //IN: 182.71.64.138
    $client_ip = '221.111.201.173';

    if (!$this->validateIP($client_ip))
      return [];

    $record = $reader->city($client_ip);

    $client_data = [
      'client_ip' => $client_ip,
      'country_name' => $record->country->name,
      'iso_code' => $record->country->isoCode,
      'city_name' => $record->city->name,
      'postal_code' => $record->postal->code,
      'latitude' => $record->location->latitude,
      'longitude' => $record->location->longitude,
    ];

    return $client_data;
  }

  /**
   * Ensures an ip address is both a valid IP and does not fall within
   * a private network range.
   */
  private function validateIP($ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
      return false;
    }
    return true;
  }

}
