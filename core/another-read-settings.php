<?php
/**
 * Settings class
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_Settings
{

    public static string $option_name = 'another_read_settings';

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public static function settings_exist()
    {
        if (get_option(self::$option_name)) {
            return true;
        }
        return false;
    }

    public static function create_settings()
    {
        if (!self::settings_exist()) {
            add_option(self::$option_name, array(
                "stacks" => array(
                    "logged_in" => false,
                    "user_token" => "",
                    "api_call_success" => null,
                    "keynote" => "0",
                    "timestamp" => 0,
                    "user_token_expirey" => null
                ),
                "activity" => array(
                    "keyword" => "",
                    "contributor" => "",
                    "publisher" => "",
                    "results" => "5",
                    "api_call_success" => null,
                    "timestamp" => 0,
                ),
                "api_key" => "",

            ));
        }
    }

    public static function get_settings()
    {
        if(!self::settings_exist()){
            $settings = self::create_settings();
        }
        else{
            $settings = get_option(self::$option_name);
        }
        return $settings;

    }

    public static function update_settings($settings)
    {
        update_option(self::$option_name, $settings);
    }

    public static function login(){
        if(isset($_POST['another_read_stacks_settings_nonce']) && wp_verify_nonce($_POST['another_read_stacks_settings_nonce'], 'another_read_stacks_settings_nonce')) {
            $settings = self::get_settings();
            $data = array(
                "username" => sanitize_text_field($_POST['username']),
                "password" => sanitize_text_field($_POST['password']),
            );

            $loginPayload = Another_Read_Api::api_call('login', $data );

            if($loginPayload['ApiCallWasSuccessful'] == true){
                $settings['stacks']['user_token'] = $loginPayload['Payload']['ApiKey'];
                $settings['stacks']['user_token_expirey'] = $loginPayload['Payload']['ApiKeyExpiryDate'];
                $settings['stacks']['logged_in'] = true;
                self::update_settings($settings);
                return true;
            }
            else{
                return false;
            }
        } else {
            return false;
        }
    }

    public static function logout()
    {
        $settings = self::get_settings();
        $settings['stacks']['user_token'] = null;
        $settings['stacks']['user_token_expirey'] = null;
        $settings['stacks']['logged_in'] = false;
        self::update_settings($settings);
        return true;
    }


    public static function delete_settings()
    {
        delete_option(self::$option_name);
    }

}