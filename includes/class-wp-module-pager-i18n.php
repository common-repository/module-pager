<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/yama-dev
 * @since      1.0.0
 *
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/includes
 * @author     yama-dev <tatsuya.yamamoto69@gmail.com>
 */
class Wp_Module_Pager_i18n {


  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    load_plugin_textdomain(
      'wp-module-pager',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );

  }

}
