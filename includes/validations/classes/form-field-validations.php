<?php
namespace Esmowin_EFA\Validations;

use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use Esmowin_EFA\Core\Form_Messages;
use Esmowin_EFA\Core\SingletonBase;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Form field validations class
 *
 * The class that manages the plugin form field validations.
 */
final class Form_Field_Validations extends SingletonBase
{
  /**
   * The form field validations singleton instance
   */
  protected static ?Form_Field_Validations $instance = null;

  /**
   * Initialize Singleton
   *
   * Initializes the form field validations functionality.
   */
  protected function init_singleton(): void
  {
    add_action('elementor_pro/forms/validation/email', [$this, 'email_field_validation'], 10, 3);
  }

  /**
   * Validate email field
   *
   * Validates the form email field.
   */
  public function email_field_validation($field, $record, $ajax_handler): void
  {
    $email = $field['raw_value'];
    $field_id = $field['id'];

    // Test for an empty email
    if (empty($email)) {
      $this->add_field_error($field_id, Ajax_Handler::FIELD_REQUIRED, $ajax_handler);
      return;
    }

    /**
     * From now on, this validation is based on the Wordpress source function "is_email"
     * which can be found in wp-includes/formatting.php. The same logic is used, we're
     * just implementing the form custom messages.
     */

    // Test for the minimum length the email can be.
    if (strlen($email) < 6) {
      $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_FORMAT, $ajax_handler);
      return;
    }

    // Test for an @ character after the first position.
    if (strpos($email, '@', 1) === false) {
      $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_FORMAT, $ajax_handler);
      return;
    }

    // Split out the local and domain parts.
    [$local, $domain] = explode('@', $email, 2);

    /*
     * LOCAL PART
     * Test for invalid characters.
     */
    if (!preg_match('/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local)) {
      $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_CHARACTERS, $ajax_handler);
      return;
    }

    /*
     * DOMAIN PART
     * Test for sequences of periods.
     */
    if (preg_match('/\.{2,}/', $domain)) {
      $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_FORMAT, $ajax_handler);
      return;
    }

    // Test for leading and trailing periods and whitespace.
    if (trim($domain, " \t\n\r\0\x0B.") !== $domain) {
      $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_FORMAT, $ajax_handler);
      return;
    }

    // Split the domain into subs.
    $subs = explode('.', $domain);

    // Assume the domain will have at least two subs.
    if (2 > count($subs)) {
      $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_FORMAT, $ajax_handler);
      return;
    }

    // Loop through each sub.
    foreach ($subs as $sub) {
      // Test for leading and trailing hyphens and whitespace.
      if (trim($sub, " \t\n\r\0\x0B-") !== $sub) {
        $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_FORMAT, $ajax_handler);
        return;
      }

      // Test for invalid characters.
      if (!preg_match('/^[a-z0-9-]+$/i', $sub)) {
        $this->add_field_error($field_id, Form_Messages::EMAIL_FIELD_INVALID_CHARACTERS, $ajax_handler);
        return;
      }
    }
  }

  private function add_field_error($field_id, $error_key, $ajax_handler): void
  {
    $form_message = Form_Messages::get_form_message($error_key, $ajax_handler);
    $ajax_handler->add_error($field_id, $form_message);
  }
}
