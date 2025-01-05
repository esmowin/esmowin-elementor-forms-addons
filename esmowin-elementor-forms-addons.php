<?php
/**
 * Plugin Name: Esmowin Elementor Forms Addons
 * Description: Esmowin EFA - Addons for Elementor Forms
 * Version:     1.0.0
 * Author:      Esmowin
 * Author URI:  https://esmowin.com/
 * Text Domain: esmowin-efa
 * Requires Plugins: elementor-pro
 *
 * Elementor Pro tested up to: 3.26.2
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

define('ESMOWIN_EFA_PATH', plugin_dir_path(__FILE__));
define('ESMOWIN_EFA_INCLUDES_PATH', ESMOWIN_EFA_PATH . 'includes/');
define('ESMOWIN_EFA_CORE_PATH', ESMOWIN_EFA_INCLUDES_PATH . 'core/');
define('ESMOWIN_EFA_CONTROLS_PATH', ESMOWIN_EFA_INCLUDES_PATH . 'controls/');
define('ESMOWIN_EFA_VALIDATIONS_PATH', ESMOWIN_EFA_INCLUDES_PATH . 'validations/');

function esmowin_efa(): void
{
  require_once ESMOWIN_EFA_CORE_PATH . 'singleton-base.php';
  require_once ESMOWIN_EFA_INCLUDES_PATH . 'plugin.php';

  \Esmowin_EFA\Plugin::getInstance();
}

add_action('plugins_loaded', 'esmowin_efa');