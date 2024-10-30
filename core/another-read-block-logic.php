<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_Block_Logic{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_blocks()
    {
        $this->createActivityBlock();
        //$this->createStacksBlock(); // This is commented out because it's not ready yet. - Requires moving to new style of block creation.
    }

    public function register_block_category( $categories ){
        return array_merge(
            array(
                array(
                    'slug' => 'another-read',
                    'title' => __( 'Another Read', 'another-read' ),
                    'icon'  => null,
                ),
            ),
            $categories
        );
    }
    
    private function createActivityBlock(){
        $assetFile = include( plugin_dir_path(dirname(__FILE__)) . '/blocks/build/block-activity/index.asset.php');

        wp_register_script(
            $this->plugin_name . '-activity-block-editor',
            plugins_url( 'blocks/build/block-activity/index.js', dirname(__FILE__) ),
            $assetFile['dependencies'],
            $assetFile['version']
        );

        register_block_type('another-read/activity-block', array(
            'editor_script' => $this->plugin_name . '-activity-block-editor',
            'render_callback' => array($this, 'activityBlockOutput'),
            'category' => 'another-read',

            'attributes' => array(
                'numberOfPosts' => array(
                    'type' => 'integer',
                    'default' => 1
                ),
                'layout' => array(
                    'type' => 'string',
                    'default' => 'column'
                ),
                'jacketImage' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'keynote' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'authorLink' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'bookLink' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
            )
        ));
    }

    static function activityBlockOutput($block_attributes, $content){

        $recent_posts = wp_get_recent_posts( array(
            'post_type' => 'activity',
            'numberposts' => $block_attributes['numberOfPosts'],
            'post_status' => 'publish',
        ) );

        ob_start(); ?>

        <div class="ar-activity-block">
            <?php
            foreach($recent_posts as $recent_post) :
                $title = $recent_post['post_title'];
                $ActivityContent = get_post_meta($recent_post['ID'], '_activity_content', true);

                $jacketImage = $ActivityContent['jacket_image'];
                $activityDate = $ActivityContent['activity_date'];
                $keynote = $ActivityContent['keynote'];
                $bookName = $ActivityContent['book_name'];
                $bookLink = $ActivityContent['book_link'];
                $authorName = $ActivityContent['author_name'];
                $authorLink = $ActivityContent['author_link'];
            ?>

                <div class="ar-activity">
                    <div class="ar-activity-title">
                        <?php if($block_attributes['jacketImage']) : ?>
                            <?php if($block_attributes['bookLink']) : ?>
                                <a href="<?php echo esc_url($bookLink); ?>">
                                    <img src="<?php echo esc_url($jacketImage); ?>" alt="<?php echo esc_attr($bookName); ?>">
                                </a>
                            <?php else : ?>
                                <img src="<?php echo esc_url($jacketImage); ?>" alt="<?php echo esc_attr($bookName); ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                        <h2 class="ar-activity-name">
                            <?php echo esc_html($title); ?>
                        </h2>
                    </div>
                    <div class="ar-activity-body">
                        <p class="ar-activity-date"><?php echo esc_html($activityDate) ?></p>
                        <?php if($block_attributes['keynote']) : ?>
                            <p class="ar-activity-keynote"><?php echo wp_kses_post($keynote) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="ar-read-more">
                        <a class="button button-primary" href="<?php echo esc_url(get_permalink($recent_post['ID'])); ?>">Read More</a>
                    </div>
                    <div class="ar-activity-book">
                        <div class="ar-book">
                            <h3 class="ar-book-title">
                                <?php if($block_attributes['bookLink']) : ?>
                                    <a href="<?php echo esc_url($bookLink); ?>">
                                        <?php echo esc_html($bookName); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo esc_html($bookName); ?>
                                <?php endif; ?>
                            </h3>
                            <p class="ar-book-author">
                                <?php if($block_attributes['authorLink']) : ?>
                                    <a href="<?php echo esc_url($authorLink); ?>">
                                        <?php echo esc_html($authorName); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo esc_html($authorName); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
            <?php endforeach; ?>
        </div>

        <?php return ob_get_clean();

    }

    private function createStacksBlock(){
        $assetFile = include( plugin_dir_path(dirname(__FILE__))  . '/blocks/build/block-stacks/index.asset.php');

        wp_register_script(
            $this->plugin_name . '-stacks-block-editor',
            plugins_url( 'blocks/build/block-stacks/index.js', dirname(__FILE__) ),
            $assetFile['dependencies'],
            $assetFile['version']
        );

        register_block_type('another-read/stacks-block', array(
            'editor_script' => $this->plugin_name . '-stacks-block-editor',
            'render_callback' => array($this, 'stacksBlockOutput'),
            'category' => 'another-read',

            'attributes' => array(
                'selectedStack' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'numberOfBooks' => array(
                    'type' => 'int',
                    'default' => 0
                ),
                'jacketImage' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'keynote' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'authorLink' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'bookLink' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
            )
        ));


    }

    private function stacksBlockOutput($block_attributes, $content){

        $StackContent = get_post_meta($block_attributes['selectedStack'], '_stack_content', true);

        ob_start();

        ?>
        <div class="ar-stacks-block">
            <?php foreach($StackContent['book_list'] as $books) : 
                $title = $books['book_name'];
                $jacketImage = $books['jacket_image'];
                $keynote = $books['keynote'];
                $bookName = $books['book_name'];
                $bookLink = $books['book_link'];
                $contributors = $books['contributors'];
            ?>
                <div class="ar-stack-book">
                    <div class="ar-book-title">
                        <?php if ($block_attributes['jacketImage'] == true) : ?>
                            <img src="<?php echo esc_url($jacketImage); ?>" alt="<?php echo esc_attr($bookName); ?>">
                        <?php endif; ?>
                        <h2><?php echo esc_html($title); ?></h2>
                    </div>
                    <div class="ar-book-body">
                        <?php if ($block_attributes['keynote'] == true) : ?>
                            <p class="ar-book-keynote"><?php echo wp_kses_post($keynote); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="ar-book-footer">
                        <div class="ar-book-link">
                            <?php if($block_attributes['bookLink'] == true) : ?>
                                <a href="<?php echo esc_url($bookLink); ?>">
                            <?php endif; ?>
                            <?php echo esc_html($bookName); ?></a>
                        </div>
                        <div class="ar-book-author">
                            <?php foreach($contributors as $contributor) : ?>
                                <?php if( isset($contributor['author_name'])) : ?>
                                    <a href="<?php echo esc_url($contributor['author_link']); ?>">
                                <?php endif; ?>
                                <?php echo esc_html($contributor['author_name']); ?></a>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <br>
            <div class="ar-read-more">
                <a class="button button-primary" href="<?php echo esc_url(get_permalink($block_attributes['selectedStack'])); ?>">Read More</a>
            </div>
        </div>
        <?php

        $str = ob_get_clean();
        return $str;

    }

}
?>