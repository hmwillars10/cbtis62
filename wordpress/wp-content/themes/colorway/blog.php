<?php
/*
  Template Name: Blog Page
 */
get_header();
$colorway_sidebar = '';
$colorway_center = '';
$a = get_option('blog-layout');
switch ($a) {
    case 'content-sidebar':
        $colorway_sidebar = 'right';
        break;
    case 'sidebar-content':
        $colorway_sidebar = 'left';
        break;
    case 'content':
        $colorway_center = 'col-md-12 col-sm-12';
        break;
    default:
        $colorway_sidebar = 'right';
}
?>
    <div class="row content">
        <?php if ($colorway_sidebar == 'left') { ?>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_attr($colorway_sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>

        <div class="<?php
        if ($colorway_center != '') {
            echo esc_attr($colorway_center);
        } else {
            echo 'col-md-8';
        }
        ?>">

            <div class="content-wrap">
                
                <div class="blog" id="blogmain">
                    <?php
                    $limit = get_option('posts_per_page');
                    $cw_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    query_posts('showposts=' . $limit . '&paged=' . $cw_paged);
                    $wp_query->is_archive = true;
                    $wp_query->is_home = false;

                    if (have_posts()) : while (have_posts()) : the_post();

                            get_template_part('templates/content/content', 'loop');

                        endwhile;
                    else:
                        ?>
                        <div>
                            <p> <?php get_template_part('templates/content/content', 'none'); ?> </p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php colorway_pagination(); ?>
            </div>
        </div>

        <?php if ($colorway_sidebar == 'right') { ?>
            <div class="col-md-4 col-sm-12">
                <div class="sidebar <?php echo esc_attr($colorway_sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="clear"></div>
    <!--End Content Grid-->
</div>
</div>

<!--End Container Div-->
<?php get_footer(); ?>
