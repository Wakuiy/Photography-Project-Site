<?php

namespace Drupal\dui_view\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Unicode;
use Drupal\Component\Utility\Crypt;

/**
 * Configure dui_view settings for this site.
 */
class DuiViewSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dui_view_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dui_view.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dui_view.settings');

    $form['dui_view_site_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('The public site key.'),
      '#required' => TRUE,
      '#default_value' => $config->get('dui_view_site_key', ''),
    );

    $form['dui_view_site_key_priv'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('The private site key.'),
      '#required' => TRUE,
      '#default_value' => $config->get('dui_view_site_key_priv', ''),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Public & Private key validation.
    $publicKey = $form_state->getValue('dui_view_site_key');
    $privateKey = $form_state->getValue('dui_view_site_key_priv');

    // Character limit on public/private keys.
    $keyCharacterLimit = 25;

    if (Unicode::strlen($privateKey) <= $keyCharacterLimit) {
      $form_state->setErrorByName('dui_view_site_key', $this->t('The private key needs to contain at least @keyLimit characters', array('@keyLimit' => $keyCharacterLimit)));
    }

    if (Unicode::strlen($publicKey) <= $keyCharacterLimit) {
      $form_state->setErrorByName('dui_view_site_key_priv', $this->t('The public key needs to contain at least @keyLimit characters', array('@keyLimit' => $keyCharacterLimit)));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    \Drupal::configFactory()->getEditable('dui_view.settings')
      // Set the submitted configuration setting.
      ->set('dui_view_site_key', $form_state->getValue('dui_view_site_key'))
      ->set('dui_view_site_key_priv', $form_state->getValue('dui_view_site_key_priv'))
      ->save();

    parent::submitForm($form, $form_state);
  }


}
