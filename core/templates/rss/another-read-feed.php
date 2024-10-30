<?php
/**
* Template Name: Custom RSS Template - Another Read Feed 
*/
if ( ! defined( 'ABSPATH' ) ) exit;
$postCount = 5; // The number of posts to show in the feed
$posts = query_posts(array(
        'showposts=' . $postCount,
        'post_type' => 'activity',
        'post_status' => 'publish'));

header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo esc_html('<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>');
?>
<rss version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:wfw="http://wellformedweb.org/CommentAPI/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        <?php do_action('rss2_ns'); ?>>
<channel>
        <title><?php bloginfo_rss('name'); ?> - Feed</title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <lastBuildDate><?php echo esc_html(mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false)); ?></lastBuildDate>
        <language><?php echo esc_html(get_option('rss_language')); ?></language>
        <sy:updatePeriod><?php echo esc_html(apply_filters( 'rss_update_period', 'hourly' )); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo esc_html(apply_filters( 'rss_update_frequency', '1' )); ?></sy:updateFrequency>
        <?php do_action('rss2_head'); ?>
        <?php while(have_posts()) : the_post(); 
                add_filter('the_excerpt_rss', 'another_read_rss_content');
                add_filter('the_content_feed', 'another_read_rss_content');
        
        ?>
                <item>
                    <title><?php the_title_rss(); ?></title>
                    <link><?php the_permalink_rss(); ?></link>
                    <pubDate><?php echo esc_html(mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false)); ?></pubDate>
                    <dc:creator><?php the_author(); ?></dc:creator>
                    <guid isPermaLink="false"><?php the_guid(); ?></guid>
                    <description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
                    <content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
                    <?php rss_enclosure(); ?>
                    <?php do_action('rss2_item'); ?>
                </item>
        <?php endwhile; 
        
        function another_read_rss_content(){
            $current_post = get_post(get_the_ID(), ARRAY_A);
            ob_start(); // Start output buffering
            ?>
            <div class="ar-activity-block">
                <?php
                $title = $current_post['post_title'];
                $ActivityContent = get_post_meta($current_post['ID'], '_activity_content', true);
            
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
                        <img src="<?php echo esc_url($jacketImage); ?>" alt="<?php echo esc_html($bookName); ?>">
                        <h2><?php echo esc_html($title); ?></h2>
                    </div>
                    <div class="ar-activity-body">
                        <p class="ar-activity-date"><?php echo esc_html($activityDate); ?></p>
                        <p class="ar-activity-keynote"><?php echo esc_html($keynote); ?></p>
                    </div>
                    <div class="ar-read-more">
                        <a class="button button-primary" href="<?php echo esc_url(get_permalink($current_post['ID'])); ?>">Read More</a>
                    </div>
                    <div class="ar-activity-book">
                        <div class="ar-book">
                            <a href="<?php echo esc_url($bookLink); ?>">
                                <?php echo esc_html($bookName); ?>
                            </a>
                        </div>
                        <div class="ar-book-author">
                            <a href="<?php echo esc_url($authorLink); ?>">
                                <?php echo esc_html($authorName); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $str = ob_get_clean(); // End output buffering and get the buffer's content
            return $str;
        }
        
        ?>
</channel>
</rss>