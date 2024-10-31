<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://servitor.io
 * @since      1.0.0
 *
 * @package    Servitor_Statistics
 * @subpackage Servitor_Statistics/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Servitor_Statistics
 * @subpackage Servitor_Statistics/admin
 * @author     Your Name <email@example.com>
 */
class Servitor_Statistics_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $Servitor_Statistics    The ID of this plugin.
	 */
	private $Servitor_Statistics;

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
	 * @param      string    $Servitor_Statistics       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Servitor_Statistics, $version ) {

		$this->Servitor_Statistics = $Servitor_Statistics;
		$this->version = $version;

		add_action('admin_menu', [$this, 'add_menu_items']);
		add_action('admin_init', [$this, 'register_option_groups']);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Servitor_Statistics_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Servitor_Statistics_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->Servitor_Statistics, plugin_dir_url( __FILE__ ) . 'css/servitor-statistics-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Servitor_Statistics_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Servitor_Statistics_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->Servitor_Statistics, plugin_dir_url( __FILE__ ) . 'js/servitor-statistics-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function add_menu_items() {
        add_menu_page('Servitor',
            'Servitor',
            'administrator',
            __FILE__,
            [$this, 'redirect_to_servitor'],
            "https://servitor.io/assets/front/images/Servitor-small-color.png");

        add_options_page(
            'Servitor',
            'Servitor',
            'administrator',
            __FILE__ . '_settings',
            [new Servitor_Statistics_Settings(), 'show_settings_page']
        );
    }

    public function redirect_to_servitor() {
        wp_enqueue_script( $this->Servitor_Statistics . '_redirect', plugin_dir_url( __FILE__ ) . 'js/servitor-statistics-redirect.js', array( 'jquery' ), $this->version, false );
    }

    public function register_option_groups() {
        // Register the settings group
        register_setting(
            Servitor_Statistics_Settings_Group,
            'servitor_user_key',
            [
                'type'          => 'string',
                'description'   => 'The user key of your account on Servitor.io',
                'show_in_rest'  => false,
            ]
        );

        register_setting(
            Servitor_Statistics_Settings_Group,
            'servitor_api_key',
            [
                'type'          => 'string',
                'description'   => 'The api key of a server on Servitor.io',
                'show_in_rest'  => false,
            ]
        );

        register_setting(
            Servitor_Statistics_Settings_Group,
            'servitor_monitor_id',
            [
                'type'          => 'string',
                'description'   => 'The monitor id of a monitor on Servitor.io',
                'show_in_rest'  => false,
            ]
        );
    }
}
