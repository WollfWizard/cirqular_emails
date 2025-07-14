<?php

namespace Drupal\cirqular_emails\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase {

  public function getFormId() {
    return 'cirqular_emails_settings_form';
  }

  protected function getEditableConfigNames() {
    return ['cirqular_emails.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cirqular_emails.settings');

    // Attach our CSS library for the color picker grid.
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
    
    // --- ADD THIS NEW FIELD ---
    $form['basics']['footer_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Footer Legal Text'),
      '#description' => $this->t('Enter the legal text to be displayed in the email footer. You can use tokens like [site:name] and [site:url].'),
      '#default_value' => $config->get('footer_text'),
      '#rows' => 4,
    ];
    // --- END ADDITION ---

    $colors_config = $config->get('colors') ?? [];
    // Global Tab 2: Brand Colors
    $form['brand_colors'] = ['#type' => 'details', '#title' => $this->t('Brand Colors'), '#group' => 'global_tabs'];
    $form['brand_colors']['grid'] = ['#type' => 'container', '#attributes' => ['class' => ['cirqular-emails-color-grid']]];
    $form['brand_colors']['grid']['primary_light'] = ['#type' => 'color','#title' => $this->t('Primary Light'),'#title_display' => 'before','#default_value' => $colors_config['primary_light'] ?? ''];
    $form['brand_colors']['grid']['primary_regular'] = ['#type' => 'color','#title' => $this->t('Primary Regular'),'#title_display' => 'before','#default_value' => $colors_config['primary_regular'] ?? ''];
    $form['brand_colors']['grid']['primary_dark'] = ['#type' => 'color','#title' => $this->t('Primary Dark'),'#title_display' => 'before','#default_value' => $colors_config['primary_dark'] ?? ''];
    $form['brand_colors']['grid']['alternate_light'] = ['#type' => 'color','#title' => $this->t('Alternate Light'),'#title_display' => 'before','#default_value' => $colors_config['alternate_light'] ?? ''];
    $form['brand_colors']['grid']['alternate_regular'] = ['#type' => 'color','#title' => $this->t('Alternate Regular'),'#title_display' => 'before','#default_value' => $colors_config['alternate_regular'] ?? ''];
    $form['brand_colors']['grid']['alternate_dark'] = ['#type' => 'color','#title' => $this->t('Alternate Dark'),'#title_display' => 'before','#default_value' => $colors_config['alternate_dark'] ?? ''];
    $form['brand_colors']['grid']['text_light'] = ['#type' => 'color','#title' => $this->t('Text Light'),'#title_display' => 'before','#default_value' => $colors_config['text_light'] ?? ''];
    $form['brand_colors']['grid']['text_regular'] = ['#type' => 'color','#title' => $this->t('Text Regular'),'#title_display' => 'before','#default_value' => $colors_config['text_regular'] ?? ''];
    $form['brand_colors']['grid']['text_dark'] = ['#type' => 'color','#title' => $this->t('Text Dark'),'#title_display' => 'before','#default_value' => $colors_config['text_dark'] ?? ''];

    // Global Tab 3: System Colors
    $form['system_colors'] = ['#type' => 'details', '#title' => $this->t('System Colors'), '#group' => 'global_tabs'];
    $form['system_colors']['grid'] = ['#type' => 'container', '#attributes' => ['class' => ['cirqular-emails-color-grid']]];
    $form['system_colors']['grid']['system_white'] = ['#type' => 'color','#title' => $this->t('System White'),'#title_display' => 'before','#default_value' => $colors_config['system_white'] ?? ''];
    $form['system_colors']['grid']['system_blue'] = ['#type' => 'color','#title' => $this->t('System Blue'),'#title_display' => 'before','#default_value' => $colors_config['system_blue'] ?? ''];
    $form['system_colors']['grid']['system_green'] = ['#type' => 'color','#title' => $this->t('System Green'),'#title_display' => 'before','#default_value' => $colors_config['system_green'] ?? ''];
    $form['system_colors']['grid']['system_yellow'] = ['#type' => 'color','#title' => $this->t('System Yellow'),'#title_display' => 'before','#default_value' => $colors_config['system_yellow'] ?? ''];
    $form['system_colors']['grid']['system_orange'] = ['#type' => 'color','#title' => $this->t('System Orange'),'#title_display' => 'before','#default_value' => $colors_config['system_orange'] ?? ''];
    $form['system_colors']['grid']['system_red'] = ['#type' => 'color','#title' => $this->t('System Red'),'#title_display' => 'before','#default_value' => $colors_config['system_red'] ?? ''];


    // --- SECTION 2: EMAIL SETTINGS ---
    $form['email_settings_heading'] = ['#markup' => '<h2>' . $this->t('Email Settings') . '</h2>', '#prefix' => '<br><br>'];
    $form['email_tabs'] = ['#type' => 'vertical_tabs'];
    $email_settings_config = $config->get('email_settings') ?? [];
    $token_tree = (\Drupal::moduleHandler()->moduleExists('token')) ? ['#theme' => 'token_tree_link', '#token_types' => ['site', 'user'], '#global_types' => TRUE, '#dialog' => TRUE] : [];

