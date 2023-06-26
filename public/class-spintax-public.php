<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       -
 * @since      1.0.0
 *
 * @package    Spintax
 * @subpackage Spintax/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Spintax
 * @subpackage Spintax/public
 * @author     Baber Parweez <baberparweez@gmail.com>
 */
class Spintax_Public {

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
		 * defined in Spintax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spintax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spintax-public.css', array(), $this->version, 'all' );

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
		 * defined in Spintax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spintax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spintax-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds the spintax functionality to text editors
	 *
	 * @return null
	 */
	public function spintax() {
		// Pass in the string you'd for which you'd like a random output
		function random($str) {
			// Returns random values found between { this | and }
			$content = preg_replace_callback("/{(.*?)}/", function ($match) {
				// Splits 'foo|bar' strings into an array
				$words = explode("|", $match[1]);
				// Grabs a random array entry and returns it
				return $words[array_rand($words)];
			// The input string, which you provide when calling this func
			}, $str);

			return $content;
		}
		
		// Apply spintax filter to necessary hooks for text editors
		add_filter('the_content', 'random');
		add_filter('widget_text_content', 'random'); // Apply to text widgets
		add_filter('widget_text_content', 'do_shortcode'); // Allow shortcodes in text widgets
		add_filter('acf_the_content', 'random'); // Apply to Advanced Custom Fields content
		// Add more editor-specific hooks here if needed

		// Apply spintax filter to WYSIWYG editor
		add_filter('tiny_mce_before_init', function ($init_array) {
			$init_array['setup'] .= 'ed.onSetContent.add(function(ed, o) { tinymce.activeEditor.setContent(random(tinymce.activeEditor.getContent())); });';
			return $init_array;
		});
	}

	/**
	 * Adds the dynamic spintax functionality to text editors
	 *
	 * @return null
	 */
	public function js_spintax() {
		function js_random($str)
		{
			// Returns random values found between { this | and }
			$content = preg_replace_callback("/~(.*?)~/", function ($match) {
				// Splits 'foo|bar' strings into an array
				$words = explode("|", $match[1]);
				// Grabs a random array entry and returns it
				$span = '<span class="spintax">' . implode('|', $words) . '</span>';
				return $span;
				// The input string, which you provide when calling this func
			}, $str);

			ob_start();
			?>
			<script type="text/javascript">
				var fadeSpeed = 250;
				jQuery('.spintax').each(function() {
					var spintaxElement = jQuery(this);
					var spintaxArr = spintaxElement.text().split('|');
					var i = 0;

					spintaxElement.html(spintaxArr[i]).fadeIn(fadeSpeed);

					setInterval(function() {
						i = (i + 1) % spintaxArr.length;
						spintaxElement.fadeOut(fadeSpeed, function() {
							spintaxElement.html(spintaxArr[i]).fadeIn(fadeSpeed);
						});
					}, 1500);
				});
			</script>
			<?php

			$script = ob_get_clean();
			return $content . $script;
		}
		
		// Apply spintax filter to necessary hooks for text editors
		add_filter('the_content', 'js_random');
		add_filter('widget_text_content', 'js_random'); // Apply to text widgets
		add_filter('widget_text_content', 'do_shortcode'); // Allow shortcodes in text widgets
		add_filter('acf_the_content', 'js_random'); // Apply to Advanced Custom Fields content
		// Add more editor-specific hooks here if needed

		// Apply spintax filter to WYSIWYG editor
		add_filter('tiny_mce_before_init', function ($init_array) {
			$init_array['setup'] .= 'ed.onSetContent.add(function(ed, o) { tinymce.activeEditor.setContent(js_random(tinymce.activeEditor.getContent())); });';
			return $init_array;
		});
	}
}
