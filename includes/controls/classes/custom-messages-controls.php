<?php
namespace Esmowin_EFA\Controls;

use Elementor\Controls_Manager;
use Esmowin_EFA\Core\Form_Messages;
use Esmowin_EFA\Core\SingletonBase;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Custom messages controls class
 *
 * The class that manages the plugin custom messages controls.
 */
final class Custom_Messages_Controls extends SingletonBase
{
  /**
   * The custom messages controls singleton instance
   */
  protected static ?Custom_Messages_Controls $instance = null;

  /**
   * Initialize Singleton
   *
   * Initializes the custom messages controls functionality.
   */
  protected function init_singleton(): void
  {
    add_action('elementor/element/form/section_form_options/after_section_end', [$this, 'inject_controls']);
  }

  /**
   * Inject controls
   *
   * Injects custom messages controls after "form options" section
   */
  public function inject_controls($form): void
  {
    $default_messages = Form_Messages::get_default_messages();

    $form->start_controls_section(
      'section_esmowin_efa',
      [
        'label' => esc_html__('Esmowin EFA', 'esmowin-efa'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    $form->add_control(
      'custom_messages_heading',
      [
        'label' => esc_html__('Custom messages', 'esmowin-efa'),
        'type' => Controls_Manager::HEADING
      ]
    );

    $form->add_control(
      'active_custom_messages_alert',
      [
        'type' => Controls_Manager::ALERT,
        'alert_type' => 'warning',
        'content' => esc_html__('Active', 'esmowin-efa') . ' <b>' . esc_html__('Additional Options > Custom messages', 'esmowin-efa') . '</b> ' . esc_html__('setting to edit custom messages', 'esmowin-efa'),
        'condition' => [
          'custom_messages' => '',
        ],
      ]
    );

    $form->add_control(
      'email_field_invalid_characters_message',
      [
        'label' => esc_html__('(Email) Invalid characters', 'esmowin-efa'),
        'type' => Controls_Manager::TEXT,
        'default' => $default_messages[Form_Messages::EMAIL_FIELD_INVALID_CHARACTERS],
        'placeholder' => $default_messages[Form_Messages::EMAIL_FIELD_INVALID_CHARACTERS],
        'label_block' => true,
        'condition' => [
          'custom_messages!' => '',
          'form_validation' => 'custom',
        ],
        'render_type' => 'none',
        'dynamic' => [
          'active' => true,
        ],
      ]
    );

    $form->add_control(
      'email_field_invalid_format_message',
      [
        'label' => esc_html__('(Email) Invalid format', 'esmowin-efa'),
        'type' => Controls_Manager::TEXT,
        'default' => $default_messages[Form_Messages::EMAIL_FIELD_INVALID_FORMAT],
        'placeholder' => $default_messages[Form_Messages::EMAIL_FIELD_INVALID_FORMAT],
        'label_block' => true,
        'condition' => [
          'custom_messages!' => '',
          'form_validation' => 'custom',
        ],
        'render_type' => 'none',
        'dynamic' => [
          'active' => true,
        ],
      ]
    );

    $form->add_control(
      'tel_field_invalid_characters_message',
      [
        'label' => esc_html__('(Tel) Invalid characters', 'esmowin-efa'),
        'type' => Controls_Manager::TEXT,
        'default' => $default_messages[Form_Messages::TEL_FIELD_INVALID_CHARACTERS],
        'placeholder' => $default_messages[Form_Messages::TEL_FIELD_INVALID_CHARACTERS],
        'label_block' => true,
        'condition' => [
          'custom_messages!' => '',
          'form_validation' => 'custom',
        ],
        'render_type' => 'none',
        'dynamic' => [
          'active' => true,
        ],
      ]
    );

    $form->end_controls_section();
  }
}
