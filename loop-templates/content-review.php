<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package understrap
 */
$book = get_post_meta($post->ID, 'related_books', true)[0];
?>

<article <?php post_class('review'); ?> id="post-<?php the_ID(); ?>">
    <div class="row">
        <div class="col-sm-4">
            <a class="post-featured-image" href="<?php echo get_permalink($book); ?>">
                <?php
                echo get_the_post_thumbnail($book, 'author-mugshot'); ?>
            </a>
        </div>
        <div class="col-sm-8">

            <header class="entry-header">

                <?php the_title( sprintf( '<h3 class="entry-title sr-only"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
                    '</a></h3>' ); ?>

            </header><!-- .entry-header -->


            <div class="review-content">
                <blockquote><?php
                the_content();
                    ?><footer><?php echo get_post_meta($post->ID, 'review_author', true); ?></footer></blockquote>

                <div class="clear clearfix">&nbsp;</div>
            </div><!-- .entry-content -->

            <footer class="entry-footer">

                <?php understrap_entry_footer(); ?>

            </footer><!-- .entry-footer -->

        </div>
    </div>


</article><!-- #post-## -->
