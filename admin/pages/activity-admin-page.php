<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
*   Activity admin page
*/

    $settings = Another_Read_Settings::get_settings();

    $publishers = array("Pan Macmillan", "Barefoot Books", "Abrams Books", "Chronicle Books", "Princeton Architectural Press", "Galison Mudpuppy", "Child's Play", "Quarto", "Sweet Cherry Publishing", "Line Industries", "Nobrow", "Childs Play", "Childsplay", "Penguin Books Ltd", "Pushkin Press", "RHCP", "Child's Play (International) Ltd", "Frances Lincoln Children's Books", "Frances Lincoln", "Voyageur Press", "becker&mayer! kids", "QED Publishing", "Walter Foster Jr", "Quarry Books", "Walter Foster Publishing", "Wide Eyed Editions", "Seagrass Press", "MoonDance Press", "Faber & Faber", "Rock Point", "words & pictures", "Ivy Kids", "Ivy Press", "Exisle Publishing", "Zest Books", "Rockport Publishers", "Creative Publishing international", "Race Point Publishing", "Cool Springs Press", "Macmillan", "Young Voyageur", "EK Books", "Leaping Hare Press", "Famillus", "Lincoln Children's Books", "QEB Publishing", "Quarto Children's Books", "QED", "Little Pink Dog Books", "Andersen Press Ltd", "Scribe Publications Pty Ltd.","Curious Fox", "Raintree", "Fair Winds Press", "Apple Press", "Penguin Random House Children's UK", "becker&mayer! books ISBN", "Happy Yak", "Pavilion Children's", "Transworld", "Child's Play (International) Ltd");

    if(isset($_POST['another_read_activity_settings_nonce']) && wp_verify_nonce($_POST['another_read_activity_settings_nonce'], 'another_read_activity_settings_nonce')) {

        if(isset($_POST['update_settings']) || isset($_POST['update_posts'])){
            $settings['api_key'] = sanitize_text_field($_POST['api_key']);
            $settings['activity']['keyword'] = sanitize_text_field($_POST['keyword']);
            $settings['activity']['contributor'] = sanitize_text_field($_POST['contributor']);
            $settings['activity']['publisher'] = sanitize_text_field($_POST['publisher']);
            $settings['activity']['results'] = intval($_POST['results']);

            Another_Read_Settings::update_settings($settings);


            echo wp_kses_post('<div class="notice notice-success is-dismissible"><p>Settings were updated successfuly</p></div>');
        }

        if(isset($_POST['update_posts'])){

            $activities = Another_Read_Post_Creator::createActivity();
            if($activities == true){
                echo wp_kses_post('<div class="notice notice-success is-dismissible"><p>Activities were updated successfuly</p></div>');
            }
            elseif($activities == 'no_api_key'){
                echo wp_kses_post('<div class="notice notice-error is-dismissible"><p>There was no API key. Please check your settings and try again.</p></div>');
            }
            elseif($activities == 'api_error'){
                echo wp_kses_post('<div class="notice notice-error is-dismissible"><p>There was an error with the API call. Please check your settings and try again.</p></div>');
            }
            else{
                echo wp_kses_post('<div class="notice notice-error is-dismissible"><p>There was an error. Please check your settings and try again.</p></div>');
            }
        }

    }
    elseif(isset($_POST['another_read_activity_settings_nonce'])) {
        echo wp_kses_post('<div class="notice notice-error is-dismissible"><p>There was an error please try again.</p></div>');
    }

    $settings = Another_Read_Settings::get_settings();
    $options = $settings['activity'];

    ?>
    <div class="another-read-admin">
        <div class="another-read-admin-settings">
            <div class="admin-header">
                <h1>Another Read</h1>
                <h2>Activity Post Settings</h2>
            </div>
            <div class="another-read-activity-settings">
                <form action="" method="post">
                    <?php wp_nonce_field('another_read_activity_settings_nonce', 'another_read_activity_settings_nonce'); ?>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="keyword">Activity keyword</label>
                                </th>
                                <td>
                                    <input type="text" id="keyword" name="keyword" value="<?php if(isset($options['keyword'])){echo esc_attr($options['keyword']);}  ?>" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <th scope="row">
                                    <label for="contributor">Contributor ID</label>
                                </th>
                                <td>
                                    <input type="text" id="contributor" name="contributor" value="<?php if(isset($options['contributor'])){echo esc_attr($options['contributor']);}  ?>" class="regular-text">
                                    <p>Find your contributor ID by logging in to <a href="https://anotherread.com">anotherread.com</a> and viewing your <a href="https://anotherread.com/tools/account-details">account details</a> page.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="publisher">Publisher</label>
                                </th>
                                <td>
                                    <select name="publisher" id="publisher">
                                        <option value="" <?php if(isset($options['publisher'])){ echo 'selected';} ?>>Select</option>
                                        <?php foreach($publishers as $publisher){
                                            echo '<option value="'.esc_attr($publisher).'"' .(isset($options['publisher']) && $options['publisher'] == $publisher ? "selected" : "").'>'.esc_html($publisher).'</option>';
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="results">Enter the number of posts wanted during the next update</label>
                                </th>
                                <td>
                                    <input type="number" id="results" name="results" value="<?php if(isset($options['results'])){echo esc_attr($options['results']);} ?>" required class="regular-text">
                                </td>
                            </tr>
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
                                    <h4>Last updated</h4>
                                </th>
                                <td>
                                    <p><?php if(isset($options['timestamp']) && $options['timestamp'] !== 0){ echo esc_html($options['timestamp']->format('Y-m-d H:i'));}else{ echo "There has been no updates";} ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div>
                        <p class="submit">
                            <input type="submit" name="update_settings" class="button button-primary" value="Save settings" >
                            <input type="submit" name="update_posts" class="button button-primary" value="Update posts" >
                        </p>
                    </div>
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
    </div>
