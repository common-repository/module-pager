<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/yama-dev
 * @since      1.0.0
 *
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/includes
 * @author     yama-dev <tatsuya.yamamoto69@gmail.com>
 */
class Wp_Module_Pager {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Wp_Module_Pager_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {
    if ( defined( 'WP_MODULE_PAGER_VERSION' ) ) {
      $this->version = WP_MODULE_PAGER_VERSION;
    } else {
      $this->version = '1.0.0';
    }
    $this->plugin_name = 'wp-module-pager';

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();

  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Wp_Module_Pager_Loader. Orchestrates the hooks of the plugin.
   * - Wp_Module_Pager_i18n. Defines internationalization functionality.
   * - Wp_Module_Pager_Admin. Defines all hooks for the admin area.
   * - Wp_Module_Pager_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-module-pager-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-module-pager-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-module-pager-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-module-pager-public.php';

  /**
   * Generates pagination HTML
   *
   * @since    1.1.0
   */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-module-pager-front.php';

    $this->loader = new Wp_Module_Pager_Loader();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Wp_Module_Pager_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {

    $plugin_i18n = new Wp_Module_Pager_i18n();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {

    $plugin_admin = new Wp_Module_Pager_Admin( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'admin_menu', $plugin_admin, 'wmp_admin_menu' );

    if( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'wp-module-pager-menu-slug' ) {
      $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
      $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }

  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {

    $plugin_public = new Wp_Module_Pager_Public( $this->get_plugin_name(), $this->get_version() );

    // $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    // $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

    add_shortcode('wp-module-pager', array($this, 'renderShortCode'));

  }

  public function renderShortCode($atts) {
    $query = null;
    $post_type = isset($atts['post_type']) ? $atts['post_type'] : '';
    $base_path = isset($atts['base_path']) ? $atts['base_path'] : '';
    $this->render($query, $post_type, $base_path);
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Wp_Module_Pager_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

  public function render($query = null, $postType = '', $basePath = '') {
    $this->front = new Wp_Module_Pager_Front($query, $postType, $basePath, 'wp-module-pager');
  }

  static function renderStatic($query = null, $postType = '', $basePath = '') {
    new Wp_Module_Pager_Front($query, $postType, $basePath, 'wp-module-pager');
  }

}
