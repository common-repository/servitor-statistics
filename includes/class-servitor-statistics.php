<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://servitor.io
 * @since      1.0.0
 *
 * @package    Servitor_Statistics
 * @subpackage Servitor_Statistics/includes
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
 * @package    Servitor_Statistics
 * @subpackage Servitor_Statistics/includes
 * @author     Your Name <email@example.com>
 */
class Servitor_Statistics {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Servitor_Statistics_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $Servitor_Statistics    The string used to uniquely identify this plugin.
	 */
	protected $Servitor_Statistics;

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
		if ( defined( 'Servitor_Statistics_VERSION' ) ) {
			$this->version = Servitor_Statistics_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->Servitor_Statistics = 'servitor-statistics';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

		add_filter('cron_schedules', [$this, 'add_cron_interval']);
		add_action(Servitor_Statistics_Cronjob, [new StatisticsCronjob(), 'run_statistics_cronjob']);

		$this->register_cronjob();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Servitor_Statistics_Loader. Orchestrates the hooks of the plugin.
	 * - Servitor_Statistics_i18n. Defines internationalization functionality.
	 * - Servitor_Statistics_Admin. Defines all hooks for the admin area.
	 * - Servitor_Statistics_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
        /**
         * Always load  the Composer autoloader first.
         */
//        require __DIR__ . '/../vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-servitor-statistics-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-servitor-statistics-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-servitor-statistics-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-servitor-statistics-settings.php';

        /**
         * Extra classes
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/statistics-collector.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/statistics-cronjob.php';

//        dd((new StatisticsCronjob)->run_statistics_cronjob());

		$this->loader = new Servitor_Statistics_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Servitor_Statistics_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Servitor_Statistics_i18n();

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

		$plugin_admin = new Servitor_Statistics_Admin( $this->get_Servitor_Statistics(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

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
	public function get_Servitor_Statistics() {
		return $this->Servitor_Statistics;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Servitor_Statistics_Loader    Orchestrates the hooks of the plugin.
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

    /**
     * add_cron_interval function.
     *
     * @param $schedules
     *
     * @return array
     */
    function add_cron_interval( $schedules ) {
        $schedules['five_minutes'] = array(
            'interval' => 5 * 60,
            'display'  => esc_html__( 'Every Five Minutes' ),
        );

        return $schedules;
    }

    private function register_cronjob() {

        if ( ! wp_next_scheduled( Servitor_Statistics_Cronjob ) ) {
            wp_schedule_event( time(), 'five_minutes', Servitor_Statistics_Cronjob );
        }
    }

}
