<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


//clear options from database
Another_Read_Settings::delete_settings();

//clear posts from database
function another_read_delete_posts(){
    $args = array(
        'post_type' => array('activity', 'stacks'),
        'posts_per_page' => -1,
    );
    $posts = get_posts($args);
    foreach($posts as $post){
        wp_delete_post($post->ID, true);
    }
}

?>
