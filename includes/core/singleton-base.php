<?php
namespace Esmowin_EFA\Core;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Singleton Pattern base class
 *
 * The abstract base class from which Singleton Pattern classes should inherit.
 */
abstract class SingletonBase
{
  /**
   * Initialize singleton
   *
   * Initializes the singleton functionality.
   */
  abstract protected function init_singleton(): void;

  /**
   * Get singleton instance
   *
   * Checks if the child singleton has a $instance property defined. Then,
   * ensures only one instance of the class is loaded or can be loaded.
   */
  public static function getInstance(): SingletonBase
  {
    if (!property_exists(static::class, 'instance')) {
      throw new \LogicException(static::class . ' must have a $instance property defined');
    }

    if (is_null(static::$instance)) {
      static::$instance = new static();
    }

    return static::$instance;
  }

  /**
   * Constructor
   *
   * Initializes the functionality calling the singleton "init_singleton" method.
   * It's a final private method, which prevents child classes from overriding it.
   */
  final private function __construct()
  {
    $this->init_singleton();
  }
}
