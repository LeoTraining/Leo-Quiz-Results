<?php

// include(plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Numbers/Words.php');

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/gr33k01
 * @since      1.0.0
 *
 * @package    Leo_Quiz_Results
 * @subpackage Leo_Quiz_Results/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Leo_Quiz_Results
 * @subpackage Leo_Quiz_Results/admin
 * @author     Nate Hobi <nate.hobi@gmail.com>
 */
class Leo_Quiz_Results_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/leo-quiz-results-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/leo-quiz-results-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Displays the admin page
	 *
	 * @since    1.0.0
	 */
	public function display_admin_page() {
		add_menu_page(
			'Quiz Results',
			'Quiz Results',
			'read',
			'leo_quiz_results_admin',
			array( $this, 'show_page' ),
			'dashicons-chart-line',
			'50.0'
			);
	}

	/**
	 * Includes the admin area display
	 *
	 * @since    1.0.0
	 */
	public function show_page() {
		$results = [];
		$departments = Departments::get_departments();	
		if(isset($_GET['leo_dept'])) {
			$qr = new Quiz_Results($_GET['leo_dept']);
			$results = $qr->get_results();
		}
		include __DIR__ . '/partials/leo-quiz-results-admin-display.php';
	}

	/**
	 * [export_quiz_results description]
	 * @return [type] [description]
	 */
	public function export_quiz_results() {
		$mtu = $_GET['mtu'];

		$q = new WP_Query([
			'post_type' => 'department',
			'meta_key'     => 'mtu',
			'meta_value'   => sanitize_text_field($mtu),
			'meta_compare' => '=',
			'posts_per_page' => -1
		]);
				
		$results = [];

		foreach($q->posts as $dept) {			
			$results = array_merge($results, (new Quiz_Results($dept->ID))->get_results());
		}
	
		$qre = new QuizResultsExporter($results);
		$qre->export();
	}
}
