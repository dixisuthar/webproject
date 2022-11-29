<?php

namespace Drupal\changemakers_newsletter\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Newsletter subscription block.
 *
 * @Block(
 *   id= "changemakers_newsletter_block",
 *   admin_label= @Translation("Changemakers Newsletter Block")
 * )
 */
class ChangemakersNewsletterBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    $build['#cache']['max-age'] = 0;
    $config = $this->cacheBackend->getEditable('changemakers_newsletter.settings');
    $country = $config->get('Country');
    $city = $config->get('City');
    $date = $config->get('date');
    return [
      '#theme' => 'block--changemakers-newsletter-block',
      '#date' => $date,
      '#city' => $city,
      '#country' => $country,
    ];
  }

}
