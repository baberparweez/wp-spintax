<?php

class Spintax_Public
{

	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles()
	{
		if (!is_admin() && !defined('DOING_AJAX') && !$this->is_builder_context()) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/spintax-public.css', array(), $this->version, 'all');
			// Add inline style to hide the body content initially
			wp_add_inline_style($this->plugin_name, 'body { visibility: hidden; }');
		}
	}

	public function enqueue_scripts()
	{
		if (!is_admin() && !defined('DOING_AJAX') && !$this->is_builder_context()) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/spintax-public.js', array('jquery'), $this->version, true);
		}
	}

	private function is_builder_context()
	{
		global $post;

		// Check for Bricks Builder
		if (isset($_GET['bricks']) && $_GET['bricks'] === 'builder') {
			return true;
		}

		// Check for Elementor
		if (function_exists('Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode() && \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)) {
			return true;
		}
		if (function_exists('Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_edit_mode() && \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)) {
			return true;
		}

		// Check for Gutenberg
		if (is_admin() && function_exists('get_current_screen')) {
			$screen = get_current_screen();
			if ($screen && $screen->is_block_editor) {
				return true;
			}
		}

		return false;
	}

	public function spintax()
	{
		// Add JavaScript to handle the spintax transformations
		if (!is_admin() && !defined('DOING_AJAX') && !$this->is_builder_context()) {
			add_action('wp_footer', function () {
?>
				<script type="text/javascript">
					document.addEventListener('DOMContentLoaded', function() {
						function random(str) {
							return str.replace(/{(.*?)}/g, function(match, p1) {
								var words = p1.split("|");
								return words[Math.floor(Math.random() * words.length)];
							});
						}

						function js_random(str) {
							return str.replace(/~(.*?)~/g, function(match, p1) {
								var words = p1.split("|");
								return '<span class="spintax">' + words[0] + '<noscript>' + words.join('|') + '</noscript></span>';
							});
						}

						// Apply spintax to elements that are not part of the editor
						document.querySelectorAll('body *:not(.elementor-editor-active):not(.wp-admin):not(.edit-post-visual-editor)').forEach(function(element) {
							element.innerHTML = random(element.innerHTML);
							element.innerHTML = js_random(element.innerHTML);
						});

						// Reveal the body content after transformations are done
						document.body.style.visibility = 'visible';
					});
				</script>
			<?php
			});
		}
	}

	public function js_spintax()
	{
		// Add JavaScript to handle the dynamic spintax replacement
		if (!is_admin() && !defined('DOING_AJAX') && !$this->is_builder_context()) {
			add_action('wp_footer', function () {
			?>
				<script type="text/javascript">
					document.addEventListener('DOMContentLoaded', function() {
						function js_random(str) {
							return str.replace(/~(.*?)~/g, function(match, p1) {
								var words = p1.split("|");
								return '<span class="spintax">' + words[0] + '<noscript>' + words.join('|') + '</noscript></span>';
							});
						}

						jQuery(document).ready(function($) {
							var fadeSpeed = 350;
							$('.spintax').each(function() {
								var spintaxElement = $(this);
								var fullSpintax = spintaxElement.find('noscript').text();
								var spintaxArr = fullSpintax.split('|');
								var i = 0;

								spintaxElement.html(spintaxArr[i]).fadeIn(fadeSpeed);

								setInterval(function() {
									i = (i + 1) % spintaxArr.length;
									spintaxElement.fadeOut(fadeSpeed, function() {
										spintaxElement.html(spintaxArr[i]).fadeIn(fadeSpeed);
									});
								}, 2500);
							});
						});

						// Apply js_random to elements that are not part of the editor
						document.querySelectorAll('body *:not(.elementor-editor-active):not(.wp-admin):not(.edit-post-visual-editor)').forEach(function(element) {
							element.innerHTML = js_random(element.innerHTML);
						});

						// Reveal the body content after transformations are done
						document.body.style.visibility = 'visible';
					});
				</script>
<?php
			});
		}
	}
}
