<?php

namespace Drupal\changemakers_newsletter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Datetime\TimeInterface;

/**
 * Newsletter config form.
 */
class ChangemakersNewsletterConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'changemakers_newsletter.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'changemakers_newsletter_config_form';
  }

  /**
   * The datetime.time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $timeService;

  /**
   * {@inheritdoc}
   */
  public function __construct(TimeInterface $time_service) {
    $this->timeService = $time_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('changemakers_newsletter.settings');
    $form['Country'] = [
      '#type' => 'textfield',
      '#title' => 'Country',
      '#default_value' => $config->get('country'),
      '#attributes' => [
        'placeholder' => $this->t('India'),
      ],
      '#required' => TRUE,
    ];

    $form['City'] = [
      '#type' => 'textfield',
      '#title' => 'City',
      '#attributes' => [
        'placeholder' => $this->t('Surat'),
      ],
      '#required' => TRUE,
    ];

    $form['Timezone'] = [
      '#type' => 'select',
      '#title' => 'Timezone',
      '#options' => [
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New_York'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'),
        'Europe/London' => $this->t('Europe/London'),
      ],
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    date_default_timezone_set($form_state->getValue('Timezone'));
    $request_time = $this->timeService->getCurrentTime();
    $date_output = date('d M Y - H:i A', $request_time);
    $this->config('changemakers_newsletter.settings')
      ->set('Country', $form_state->getValue('Country'))
      ->set('City', $form_state->getValue('City'))
      ->set('date', $date_output)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
