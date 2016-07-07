<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/gr33k01
 * @since      1.0.0
 *
 * @package    Leo_Quiz_Results
 * @subpackage Leo_Quiz_Results/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Leo_Quiz_Results
 * @subpackage Leo_Quiz_Results/public
 * @author     Nate Hobi <nate.hobi@gmail.com>
 */
class Leo_Quiz_Results_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Leo_Quiz_Results_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Leo_Quiz_Results_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/leo-quiz-results-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Leo_Quiz_Results_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Leo_Quiz_Results_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/leo-quiz-results-public.js', array( 'jquery' ), $this->version, true );

	}

	public function register_shortcodes() {
		add_shortcode('quiz-results', array($this, 'show_quiz_results'));
	}

	public function show_quiz_results() {
		ob_start();
		$this->quiz_results_view();
		$output_string = ob_get_contents();;
		ob_end_clean();

		return $output_string;		
	}

	public function quiz_results_view() {
		$departments = Departments::get_departments_current_user_is_head_of();		
		$qr = new Quiz_Results();
		$quizzes = $qr->get_all_quizzes();

		$quiz_filter_id = $_GET['quiz_id'];		
		$has_filter = $quiz_filter_id != 'all';
		$quiz_name = '';

		// Set default to most recent quiz
		if(!$_GET['quiz_id']) {
			$quiz_filter_id = end($quizzes)->id;
			$quiz_name = end($quizzes)->name;
		}

		if($has_filter) {
			foreach($quizzes as $quiz) {
				if($quiz->id == $quiz_filter_id) {					
					$quiz_name = $quiz->name;
				}
			}
		}

		include __DIR__ . '/partials/leo-quiz-results-show-quiz-results.php';
	}
}
