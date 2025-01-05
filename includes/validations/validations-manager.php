<?php
namespace Esmowin_EFA\Validations;

use Esmowin_EFA\Core\SingletonBase;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Validations manager class
 *
 * The class that manages the plugin validations.
 */
final class Validations_Manager extends SingletonBase
{
  /**
   * The controls manager singleton instance
   */
  protected static ?Validations_Manager $instance = null;

  /**
   * Initialize Singleton
   *
   * Initializes the validations manager functionality.
   */
  protected function init_singleton(): void
  {
    $this->add_form_field_validations();
    $this->add_form_completed_validations();
  }

  /**
   * Add form field validations
   *
   * Adds form field specific validations
   */
  private function add_form_field_validations(): void
  {
    require_once ESMOWIN_EFA_VALIDATIONS_PATH . 'classes/form-field-validations.php';

    \Esmowin_EFA\Validations\Form_Field_Validations::getInstance();
  }

  /**
   * Add form completed validations
   *
   * Adds form completed specific validations
   */
  private function add_form_completed_validations(): void
  {
    require_once ESMOWIN_EFA_VALIDATIONS_PATH . 'classes/form-completed-validations.php';

    \Esmowin_EFA\Validations\Form_Completed_Validations::getInstance();
  }
}
