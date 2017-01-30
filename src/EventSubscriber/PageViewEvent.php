<?php

namespace Drupal\personalization_algo\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Class PageViewEvent.
 *
 * @package Drupal\personalization_algo
 */
class PageViewEvent implements EventSubscriberInterface {

  protected $personalize_var;

  /**
   * Constructor.
   */
  public function __construct() {
    
  }

  /**
   * Getter for the config object.
   *
   * @return Config
   */
  public function getPersonalizeVar() {
    return $this->personalize_var;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST] = ['personalizePageView'];

    return $events;
  }

  /**
   * This method is called whenever the kernel.request event is dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function personalizePageView(GetResponseEvent $event) {
    \Drupal::service('page_cache_kill_switch')->trigger();
  }

}
