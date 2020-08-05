<?php
/**
 * The template for displaying woocommerce pages.
 *
 * This is the template that displays woocommerce pages by default.
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
        <div id="left-sidebar" class="col-md-4 col-sm-12 <?php
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
        woocommerce_content();
        ?>
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
