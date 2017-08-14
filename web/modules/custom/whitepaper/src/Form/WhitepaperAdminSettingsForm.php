<?php

namespace Drupal\whitepaper\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class WhitepaperAdminSettingsForm extends ConfigFormBase{

  public function getFormId() {
    return 'whitepaper_admin_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('whitepaper.settings');

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Set email address to send whitepaper email submissions to'),
      '#default_value' => $config->get('whitepaper.email'),
      '#required' => true,
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('whitepaper.settings');
    $config->set('whitepaper.email', $form_state->getValue('email'));
    $config->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['whitepaper.settings'];
  }
}