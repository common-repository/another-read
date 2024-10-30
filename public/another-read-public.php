<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://anotherread.com
 * @package    Another_Read
 * @subpackage Another_Read/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Another_Read
 * @subpackage Another_Read/public
 * @author     Line Industries <support@lineindustries.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_Public
{
	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugins_url('/assets/css/another-read.css', __FILE__) , array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts()
	{
		// wp_enqueue_script($this->plugin_name, plugins_url( 'assets/js/another-read-public.js', __FILE__), array('jquery'), $this->version, false);
	}

}