    $build_email_tab = function($id, $title, $template_name) use ($email_settings_config, $token_tree) {
      $tab = ['#type' => 'details', '#title' => $title, '#group' => 'email_tabs'];
      $tab['subject'] = ['#type' => 'textfield', '#title' => $this->t('Subject'), '#default_value' => $email_settings_config[$id]['subject'] ?? ''];
      $tab['preview_text'] = ['#type' => 'textfield', '#title' => $this->t('Inbox Preview Text'), '#default_value' => $email_settings_config[$id]['preview_text'] ?? ''];
      $tab['template_info'] = ['#type' => 'item', '#markup' => $this->t('To override the HTML content, copy the file <strong>@template</strong> from the module\'s templates directory into your theme\'s own templates directory.', ['@template' => $template_name])];
      $tab['token_help'] = $token_tree;
      return $tab;
    };

    // Build all the email tabs
    $form['update_notification'] = $build_email_tab('update_notification', $this->t('System Updates'), 'cirqular-update-email.html.twig');
    $form['tfa_enabled'] = $build_email_tab('tfa_enabled', $this->t('TFA Enabled'), 'cirqular-tfa-enabled-email.html.twig');
    $form['tfa_disabled'] = $build_email_tab('tfa_disabled', $this->t('TFA Disabled'), 'cirqular-tfa-disabled-email.html.twig');
    $form['password_recovery'] = $build_email_tab('password_recovery', $this->t('Password Recovery'), 'cirqular-password-recovery.html.twig');
    $form['welcome_admin_created'] = $build_email_tab('welcome_admin_created', $this->t('Welcome (Admin Created)'), 'cirqular-welcome-admin-created.html.twig');
    $form['welcome_awaiting_approval'] = $build_email_tab('welcome_awaiting_approval', $this->t('Welcome (Awaiting Approval)'), 'cirqular-welcome-awaiting-approval.html.twig');
    $form['admin_user_awaiting_approval'] = $build_email_tab('admin_user_awaiting_approval', $this->t('Admin: User Awaiting Approval'), 'cirqular-admin-user-awaiting-approval.html.twig');
    $form['welcome_no_approval'] = $build_email_tab('welcome_no_approval', $this->t('Welcome (No Approval)'), 'cirqular-welcome-no-approval.html.twig');
    $form['account_activated'] = $build_email_tab('account_activated', $this->t('Account Activated'), 'cirqular-account-activated.html.twig');
    $form['account_suspended'] = $build_email_tab('account_suspended', $this->t('Account Suspended'), 'cirqular-account-suspended.html.twig');
    $form['account_cancellation_confirm'] = $build_email_tab('account_cancellation_confirm', $this->t('Account Cancellation Confirmation'), 'cirqular-account-cancellation-confirm.html.twig');
    $form['account_canceled'] = $build_email_tab('account_canceled', $this->t('Account Canceled'), 'cirqular-account-canceled.html.twig');

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $logo_fid = $form_state->getValue(['basics', 'logo', 0]);
    if ($logo_fid) {
      $file = \Drupal\file\Entity\File::load($logo_fid);
      if ($file) {
        $file->setPermanent();
        $file->save();
      }
    }

    $brand_colors = $form_state->getValue(['brand_colors', 'grid']) ?? [];
    $system_colors = $form_state->getValue(['system_colors', 'grid']) ?? [];
    $all_colors = array_merge($brand_colors, $system_colors);

    $email_settings = [
        'update_notification' => $form_state->getValue('update_notification'),
        'tfa_enabled' => $form_state->getValue('tfa_enabled'),
        'tfa_disabled' => $form_state->getValue('tfa_disabled'),
        'password_recovery' => $form_state->getValue('password_recovery'),
        'welcome_admin_created' => $form_state->getValue('welcome_admin_created'),
        'welcome_awaiting_approval' => $form_state->getValue('welcome_awaiting_approval'),
        'admin_user_awaiting_approval' => $form_state->getValue('admin_user_awaiting_approval'),
        'welcome_no_approval' => $form_state->getValue('welcome_no_approval'),
        'account_activated' => $form_state->getValue('account_activated'),
        'account_suspended' => $form_state->getValue('account_suspended'),
        'account_cancellation_confirm' => $form_state->getValue('account_cancellation_confirm'),
        'account_canceled' => $form_state->getValue('account_canceled'),
    ];

    $this->config('cirqular_emails.settings')
      ->set('logo', $form_state->getValue(['basics', 'logo']))
      ->set('registered_name', $form_state->getValue(['basics', 'registered_name']))
      // --- SAVE THE NEW FIELD ---
      ->set('footer_text', $form_state->getValue(['basics', 'footer_text']))
      ->set('colors', $all_colors)
      ->set('email_settings', $email_settings)
      ->save();

    parent::submitForm($form, $form_state);
  }
}