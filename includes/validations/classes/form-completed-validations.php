<?php
namespace Esmowin_EFA\Validations;

use Esmowin_EFA\Plugin;
use Esmowin_EFA\Core\Form_Messages;
use Esmowin_EFA\Core\SingletonBase;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Form completed validations class
 *
 * The class that manages the plugin form completed validations, that is,
 * after all its fields have been validated.
 */
final class Form_Completed_Validations extends SingletonBase
{
  /**
   * The form completed validations singleton instance
   */
  protected static ?Form_Completed_Validations $instance = null;

  /**
   * Initialize Singleton
   *
   * Initializes the form completed validations functionality.
   */
  protected function init_singleton(): void
  {
    add_action('elementor_pro/forms/validation', [$this, 'form_completed_validation'], 10, 2);
  }

  /**
   * Validate form completed
   *
   * Validates the form completed, that is, after all its fields have been validated.
   */
  public function form_completed_validation($record, $ajax_handler): void
  {
    $form_settings = Plugin::get_form_settings($ajax_handler);
    $form_fields = $form_settings['form_fields'] ?? null;

    if (empty($form_fields)) {
      // The form is invalid
      return;
    }

    $field_type = 'tel';
    $tel_fields = $this->get_fields_by_type($field_type, form_fields: $form_fields);

    if (!empty($tel_fields)) {
      $this->form_completed_validation_tel_fields($tel_fields, $ajax_handler);
    }
  }

  private function get_fields_by_type($field_type, $form_fields): array
  {
    return array_filter($form_fields, fn($form_field): bool => $form_field['field_type'] === $field_type);
  }

  private function form_completed_validation_tel_fields($tel_fields, $ajax_handler): void
  {
    foreach ($tel_fields as $tel_field) {
      if (empty($tel_field['custom_id'])) {
        // This tel field is invalid
        return;
      }

      $tel_field_id = $tel_field['custom_id'];

      if (empty($ajax_handler->errors[$tel_field_id])) {
        // There is no error for this tel field
        return;
      }

      $tel_field_error = $ajax_handler->errors[$tel_field_id];

      if (strpos($tel_field_error, 'field accepts only numbers and phone characters') !== false) {
        $form_message = Form_Messages::get_form_message(
          Form_Messages::TEL_FIELD_INVALID_CHARACTERS,
          $ajax_handler
        );

        $ajax_handler->add_error($tel_field_id, $form_message);
        return;
      }
    }
  }
}
