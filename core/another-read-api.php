<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_Api
{
    static function api_call($endpoint, $data){

        $options = Another_Read_Settings::get_settings();

        $endpoints = array(
            "login" => "https://anotherread.com/api/user/json/v1/get-api-key/default.aspx",
            "activity" => "https://anotherread.com/site/read/templates/api/activities/json/v2/get-activity-list/default.aspx",
            "stacks" => "https://anotherread.com/site/read/templates/api/stacks/json/v2/get-stack-admin-list/default.aspx",
        );

        $headers = array(
            "Accept" => "application/json"
        );

        $response = wp_remote_post( $endpoints[$endpoint], array(
            'method' => 'POST',
            'headers' => $headers,
            'body' => $data
        ));

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: " . wp_kses_post($error_message);
        } else {
            $response = json_decode( wp_remote_retrieve_body( $response ), true );
            
            if($endpoint !== 'login'){
                if($response["ApiCallWasSuccessful"] == true)
                {
                    $timestamp = new DateTime();
                    if($options[$endpoint]['timestamp'] !== false){
                        $options[$endpoint]['timestamp'] = $timestamp;
                    }
                    $options[$endpoint]['api_call_success'] = true;
                    Another_Read_Settings::update_settings($options);
                    return $response;
                }
                else{                
                    $options[$endpoint]['apiCallSuccessful'] = false;
                    Another_Read_Settings::update_settings($options);
                    return $response;
                }
            }
            else{
                return $response;
            }
        }
    }
}