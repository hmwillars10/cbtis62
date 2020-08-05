<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 */
get_header();
$colorway_sidebar = '';
$colorway_center = '';
$a = get_option('page-layout');
switch ($a) {
    case 'content-sidebar':
        $colorway_sidebar = 'right';
        $content = 'left_d_none';
        break;
    case 'sidebar-content':
        $colorway_sidebar = 'left';
        $content = 'right_d_none';
        break;
    case 'content':
        $colorway_center = 'col-md-12 col-sm-12';
        break;
    default:
        $colorway_sidebar = 'right';
}

?>
        <!--Start Content Grid-->
        <div class="row content ">
            <?php if ($colorway_sidebar == 'left') { ?> 
                <div id="left-sidebar" class="col-md-4 col-sm-4 <?php
                if ($content != '') {
                    echo esc_attr($content);
                }
                ?>">
                    <div class="sidebar <?php echo esc_attr($colorway_sidebar); ?>">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            <?php } ?>

            <div id="content-case" class="<?php
            if ($colorway_center != '') {
                echo esc_attr($colorway_center);
            } else {
                echo 'col-md-8';
            }
            ?>">
                     <?php
                     if (have_posts()) :
                         while (have_posts()) :
                             the_post();

                             get_template_part('templates/content/content', 'page');

                         endwhile;
                         colorway_pagination();

                     else:
                         // If no content, include the "No posts found" template.
//                                          get_template_part( 'content', 'none' );
                         ?>    
                <div>
                                <p> <?php get_template_part( 'templates/content/content', 'none' ); ?> </p>
                    </div>
                <?php
                endif;
                ?>
                <div class="comment_section">
                    <!--Start Comment list-->
                    <?php comments_template('', true); ?>
                    <!--End Comment Form-->
                </div>
            </div>

            <?php if ($colorway_sidebar == 'right') { ?>
                <div id="right-sidebar" class="col-md-4 col-sm-12 switch <?php
                if ($content != '') {
                    echo esc_attr($content);
                }
                ?>">
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
