<?php
/**
 * Partial template for content in page.php
 *
 * @package understrap
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <header class="entry-header">

        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

    </header><!-- .entry-header -->

    <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>

    <div class="entry-content">

        <?php the_content(); ?>

        <h2>The Widgets</h2>
        <p>Please feel free to copy and paste the code snippets into your websites.</p>
        <div class="">
        <?php
        $string = file_get_contents('https://promo.cranleigh.org/awesome-book-awards/widgets/config.json');
        $widgets_urls = json_decode($string);
echo "<ol>";
        foreach ($widgets_urls as $widget_name => $url): ?>
        <li>
            <h3><?php echo titleize($widget_name); ?></h3>
            <div class="row">
                <div class="col-md-4">
            <img class="img-thumbnail img-responsive" style="margin:auto;display:inline-block" src="https://promo.cranleigh.org/awesome-book-awards/widgets/images/<?php echo $widget_name.".jpg";?>" />
                </div>
                <div class="col-md-8">
<div class="form-group">
<label style="margin-bottom:1px;font-size:12px;">Embed Code:</label>
            <textarea class="select-on-click form-control" rows="5" style="width:100%;font-size:10px"><iframe id="awesome-books-widget" border=0 width=100% frameborder=0 src="https://promo.cranleigh.org/awesome-book-awards/widgets/index.php?widget=<?php echo $widget_name; ?>"></iframe><script type="text/javascript">function awesomebookawardswidgetresizer(){jQuery(document).ready(function(){var e=jQuery("#awesome-books-widget"),t=e.width();500<t&&(t=500);var a=t/2+5;e.attr("style","max-width:100%;width:"+t+"px;height:"+a+"px;")})}if("undefined"==typeof jQuery){var headTag=document.getElementsByTagName("head")[0],jqTag=document.createElement("script");jqTag.type="text/javascript",jqTag.src="https://code.jquery.com/jquery-3.3.1.min.js",jqTag.onload=awesomebookawardswidgetresizer,headTag.appendChild(jqTag)}else awesomebookawardswidgetresizer();</script></textarea>
</div>
                </div>
            </div>
        </li>
        <hr />
        <?php endforeach;
        echo "</ol>";?>
        </div>
        <script type="text/javascript">
            jQuery(".select-on-click").focus(function() {
                var $this = jQuery(this);
                $this.select();

                // Work around Chrome's little problem
                $this.mouseup(function() {
                    // Prevent further mouseup intervention
                    $this.unbind("mouseup");
                    return false;
                });
            });

        </script>
        <?php
        wp_link_pages([
            'before' => '<div class="page-links">'.__('Pages:', 'understrap'),
            'after' => '</div>',
        ]);
        ?>

    </div><!-- .entry-content -->

    <footer class="entry-footer">

        <?php edit_post_link(__('Edit', 'understrap'), '<span class="edit-link">', '</span>'); ?>

    </footer><!-- .entry-footer -->

</article><!-- #post-## -->
<?php
function titleize($input) {
    $input = str_replace("-", " ", $input);
    return $input;
}
