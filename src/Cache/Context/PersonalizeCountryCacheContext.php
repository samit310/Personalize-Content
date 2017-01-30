<?php

namespace Drupal\personalization_algo\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\personalization_algo\PersonalizeClientIp;

/**
 * Defines the PersonalizeCountryCacheContext service, for "Geoip Country code" caching.
 * Cache context ID: 'personalizecountry'.
 */
class PersonalizeCountryCacheContext implements CacheContextInterface {

  /**
   * The PersonalizeClientIp object.
   *
   * @var \Drupal\personalization_algo
   */
  protected $personalize_client_ip;

  /**
   * Constructs a new PersonalizeClientIp class.
   *
   * @param \Drupal\personalization_algo\PersonalizeClientIp $personalize_client_ip
   */
  public function __construct(PersonalizeClientIp $personalize_client_ip) {
    $this->personalize_client_ip = $personalize_client_ip;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Personalize Country Cache Context');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    $client_ip_data = &drupal_static('personalization_algo.client_ip');
    if (empty($client_ip_data)) {
      $client_ip_data = $this->personalize_client_ip->getClientData();
    }
    return empty($client_ip_data['iso_code']) ? 'US' : $client_ip_data['iso_code'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }

}
