<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://line.industries
 * @package    Another_Read
 * @subpackage Another_Read/includes
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
 * @package    Another_Read
 * @subpackage Another_Read/includes
 * @author     Line Industries <support@lineindustries.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct()
	{
		if(defined('ANOTHER_READ_VERSION'))
			$this->version = ANOTHER_READ_VERSION;
		else
			$this->version = '1.0.0';

		$this->plugin_name = 'another-read';

		$this->load_dependencies();
		$this->define_admin_hooks();
        $this->define_custom_post_type_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() : string
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'core/another-read-settings.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'core/another-read-post-creation.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'core/another-read-api.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'core/another-read-cpt.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'core/another-read-rss.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'core/another-read-block-logic.php';



		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'core/another-read-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/another-read-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/another-read-public.php';

		$this->loader = new Another_Read_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Another_Read_Admin($this->get_plugin_name(), $this->get_version());
		$block_logic = new Another_Read_Block_Logic($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_menu_page');
        $this->loader->add_action('block_categories_all', $block_logic, 'register_block_category');
        $this->loader->add_action('init', $block_logic, 'enqueue_blocks');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Another_Read_Public($this->get_plugin_name(), $this->get_version());
        $rss_feed = new Another_Read_RSS($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_action('init', $rss_feed, 'set_rss_feeds');

	}

    /**
	 * Register all of the hooks related to the custom post type and taxnonmy functionality
	 * of the plugin.
	 */
    private function define_custom_post_type_hooks()
    {
        $plugin_custom_post_types = new Another_Read_CPT($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_custom_post_types, 'register_custom_post_types');
        $this->loader->add_action('add_meta_boxes', $plugin_custom_post_types, 'register_meta_boxes');
        $this->loader->add_action('save_post', $plugin_custom_post_types, 'save_meta_boxes');
        $this->loader->add_action('init', $plugin_custom_post_types, 'register_templates');

    }
}
