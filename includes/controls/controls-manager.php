<?php
namespace Esmowin_EFA\Controls;

use Esmowin_EFA\Core\SingletonBase;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Controls manager class
 *
 * The class that manages the plugin controls.
 */
final class Controls_Manager extends SingletonBase
{
  /**
   * The controls manager singleton instance
   */
  protected static ?Controls_Manager $instance = null;

  /**
   * Initialize Singleton
   *
   * Initializes the controls manager functionality.
   */
  protected function init_singleton(): void
  {
    $this->inject_controls();
  }

  /**
   * Inject Controls
   *
   * Load controls injection files and inject them.
   */
  private function inject_controls(): void
  {
    require_once ESMOWIN_EFA_CONTROLS_PATH . 'classes/custom-messages-controls.php';

    \Esmowin_EFA\Controls\Custom_Messages_Controls::getInstance();
  }
}
