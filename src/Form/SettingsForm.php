<?php

namespace Drupal\cirqular_emails\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Configure Cirqular Emails settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cirqular_emails_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cirqular_emails.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cirqular_emails.settings');

    $form['#attached']['library'][] = 'cirqular_emails/admin_form_styles';

    // --- HEADING 1: GLOBAL SETTINGS ---
    $form['global_settings_heading'] = [
      '#markup' => '<h2>' . $this->t('Global Settings') . '</h2>',
    ];

    $form['global_tabs'] = [
      '#type' => 'vertical_tabs',
    ];

    // Global Tab 1: Basics
    $form['basics'] = ['#type' => 'details', '#title' => $this->t('Basics'), '#group' => 'global_tabs'];
    $form['basics']['logo'] = ['#type' => 'managed_file', '#title' => $this->t('Email Logo'), '#upload_location' => 'public://mails/logos', '#upload_validators' => ['FileExtension' => ['extensions' => 'svg png jpg jpeg']], '#default_value' => $config->get('logo')];
    $form['basics']['registered_name'] = ['#type' => 'textfield', '#title' => $this->t('Registered Company Name'), '#default_value' => $config->get('registered_name')];
    $form['basics']['footer_text'] = ['#type' => 'textarea', '#title' => $this->t('Footer Legal Text'), '#description' => $this->t('Enter the legal text to be displayed in the email footer. You can use tokens like [site:name] and [site:url].'), '#default_value' => $config->get('footer_text'), '#rows' => 4];

    $colors_config = $config->get('colors') ?? [];
    // Global Tab 2: Brand Colors
    $form['brand_colors'] = ['#type' => 'details', '#title' => $this->t('Brand Colors'), '#group' => 'global_tabs'];
    $form['brand_colors']['grid'] = ['#type' => 'container', '#attributes' => ['class' => ['cirqular-emails-color-grid']]];
    $form['brand_colors']['grid']['primary_light'] = ['#type' => 'color', '#title' => $this->t('Primary Light'), '#default_value' => $colors_config['primary_light'] ?? ''];
    $form['brand_colors']['grid']['primary_regular'] = ['#type' => 'color', '#title' => $this->t('Primary Regular'), '#default_value' => $colors_config['primary_regular'] ?? ''];
    $form['brand_colors']['grid']['primary_dark'] = ['#type' => 'color', '#title' => $this->t('Primary Dark'), '#default_value' => $colors_config['primary_dark'] ?? ''];
    $form['brand_colors']['grid']['alternate_light'] = ['#type' => 'color', '#title' => $this->t('Alternate Light'), '#default_value' => $colors_config['alternate_light'] ?? ''];
    $form['brand_colors']['grid']['alternate_regular'] = ['#type' => 'color', '#title' => $this->t('Alternate Regular'), '#default_value' => $colors_config['alternate_regular'] ?? ''];
    $form['brand_colors']['grid']['alternate_dark'] = ['#type' => 'color', '#title' => $this->t('Alternate Dark'), '#default_value' => $colors_config['alternate_dark'] ?? ''];
    $form['brand_colors']['grid']['text_light'] = ['#type' => 'color', '#title' => $this->t('Text Light'), '#default_value' => $colors_config['text_light'] ?? ''];
    $form['brand_colors']['grid']['text_regular'] = ['#type' => 'color', '#title' => $this->t('Text Regular'), '#default_value' => $colors_config['text_regular'] ?? ''];
    $form['brand_colors']['grid']['text_dark'] = ['#type' => 'color', '#title' => $this->t('Text Dark'), '#default_value' => $colors_config['text_dark'] ?? ''];

    // Global Tab 3: System Colors
    $form['system_colors'] = ['#type' => 'details', '#title' => $this->t('System Colors'), '#group' => 'global_tabs'];
    $form['system_colors']['grid'] = ['#type' => 'container', '#attributes' => ['class' => ['cirqular-emails-color-grid']]];
    $form['system_colors']['grid']['system_white'] = ['#type' => 'color', '#title' => $this->t('System White'), '#default_value' => $colors_config['system_white'] ?? ''];
    $form['system_colors']['grid']['system_blue'] = ['#type' => 'color', '#title' => $this->t('System Blue'), '#default_value' => $colors_config['system_blue'] ?? ''];
    $form['system_colors']['grid']['system_green'] = ['#type' => 'color', '#title' => $this->t('System Green'), '#default_value' => $colors_config['system_green'] ?? ''];
    $form['system_colors']['grid']['system_yellow'] = ['#type' => 'color', '#title' => $this->t('System Yellow'), '#default_value' => $colors_config['system_yellow'] ?? ''];
    $form['system_colors']['grid']['system_orange'] = ['#type' => 'color', '#title' => $this->t('System Orange'), '#default_value' => $colors_config['system_orange'] ?? ''];
    $form['system_colors']['grid']['system_red'] = ['#type' => 'color', '#title' => $this->t('System Red'), '#default_value' => $colors_config['system_red'] ?? ''];

    // --- SECTION 2: EMAIL SETTINGS ---
    $form['email_settings_heading'] = ['#markup' => '<h2>' . $this->t('Email Settings') . '</h2>', '#prefix' => '<br><br>'];
    $form['email_tabs'] = ['#type' => 'vertical_tabs'];
    $email_settings_config = $config->get('email_settings') ?? [];
    $token_tree = (\Drupal::moduleHandler()->moduleExists('token')) ? ['#theme' => 'token_tree_link', '#token_types' => ['site', 'user'], '#global_types' => TRUE, '#dialog' => TRUE] : [];

    $email_definitions = [
        'update_notification' => ['title' => 'System Updates', 'template' => 'cirqular-update-email.html.twig'],
        'tfa_enabled' => ['title' => 'TFA Enabled', 'template' => 'cirqular-tfa-enabled-email.html.twig'],
        'tfa_disabled' => ['title' => 'TFA Disabled', 'template' => 'cirqular-tfa-disabled-email.html.twig'],
        'password_recovery' => ['title' => 'Password Recovery', 'template' => 'cirqular-password-recovery.html.twig'],
        'welcome_admin_created' => ['title' => 'Welcome (Admin Created)', 'template' => 'cirqular-welcome-admin-created.html.twig'],
        'welcome_awaiting_approval' => ['title' => 'Welcome (Awaiting Approval)', 'template' => 'cirqular-welcome-awaiting-approval.html.twig'],
        'admin_user_awaiting_approval' => ['title' => 'Admin: User Awaiting Approval', 'template' => 'cirqular-admin-user-awaiting-approval.html.twig'],
        'welcome_no_approval' => ['title' => 'Welcome (No Approval)', 'template' => 'cirqular-welcome-no-approval.html.twig'],
        'account_activated' => ['title' => 'Account Activated', 'template' => 'cirqular-account-activated.html.twig'],
        'account_suspended' => ['title' => 'Account Suspended', 'template' => 'cirqular-account-suspended.html.twig'],
        'account_cancellation_confirm' => ['title' => 'Account Cancellation Confirmation', 'template' => 'cirqular-account-cancellation-confirm.html.twig'],
        'account_canceled' => ['title' => 'Account Canceled', 'template' => 'cirqular-account-canceled.html.twig'],
    ];
    
    foreach ($email_definitions as $id => $details) {
        $form[$id] = [
          '#type' => 'details',
          '#title' => $this->t($details['title']),
          '#group' => 'email_tabs',
        ];
        $form[$id]['subject'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Subject'),
          '#default_value' => $email_settings_config[$id]['subject'] ?? '',
        ];
        $form[$id]['preview_text'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Inbox Preview Text'),
          '#default_value' => $email_settings_config[$id]['preview_text'] ?? '',
        ];
        $form[$id]['template_info'] = ['#type' => 'item', '#markup' => $this->t('To override the HTML content, copy the file <strong>@template</strong> from the module\'s templates directory into your theme\'s own templates directory.', ['@template' => $details['template']])];
        $form[$id]['token_help'] = $token_tree;
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // --- Process File ---
    $logo_fid = $form_state->getValue('logo');
    if (!empty($logo_fid[0])) {
      $file = File::load($logo_fid[0]);
      if ($file) {
        $file->setPermanent();
        $file->save();
      }
    }

    // --- Process Colors ---
    $colors = [
        'primary_light' => $form_state->getValue('primary_light'),
        'primary_regular' => $form_state->getValue('primary_regular'),
        'primary_dark' => $form_state->getValue('primary_dark'),
        'alternate_light' => $form_state->getValue('alternate_light'),
        'alternate_regular' => $form_state->getValue('alternate_regular'),
        'alternate_dark' => $form_state->getValue('alternate_dark'),
        'text_light' => $form_state->getValue('text_light'),
        'text_regular' => $form_state->getValue('text_regular'),
        'text_dark' => $form_state->getValue('text_dark'),
        'system_white' => $form_state->getValue('system_white'),
        'system_blue' => $form_state->getValue('system_blue'),
        'system_green' => $form_state->getValue('system_green'),
        'system_yellow' => $form_state->getValue('system_yellow'),
        'system_orange' => $form_state->getValue('system_orange'),
        'system_red' => $form_state->getValue('system_red'),
    ];

    // --- Process Email Settings ---
    $email_keys = ['update_notification', 'tfa_enabled', 'tfa_disabled', 'password_recovery', 'welcome_admin_created', 'welcome_awaiting_approval', 'admin_user_awaiting_approval', 'welcome_no_approval', 'account_activated', 'account_suspended', 'account_cancellation_confirm', 'account_canceled'];
    $email_settings = [];
    foreach ($email_keys as $key) {
        // getValue returns an array like ['subject' => '...', 'preview_text' => '...']
        $email_settings[$key] = $form_state->getValue($key);
    }
    
    // --- Save all configuration ---
    $this->config('cirqular_emails.settings')
      ->set('logo', $form_state->getValue('logo'))
      ->set('registered_name', $form_state->getValue('registered_name'))
      ->set('footer_text', $form_state->getValue('footer_text'))
      ->set('colors', $colors)
      ->set('email_settings', $email_settings)
      ->save();

    parent::submitForm($form, $form_state);
  }

}