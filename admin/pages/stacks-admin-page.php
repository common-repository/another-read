<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
*   Stacks admin page
*/

$settings = Another_Read_Settings::get_settings();
$options = $settings['stacks'];

if(isset($_POST['another_read_stacks_settings_nonce']) && wp_verify_nonce($_POST['another_read_stacks_settings_nonce'], 'another_read_stacks_settings_nonce')) {

    if(isset($_POST['update_settings']) || isset($_POST['update_posts'])){
        $settings['api_key'] = sanitize_text_field($_POST['api_key']);
        $settings['stacks']['keynote'] = sanitize_text_field($_POST['keynote']);

        Another_Read_Settings::update_settings($settings);

        $settings = Another_Read_Settings::get_settings();

        echo '<div class="notice notice-success is-dismissible"><p>Settings were updated successfuly</p></div>';
    }



    if(isset($_POST['update_posts'])){

        $stacks = Another_Read_Post_Creator::createStacks();
        if($stacks == true){
            echo '<div class="notice notice-success is-dismissible"><p>Stacks were updated successfuly</p></div>';
        }
        elseif($stacks == 'no_api_key'){
            echo '<div class="notice notice-error is-dismissible"><p>There was no API key. Please check your settings and try again.</p></div>';
        }
        elseif($stacks == 'api_error'){
            echo '<div class="notice notice-error is-dismissible"><p>There was an error with the API call. Please check your settings and try again.</p></div>';
        }
        else{
            echo '<div class="notice notice-error is-dismissible"><p>There was an error. Please check your settings and try again.</p></div>';
        }
    }

    if(isset($_POST['ar_login']) && $_POST['ar_login'] == 'Login'){
        $login = Another_Read_Settings::login();
        if($login == true){
            echo '<div class="notice notice-success is-dismissible"><p>Login was successful</p></div>';
        }
        else{
            echo '<div class="notice notice-error is-dismissible"><p>There was an error with the provided login details. Please check your details and try again.</p></div>';
        }
        $settings = Another_Read_Settings::get_settings();
        $options = $settings['stacks'];
    }

    if(isset($_POST['ar_logout']) && $_POST['ar_logout'] == 'Logout' ){
        $logout = Another_Read_Settings::logout();
        if($logout == true){
            echo '<div class="notice notice-success is-dismissible"><p>Logout was successful</p></div>';
        }
        else{
            echo '<div class="notice notice-error is-dismissible"><p>There was an error please try again.</p></div>';
        }
        $settings = Another_Read_Settings::get_settings();
        $options = $settings['stacks'];
    }

}
elseif(isset($_POST['another_read_stacks_settings_nonce'])) {
    echo '<div class="notice notice-error is-dismissible"><p>There was an error please try again.</p></div>';
}

?>
<div class="another-read-admin">
    <div class="another-read-admin-settings">
        <div class="admin-header">
            <h1>Another Read</h1>
            <h2>Stacks Settings</h2>
        </div>
        <div class="another-read-activity-settings">
            <form action="" method="post">
                <?php wp_nonce_field('another_read_stacks_settings_nonce', 'another_read_stacks_settings_nonce'); ?>
                <table class="form-table">
                    <tbody>
                        <?php if($options['logged_in'] == false || $options['user_token'] == null) : ?>
                            <tr>
                                <th scope="row">
                                    <label for="username">Another Read Username</label>
                                </th>
                                <td>
                                    <input type="text" id="username" name="username" value="" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <th scope="row">
                                    <label for="password">Another Read Password</label>
                                </th>
                                <td>
                                    <input type="password" id="password" name="password" value="" class="regular-text">
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th scope="row">
                                <label for="login">Another Read account status</label>
                            </th>
                            <td>
                                <?php if($options['logged_in'] == false || $options['user_token'] == null) : ?>
                                    <input class="button button-primary" name="ar_login" type="submit" value="Login">
                                <?php elseif($options['logged_in'] == true && isset($options['user_token_expirey'])) : ?>
                                    <p>Login expires on: </p> <?php echo esc_html($options['user_token_expirey']); ?>
                                    <br>
                                    <input class="button button-primary" name="ar_logout" type="submit" value="Logout" formnovalidate>
                                <?php endif; ?>

                            </td>
                        </tr>
                        <?php if($options['logged_in'] == true){ ?>
                        <tr>
                            <th scope="row">
                                <label for="api_key">Another Read API key</label>
                            </th>
                            <td>
                            <input class="regular-text" type="text" id="api_key" name="api_key" value="<?php if(isset($settings['api_key'])){echo esc_attr($settings['api_key']);} ?>" required>
                                <p>Get your API key at <a href="https://anotherread.com">anotherread.com</a>.</p>

                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="keynote">Display keynote for stacks</label>
                            </th>
                            <td>
                                <input name="keynote" type="hidden" value="0">
                                <input name="keynote" id="keynote" type="checkbox" value="1" <?php checked('1', $options['keynote']) ?> >

                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <h4>Last updated</h4>
                            </th>
                            <td>
                                <p><?php if(isset($options['timestamp']) && $options['timestamp'] !== 0){ echo esc_html($options['timestamp']->format('Y-m-d H:i'));}else{ echo "There has been no updates";} ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <input type="submit" name="update_settings" class="button button-primary" value="Save settings" >
                            </th>
                            <td>
                            <input type="submit" name="update_posts" class="button button-primary" value="Update stacks" >

                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>

            </form>                  
        </div>
    </div>
    <div class="another-read-content">
        <div class="another-read-banner">
            <div class="branding">
                <a href="https://anotherread.com" target="_blank">
                    <img class="branding-img" src="<?php echo esc_url(plugins_url('/assets/img/brand--extended--red.svg', dirname(__FILE__))); ?>" alt="Another Read">
                </a>
            </div>
            <div class="banner-content">
                <h2>Find children's books you like and we will recommend others.</h2>
                <p>Our app helps parents and young readers discover the latest children's books as well as all-time favourites. We make topical recommendations based on the books readers like and bring them news and events from their favourite authors and illustrators</p>
                <img class="content-img" src="<?php echo esc_url(plugins_url('/assets/img/home-app-device-mock-up.jpg', dirname(__FILE__)))?>" alt="">
            </div>
        </div>
    </div>