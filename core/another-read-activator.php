<?php

/**
 * Fired during plugin activation
 *
 * @link       https://anotherread.com
 * @package    Another_Read
 * @subpackage Another_Read/core
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Another_Read
 * @subpackage Another_Read/core
 * @author     Line Industries <support@lineindustries.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_Activator
{
	/**
	 * Fired during plugin activation
	 */
	public static function activate()
	{
		$settings_were_already_in_db = Another_Read_Settings::settings_exist();

		// $existing_settings = Another_Read_Settings::get_settings();

        if (!$settings_were_already_in_db) {
            Another_Read_Settings::create_settings();
        }

	}

}