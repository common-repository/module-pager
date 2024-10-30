<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/yama-dev
 * @since      1.0.0
 *
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/admin
 * @author     yama-dev <tatsuya.yamamoto69@gmail.com>
 */
class Wp_Module_Pager_Admin {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param    string    $plugin_name       The name of this plugin.
   * @param    string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    wp_enqueue_style( $this->plugin_name.'-font', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900', array(), $this->version, 'all' );
    wp_enqueue_style( $this->plugin_name.'-material', 'https://fonts.googleapis.com/css?family=Material+Icons', array(), $this->version, 'all' );
    wp_enqueue_style( $this->plugin_name.'-vuetify', plugin_dir_url( __FILE__ ) . 'css/vuetify.min.css', array(), $this->version, 'all' );
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-module-pager-admin.css', array(), $this->version, 'all' );

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    wp_enqueue_script( $this->plugin_name.'-vue', plugin_dir_url( __FILE__ ) . 'js/vue.js', array(), $this->version, true );
    wp_enqueue_script( $this->plugin_name.'-vuetify', plugin_dir_url( __FILE__ ) . 'js/vuetify.js', array(), $this->version, true );
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-module-pager-admin.js', array(), $this->version, true );

  }

  /**
   * Register admin input area.
   *
   * @since    1.1.0
   */
  public function wmp_admin_menu() {
    $page_title = __('ページネーション設定', 'wp-module-pager' );
    $menu_title = __('ページネーション設定', 'wp-module-pager' );
    $capability = 'manage_options';
    $menu_slug  = $this->plugin_name.'-menu-slug';

    add_options_page( $page_title, $menu_title, $capability, $menu_slug, array($this, 'display_plugin_admin_page'));
  }

  public function display_plugin_admin_page(){
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wp-module-pager-admin-display.php';
  }

}
