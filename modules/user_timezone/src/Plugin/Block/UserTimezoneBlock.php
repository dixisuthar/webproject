<?php

namespace Drupal\user_timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Newsletter subscription block.
 *
 * @Block(
 *   id= "user_timezone_block",
 *   admin_label= @Translation("User Timezone Block")
 * )
 */
class UserTimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * ExchangeRatesBlock constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $cache_backend
   *   The cacheBackendInterface.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $cache_backend) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->cacheBackend = $cache_backend;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * Place config form in block.
   */
  public function build() {
    $build = [];
    $build['#cache']['contexts'] = ['timezone'];
    $config = $this->cacheBackend->getEditable('user_timezone.settings');
    $country = $config->get('country');
    $city = $config->get('city');
    $date = $config->get('date');
    $time = explode('-', $date ?? '');

    return [
      '#theme' => 'block--user-timezone-block',
      '#time' => $time[1] ?? '',
      '#day' => date('l, d F Y', strtotime($time[0])),
      '#city' => $city,
      '#country' => $country,
    ];
  }

}
