<?php

namespace Drupal\weather\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "weather_form_block",
 *   admin_label = @Translation("Weather Form block"),
 *   category = @Translation("Weather Form"),
 * )
 */
class WeatherFormBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */

  public function build() {
    $config = $this->getConfiguration();
    $city = $config['city'];
    print_r($city);

    $image = $config['image'];
    $file = File::load($image[0]);
    $imageurl = $file->getFileUri();
    print_r($imageurl);

    $app = \Drupal::config('weather.settings');
    $app = $app->get('app');
    $service = \Drupal::service('weather.test_service');
    $ress = $service->WeatherMethod($config['city']);
    $result = Json::decode($ress);
    print_r($result);

    $description = $config['description']['value'];
    // $description = $desc['value'];
    print_r($description);

    
    $mintemp = $this->t((string) $result['main']['temp_min']);
    $maxtemp = $this->t((string) $result['main']['temp_max']);
    $pressure = $this->t((string) $result['main']['pressure']);
    $humid = $this->t((string) $result['main']['humidity']);
    $speed = $this->t((string) $result['wind']['speed']); 
    return [
      '#theme' => 'weather',
      '#image'  => $imageurl,
      '#city' => $city,
      '#description' => $description,
      '#mintemp' =>  $mintemp,
      '#maxtemp'  =>  $maxtemp,
      '#pressure' => $pressure,
      '#humid'   =>  $humid,
      '#speed'   =>  $speed,

      // '#type' => 'markup',
      // '#markup' => $markup,
    ];    
  }

  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => t('City'),
      '#description' => t('Enter the city'),
      '#default_value' => 'City'
    ];

    $form['description'] = [
        '#type' => 'text_format',
        '#title' => t('Description'),
        '#description' => t('This is the description'),
        '#format' => 'full_html',
        '#rows' => 50,
        '#default_value' => ''
    ];

    $form['image'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://upload/hello',
      '#title' => t('Image'),
      '#upload_validators' => [
          'file_validate_extensions' => ['jpg', 'jpeg', 'png', 'gif']
      ],
      '#default_value' => isset($this->configuration['image']) ? $this->configuration['image'] : '',
      '#description' => t('The image to display'),
      '#required' => true
    ];

    return $form;
    
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // $abs = $form_state->getValue('description');
    // kint($abs['value']);
    // exit();
    $this->configuration['weather'] = $form_state->getValue('weather');
    
    $image = $form_state->getValue('image');
    $file = File::load( $image[0] );
    $file->setPermanent();
    $file->save();
    
    // Save configurations.
    $this->setConfigurationValue('city' , $form_state->getValue('city'));
    $this->setConfigurationValue('description' , $form_state->getValue('description'));
    $this->setConfigurationValue('image' , $form_state->getValue('image'));
  }
}