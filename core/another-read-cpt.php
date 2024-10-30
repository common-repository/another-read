<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_CPT{

    


    public function register_custom_post_types(){
        $this->activityCPT();
        $this->activityTaxonomy();
        $this->stacksCPT();
    }

    public function register_meta_boxes(){
        $this->createMetaBoxes();
    }

    public function save_meta_boxes($post_id){
        $this->saveActivityMetaBoxes($post_id);
        $this->saveStacksMetaBoxes($post_id);
    }

    public function register_templates(){
        add_filter('single_template', array($this, 'setActivityTemplate'));
        add_filter('single_template', array($this, 'setStacksTemplate'));
    }

    private function activityCPT(){

        //custom post type
        register_post_type('activity',
        array(
            'labels' => array(
                'name' => 'Activities',
                'singular_name' => 'Activity',
                'add_new' => 'Add activity',
                'all_items' => 'All activities',
                'add_new_item' => 'Add activity',
                'edit_item' => 'Edit activity',
                'new_item' => 'New activity',
                'view_item' => 'View activity',
                'search_item' => 'Search activities',
                'not_foud' => 'No activities found',
                'not_found_in_trash' => 'No activities found in trash'
            ),
            'public' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true
            )
        );

        //removes editor from posts
        remove_post_type_support('activity', 'editor');
        remove_post_type_support('activity', 'author');
    }

    private function activityTaxonomy(){

        //custom taxonomy for the custom post type
        register_taxonomy('keywords', array('activity'), array(
            'labels' => array(
                'name' => 'Keywords',
                'singular_name' => 'Keyword',
                'search_items' => 'Search keywords',
                'all_items' => 'All keywords',
                'edit_item' => 'Edit keyword',
                'update_item' => 'Update keyword',
                'add_new_item' => 'Add new keyword',
                'new_item_name' => 'New keyword name',
                'menu_name' => 'Keyword'
            ),
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'keywords')
        ));
    }

    private function createMetaBoxes(){

        //Activity meta box
        add_meta_box(
            'activity_data_id',
            'Activity content',
            array($this, 'activity_data_html'),
            'activity'
        );

        //stack meta box
        add_meta_box(
            'stack_data_id',
            'Stack content',
            array($this, 'stack_data_html'),
            'stacks'
        );
    }
        
    public function activity_data_html($post){

        // Post meta data
        $activityContent = get_post_meta($post->ID, '_activity_content', true);

        //html for the meta boxes
        wp_nonce_field('activity_data_nonce', 'activity_data_nonce')
        ?>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="activity-id">Activity ID</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="activity_id" value="<?php echo esc_html($activityContent['activity_id']) ?>" id="activity_id">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="jacket-image">Link to jacket image</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="jacket_image" value="<?php echo esc_url($activityContent['jacket_image']) ?>" id="jacket_image">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="keynote">Keynote</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="keynote" value="<?php echo wp_kses_post($activityContent['keynote']) ?>" id="keynote">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="activity-date">Activity date</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="activity_date" value="<?php echo esc_html($activityContent['activity_date']) ?>" id="activity_date">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-isbn">Book ISBN</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book_isbn" value="<?php echo esc_html($activityContent['book_isbn']) ?>" id="book_isbn">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-name">Book name</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book_name" value="<?php echo esc_html($activityContent['book_name']) ?>" id="book_name">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-link">Link to book</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book_link" value="<?php echo esc_url($activityContent['book_link']) ?>" id="book_link">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="author-name">Author name</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="author_name" value="<?php echo esc_html($activityContent['author_name']) ?>" id="author_name">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="author-link">Link to author</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="author_link" value="<?php echo esc_html($activityContent['author_link']) ?>" id="author_link">
                </div>
            </div>

        <?php
    }

    private function saveActivityMetaBoxes( $post_id ){

        //saves the data entered into the meta boxes when the post is saved
        $keys = array('activity_id', 'jacket_image', 'keynote', 'activity_date', 'book_isbn', 'book_name', 'book_link', 'author_name', 'author_link');
        $activityContent = array();

        if( !isset($_POST['activity_data_nonce']) || !wp_verify_nonce($_POST['activity_data_nonce'], 'activity_data_nonce')){
            return;
        }
        if( array_key_exists('activity_id', $_POST) && $_POST['activity_id'] == $post_id){

            foreach($keys as $key){
                if($key == 'keynote'){
                    $activityContent[$key] = wp_kses_post($_POST[$key]);
                }
                else{
                    $activityContent[$key] = sanitize_text_field($_POST[$key]);
                }
            }

            update_post_meta(
                $post_id,
                '_activity_content',
                $activityContent
            );
        }
        
    }

    public function setActivityTemplate($single_template){
        global $post;

        if($post->post_type == 'activity'){
            $single_template = dirname(__FILE__) . '/templates/page/activity-post.php';

            return $single_template;
        }
        else{
            return $single_template;
        }
    }

    /* Another Read Stacks */

    private function stacksCPT(){

        //custom post type
        register_post_type('stacks',
        array(
            'labels' => array(
                'name' => 'Stacks',
                'singular_name' => 'Stack',
                'add_new' => 'Add Stack',
                'all_items' => 'All Stacks',
                'add_new_item' => 'Add Stack',
                'edit_item' => 'Edit Stack',
                'new_item' => 'New Stack',
                'view_item' => 'View Stack',
                'search_item' => 'Search Stacks',
                'not_foud' => 'No Stacks found',
                'not_found_in_trash' => 'No Stacks found in trash'
            ),
            'public' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true
            )
        );

        //removes editor from posts
        remove_post_type_support('stacks', 'editor');
        remove_post_type_support('stacks', 'author');
    }
        
    public function stack_data_html($post){

        //Obtains the values for the metaboxes
        $postMeta = get_post_meta($post->ID, '_stack_content', true);


            //html for the meta boxes
            wp_nonce_field('stack_data_nonce', 'stack_data_nonce')
            ?>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="stack-id">Stack ID</label>
                    </div>
                    <div class="meta-input">
                        <input type="text" name="stack_id" value="<?php echo esc_attr($post->ID) ?>" id="stack_id">
                    </div>
                </div>
        <?php
        $i = 1;
        //Loops through the array and sets the values for the metaboxes
        foreach($postMeta['book_list'] as $metaData){

        ?>
                <div class="header"> <h1>Book <?php echo esc_html($i) ?></h1></div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-name">Book name</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo esc_html($i); ?>_book_name" value="<?php echo esc_html($metaData['book_name']) ?>" id="book_name">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="jacket-image">Link to jacket image</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo esc_html($i); ?>_jacket_image" value="<?php echo esc_url($metaData['jacket_image']) ?>" id="jacket_image">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="keynote">Keynote</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo esc_html($i); ?>_keynote" value="<?php echo wp_kses_post($metaData['keynote']) ?>" id="keynote">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-isbn">Book ISBN</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo esc_html($i); ?>_book_isbn" value="<?php echo esc_html($metaData['book_isbn']) ?>" id="book_isbn">
                    </div>
                </div>

                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-link">Link to book</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo esc_html($i); ?>_book_link" value="<?php echo esc_url($metaData['book_link']) ?>" id="book_link">
                    </div>
                </div>
                <?php 
                $j = 0;
                foreach($metaData['contributors'] as $contributor) { ?>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="author-name">Author name</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo esc_html($i); ?>_author_name_<?php echo esc_attr($j); ?>" value="<?php echo esc_html($contributor['author_name']) ?>" id="author_name">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="author-link">Link to author</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo esc_html($i); ?>_author_link_<?php echo esc_attr($j); ?>" value="<?php echo esc_url($contributor['author_link']) ?>" id="author_link">
                    </div>
                </div>
        <?php
                }
            $i++;
        }
    }

    private function saveStacksMetaBoxes($post_id){


        //saves the data entered into the meta boxes when the post is saved

        if( !isset($_POST['stack_data_nonce']) || !wp_verify_nonce($_POST['stack_data_nonce'], 'stack_data_nonce')){
            return;
        }

        if( array_key_exists('stack_id', $_POST) && $_POST['stack_id'] == $post_id){
            $i = 1;
            $k = 0;
            $meta = get_post_meta($post_id, '_stack_content', true);

            //Loops through the current meta array and sets the new values for the metaboxes
            foreach($meta['book_list'] as $book){
                $book['book_name'] = sanitize_text_field($_POST['book_'.$i.'_book_name']);
                $book['jacket_image'] = sanitize_text_field($_POST['book_'.$i.'_jacket_image']);
                $book['keynote'] = wp_kses_post($_POST['book_'.$i.'_keynote']);
                $book['book_isbn'] = sanitize_text_field($_POST['book_'.$i.'_book_isbn']);
                $book['book_link'] = sanitize_text_field($_POST['book_'.$i.'_book_link']);
                $book['contributors'] = array();
                $j = 0;
                foreach($book['contributors'] as $contributor){
                    $contributor[$j] = array(
                        'author_name' => sanitize_text_field($_POST['book_'.$i.'_author_name_'.$j]),
                        'author_link' => sanitize_text_field($_POST['book_'.$i.'_author_link_'.$j])
                    );
                    $j++;
                }

                $stackContent['book_list'][$i] = $book;

                $i++;
                $k++;
            }

            $meta['book_list'] = $stackContent['book_list'];
            
            update_post_meta(
                $post_id,
                '_stack_content',
                $meta
            );
        }
        
    }

    public function setStacksTemplate($single_template){
        global $post;

        if($post->post_type == 'stacks'){
            $single_template = dirname(__FILE__) . '/templates/page/stack-post.php';

            return $single_template;
        }
        else{
            return $single_template;
        }
    }

}


?>