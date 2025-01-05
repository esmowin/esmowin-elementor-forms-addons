<?php
namespace Esmowin_EFA\Core;

use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use Esmowin_EFA\Plugin;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

final class Form_Messages
{
  public const EMAIL_FIELD_INVALID_CHARACTERS = 'email_field_invalid_characters';
  public const EMAIL_FIELD_INVALID_FORMAT = 'email_field_invalid_format';
  public const TEL_FIELD_INVALID_CHARACTERS = 'tel_field_invalid_characters';

  public static function get_default_messages(): array
  {
    $elementor_default_messages = Ajax_Handler::get_default_messages();

    $esmowin_efa_default_messages = [
      self::EMAIL_FIELD_INVALID_CHARACTERS => esc_html__('The email has invalid characters', 'esmowin-efa'),
      self::EMAIL_FIELD_INVALID_FORMAT => esc_html__('The email format is invalid', 'esmowin-efa'),
      self::TEL_FIELD_INVALID_CHARACTERS => esc_html__('The field accepts only numbers and phone characters (#, -, *, etc).', 'esmowin-efa'),
    ];

    return array_merge($elementor_default_messages, $esmowin_efa_default_messages);
  }

  public static function get_form_message($key, $ajax_handler): string
  {
    $form_settings = Plugin::get_form_settings($ajax_handler);

    if (!empty($form_settings['custom_messages'])) {
      $settings_key = "{$key}_message";

      if (isset($form_settings[$settings_key])) {
        return $form_settings[$settings_key];
      }
    }

    $default_messages = self::get_default_messages();

    return $default_messages[$key] ?? esc_html__('Unknown error while processing this field', 'esmowin-efa');
  }
}