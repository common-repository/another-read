<?php

/**
 * @link       https://anotherread.com
 * @package    Another_Read
 * @subpackage Another_Read/admin
 */

/**
 * @package    Another_Read
 * @subpackage Another_Read/admin
 * @author     Line Industries <support@lineindustries.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_Admin
{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function add_menu_page()
    {
        add_menu_page(
            'Another Read',
            'Another Read',
            'manage_options',
            'another-read',
            __return_null(),
            plugin_dir_url(__FILE__) . '/assets/img/brand--red--small.svg'
        );

        add_submenu_page(
            'another-read',
            'Activity',
            'Activity',
            'manage_options',
            'another-read',
            array($this, 'render_activity_admin_page')
        );

        add_submenu_page(
            'another-read',
            'Stacks',
            'Stacks',
            'manage_options',
            'another-read-stacks',
            array($this, 'render_stacks_admin_page')
        );
    }

    public function render_activity_admin_page()
    {
        require_once plugin_dir_path(__FILE__) . 'pages/activity-admin-page.php';
    }

    public function render_stacks_admin_page()
    {
        require_once plugin_dir_path(__FILE__) . 'pages/stacks-admin-page.php';
    }

    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugins_url('assets/css/another-read-admin.css', __FILE__) , array(), $this->version, 'all');
    }

    public function enqueue_scripts()
    {
        // wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/js/another-read-admin.js', array('jquery'), $this->version, false);
    }

}