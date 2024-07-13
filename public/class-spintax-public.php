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

		if (isset($_GET['bricks']) && $_GET['bricks'] === 'builder') {
			return true;
		}

		if (function_exists('Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode() && \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)) {
			return true;
		}
		if (function_exists('Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_edit_mode() && \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)) {
			return true;
		}

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
		// This function is intentionally left empty since the logic is moved to the JS file
	}
	public function js_spintax()
	{
		// This function is intentionally left empty since the logic is moved to the JS file
	}
}
