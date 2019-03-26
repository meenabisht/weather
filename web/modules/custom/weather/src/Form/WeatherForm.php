<?php

namespace Drupal\weather\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class WeatherForm extends ConfigFormBase {

  public function getFormId() {
    return 'weatherform';
  }

  protected function getEditableConfigNames() {
    return [
      'weather.settings'
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('weather.settings');

    $form['app'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App'),
      '#description' => $this->t('Please Enter your full name'),
      '#default_value' => $config->get('app'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('weather.settings')
      ->set('app', $form_state->getValue('app'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
?>