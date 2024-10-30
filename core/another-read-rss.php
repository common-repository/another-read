<?php
/**
 * RSS class
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_RSS
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

    public function set_rss_feeds()
    {
        add_feed( $this->plugin_name . '-activity-feed', array($this, 'another_read_activity_rss'));
    }

    public function another_read_activity_rss()
    {
        load_template(plugin_dir_path(__FILE__) . 'templates\rss\another-read-feed.php');
    }
}