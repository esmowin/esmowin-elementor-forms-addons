<?php
namespace Esmowin_EFA;

use Esmowin_EFA\Core\SingletonBase;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Plugin class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Plugin extends SingletonBase
{
  private const ESMOWIN_EFA_VERSION = '1.0.0';
  private const MINIMUM_ELEMENTOR_PRO_VERSION = '3.26.0';
  private const MINIMUM_PHP_VERSION = '7.4';

  /**
   * The plugin singleton instance
   */
  protected static ?Plugin $instance = null;

  /**
   * Initialize singleton
   *
   * Performs some compatibility checks to make sure basic requirements are meet.
   * If all compatibility checks pass, initializes the plugin functionality.
   */
  protected function init_singleton(): void
  {
    if ($this->is_compatible()) {
      add_action('elementor/init', [$this, 'init_plugin']);
    }
  }

  /**
   * Initialize plugin
   *
   * Loads the plugin functionality only after Elementor is initialized.
   * Fired by `elementor/init` action hook.
   */
  public function init_plugin(): void
  {
    require_once ESMOWIN_EFA_CORE_PATH . 'form-messages.php';
    require_once ESMOWIN_EFA_CONTROLS_PATH . 'controls-manager.php';
    require_once ESMOWIN_EFA_VALIDATIONS_PATH . 'validations-manager.php';

    \Esmowin_EFA\Controls\Controls_Manager::getInstance();
    \Esmowin_EFA\Validations\Validations_Manager::getInstance();
  }

  /**
   * Compatibility Checks
   *
   * Checks whether the site meets the plugin requirement.
   */
  private function is_compatible(): bool
  {
    // Check if Elementor is installed and activated
    if (!did_action('elementor/loaded') || !$this->is_elementor_pro_installed()) {
      add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
      return false;
    }

    // Check for required Elementor Pro version
    if (!version_compare(ELEMENTOR_PRO_VERSION, self::MINIMUM_ELEMENTOR_PRO_VERSION, '>=')) {
      add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_pro_version']);
      return false;
    }

    // Check for required PHP version
    if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
      add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
      return false;
    }

    return true;
  }

  /**
   * Dependency Check
   *
   * Checks if Elementor Pro is installed.
   */
  private function is_elementor_pro_installed(): bool
  {
    return defined('ELEMENTOR_PRO_VERSION');
  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have Elementor installed or activated.
   */
  public function admin_notice_missing_main_plugin(): void
  {
    if (isset($_GET['activate']))
      unset($_GET['activate']);

    $format = esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'esmowin-efa');
    $value1 = '<strong>' . esc_html__('Esmowin Elementor Forms Addons', 'esmowin-efa') . '</strong>';
    $value2 = '<strong>' . esc_html__('Elementor and Elementor Pro', 'esmowin-efa') . '</strong>';
    $message = sprintf($format, $value1, $value2);

    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have a minimum required Elementor version.
   */
  public function admin_notice_minimum_elementor_pro_version(): void
  {
    if (isset($_GET['activate']))
      unset($_GET['activate']);

    $format = esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'esmowin-efa');
    $value1 = '<strong>' . esc_html__('Esmowin Elementor Forms Addons', 'esmowin-efa') . '</strong>';
    $value2 = '<strong>' . esc_html__('Elementor Pro', 'esmowin-efa') . '</strong>';
    $value3 = self::MINIMUM_ELEMENTOR_PRO_VERSION;
    $message = sprintf($format, $value1, $value2, $value3);

    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have a minimum required PHP version.
   */
  public function admin_notice_minimum_php_version(): void
  {
    if (isset($_GET['activate']))
      unset($_GET['activate']);

    $format = esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'esmowin-efa');
    $value1 = '<strong>' . esc_html__('Esmowin Elementor Forms Addons', 'esmowin-efa') . '</strong>';
    $value2 = '<strong>' . esc_html__('PHP', 'esmowin-efa') . '</strong>';
    $value3 = self::MINIMUM_PHP_VERSION;
    $message = sprintf($format, $value1, $value2, $value3);

    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
  }

  public static function get_form_settings($ajax_handler): array
  {
    $current_form = $ajax_handler->get_current_form();
    return $current_form['settings'];
  }
}
