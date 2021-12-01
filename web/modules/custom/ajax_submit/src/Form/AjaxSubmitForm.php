<?php

/**
 * @file
 * Contains \Drupal\ajax_submit\Form\AjaxSubmitForm
 */

namespace Drupal\ajax_submit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class AjaxSubmitForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_submit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="text-message"></div>'
    ];

    $form['field_name'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
    ];

    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::showText',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function showText(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $name = $form_state->getValue('field_name');
    $html_command = new HtmlCommand('.text-message', t("Hello @name", ['@name' => $name]));

    if (trim($name) == '') {
      $html_command = new HtmlCommand('.text-message', t("Input valid name"));
    }

    $response->addCommand($html_command);

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) { }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) { }
}
