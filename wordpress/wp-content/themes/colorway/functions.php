<?php
ob_start();
include_once get_template_directory() . '/functions/cw-admin-notices-dismissal.php';
include_once get_template_directory() . '/functions/inkthemes-functions.php';
include_once get_template_directory() . '/includes/customizer.php';
include_once get_template_directory() . '/includes/colorway-admin-settings/class-colorway-admin-settings.php';

//include_once get_template_directory() . '/includes/plugin-notification/features/feature-about-page.php';
//get the theme option from options array
function colorway_get_option($name, $default = '') {
    $options = get_option('inkthemes_options');
    if (isset($options[$name]) && $options[$name] != '') {
        return $options[$name];
    } elseif ($default != '') {
        //if (colorway_get_option('colorway_dummy_data') == 'on') {
        return $default;
        // }
    } else {
        return false;
    }
}

// Save all option in single array
function colorway_save_option($option) {
    if (!empty($option)) {
        return update_option('inkthemes_options', $option);
    }
}

//update theme option
function colorway_update_option($name, $value) {
    $options = get_option('inkthemes_options');
    $options[$name] = $value;
    return update_option('inkthemes_options', $options);
}

//delete theme option
function colorway_delete_option($name) {
    $options = get_option('inkthemes_options');
    unset($options[$name]);
    return update_option('inkthemes_options', $options);
}

$inkthemes_backup_data = get_option('inkthemes_backup_data');
if (!$inkthemes_backup_data) {
    $inkthemes_options = get_option('colorway');
    $inkthemes_options = get_option('inkthemes_options');
    if (!empty($inkthemes_options) && empty($inkthemes_options)) {
        foreach ($inkthemes_options as $key => $val) {
            colorway_update_option($key, $val);
        }
        update_option('inkthemes_backup_data', '1');
    } elseif (!empty($inkthemes_options)) {
        foreach ($inkthemes_options as $key => $val) {
            $previous_value = colorway_get_option($key);
            if ($previous_value == '') {
                colorway_update_option($key, $val);
            }
        }
        update_option('inkthemes_backup_data', '1');
    } elseif (empty($inkthemes_options) && empty($inkthemes_options)) {
        update_option('inkthemes_backup_data', '1');
    }
}
/*
  /* Styles Enqueue
 */

if (!defined('COLORWAY_DIR')) {
    define('COLORWAY_DIR', get_template_directory() . '/');
}
if (!defined('COLORWAY_DIR_URI')) {
    define('COLORWAY_DIR_URI', get_template_directory_uri() . '/');
}

function colorway_add_stylesheet() {
    //wp_enqueue_style('load-fa', 'https://use.fontawesome.com/releases/v5.4.2/css/all.css');
    if (!is_admin()) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            wp_enqueue_style('colorway-woocommerce', COLORWAY_DIR_URI . 'assets/css/woocommerce.css');
        }
        //wp_enqueue_style('colorway-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
        //wp_enqueue_style('colorway_smartmenu_mint_minified', get_template_directory_uri() . "/assets/css/sm-mint.min.css", '', '', 'all');
        //wp_enqueue_style('colorway_smartmenu_mint', get_template_directory_uri() . "/assets/css/sm-mint.css", '', '', 'all');
        wp_enqueue_style('colorway_stylesheet_minified', get_template_directory_uri() . "/assets/css/style.min.css", '', '', 'all');
        //wp_enqueue_style('colorway_stylesheet', get_template_directory_uri() . "/assets/css/style.css", '', '', 'all');
        //wp_enqueue_style('colorway_stylesheet_animate', get_template_directory_uri() . "/assets/css/animate.css", '', '', 'all');
    }
}

add_action('init', 'colorway_add_stylesheet');

function my_enqueue() {
    wp_enqueue_script('colorway-admin-settings', get_template_directory_uri() . '/includes/colorway-admin-settings/js/colorway-admin-menu-settings.js', array('jquery', 'wp-util', 'updates'));
    $localize = array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'btnActivating' => __('Processing ', 'colorway') . '&hellip;',
        'colorwaySitesLink' => admin_url('themes.php?page=colorway-sites'),
        'colorwaySitesLinkTitle' => __('Start Building With Elementor »', 'colorway'),
    );
    wp_localize_script('colorway-admin-settings', 'colorway', apply_filters('colorway_theme_js_localize', $localize));
}

add_action('admin_enqueue_scripts', 'my_enqueue');

function af_form_styles() {
    wp_enqueue_style('af-form-style', get_stylesheet_directory_uri() . '/includes/colorway-admin-settings/css/colorway-admin-menu-settings.css', '', '', 'all');
}

add_action('admin_init', 'af_form_styles');

/**
 * Enqueue script for custom customize control.
 */
function colorway_custom_customize_enqueue() {
    wp_enqueue_style('customizer-css', get_template_directory_uri() . '/assets/css/customizer.css');
}

add_action('customize_controls_enqueue_scripts', 'colorway_custom_customize_enqueue');

/* ----------------------------------------------------------------------------------- */
/* jQuery Enqueue */
/* ---------------------------------------------------------------------------------- */

function colorway_wp_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('inkthemes_all_minified_js', get_template_directory_uri() . "/assets/js/jquery.all.min.js", array('jquery'), '', true);
        if (is_page_template('template-home.php')) {
            wp_enqueue_script('slides', get_template_directory_uri() . '/assets/js/jquery.flexslider.js', array('jquery'), '', true);
            wp_enqueue_script('colorway_tipsy', get_template_directory_uri() . '/assets/js/jquery.tipsy.js', array('jquery'), '', true);
            wp_enqueue_script('colorway-slitslider', get_template_directory_uri() . '/assets/js/jquery.slitslider.js', array('jquery'), false, true);
            wp_enqueue_script('colorway-sliderinit', get_template_directory_uri() . '/assets/js/slider-init.js', array('jquery'), false, true);
            wp_enqueue_script('colorway-ba-cond', get_template_directory_uri() . '/assets/js/jquery.ba-cond.js', array('jquery'), false, true);
        }
        if (is_singular() and get_site_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}

add_action('wp_enqueue_scripts', 'colorway_wp_enqueue_scripts');

//function load_custom_wp_admin_style() {
//        wp_enqueue_script('dismiss-notice-101', get_template_directory_uri() . '/assets/js/dismiss-notice.js', array('jquery'), '', true);
//}
//add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );


function colorway_customizer_preview() {
    wp_enqueue_script(
            'inkthemes_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('jquery', 'customize-preview'), false, true
    );
}

add_action(
        'customize_preview_init', 'colorway_customizer_preview'
);

/**
 * Enqueues the javascript for comment replys
 *
 * */
function colorway_enqueue_scripts() {
    if (is_singular() and get_site_option('thread_comments')) {
        wp_print_scripts('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'colorway_enqueue_scripts');
//Front Page Rename
$get_status = colorway_get_option('re_nm');
$get_file_ac = get_template_directory() . '/front-page.php';
$get_file_dl = get_template_directory() . '/front-page-hold.php';
//True Part
if ($get_status === 'off' && file_exists($get_file_ac)) {
    rename("$get_file_ac", "$get_file_dl");
}
//False Part
if ($get_status === 'on' && file_exists($get_file_dl)) {
    rename("$get_file_dl", "$get_file_ac");
}
/**
 * Load plugin notification file
 */
require_once(get_template_directory() . '/functions/plugin-activation.php');
require_once(get_template_directory() . '/functions/inkthemes-plugin-notify.php');
add_action('tgmpa_register', 'colorway_plugins_notify');

//delete_option('colorway_migrate_option');
add_action('init', 'colorway_migrate_option');

function colorway_migrate_option() {
    if (get_option('inkthemes_options') && !get_option('colorway_migrate_option')) {
        $theme_option = array('colorway_logo', 'colorway_favicon', 'colorway_slideimage1', 'colorway_slideimage2', 'inkthemes_fimg1', 'inkthemes_fimg2', 'inkthemes_fimg3', 'inkthemes_fimg4', 'inkthemes_testimonial_img', 'inkthemes_testimonial_img_2', 'inkthemes_testimonial_img_3');
        $wp_upload_dir = wp_upload_dir();
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        foreach ($theme_option as $option) {
            $option_value = colorway_get_option($option);
            if ($option_value && $option_value != '') {
                $filetype = wp_check_filetype(basename($option_value), null);
                $image_name = preg_replace('/\.[^.]+$/', '', basename($option_value));
                $new_image_url = $wp_upload_dir['path'] . '/' . $image_name . '.' . $filetype['ext'];
                colorway_import_file($new_image_url);
            }
        }
        update_option('colorway_migrate_option', true);
    }
}

function colorway_import_file($file, $post_id = 0, $import_date = 'file') {
    set_time_limit(120);

    // Initially, Base it on the -current- time.
    $time = current_time('mysql', 1);
//     Next, If it's post to base the upload off:

    $time = gmdate('Y-m-d H:i:s', filemtime($file));


//     A writable uploads dir will pass this test. Again, there's no point overriding this one.
    if (!( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] )) {
        return new WP_Error('upload_error', $uploads['error']);
    }

    $wp_filetype = wp_check_filetype($file, null);

    extract($wp_filetype);

    if ((!$type || !$ext ) && !current_user_can('unfiltered_upload')) {
        return new WP_Error('wrong_file_type', __('Sorry, this file type is not permitted for security reasons.', 'colorway')); //A WP-core string..
    }

    $file_name = str_replace('\\', '/', $file);

    if (preg_match('|^' . preg_quote(str_replace('\\', '/', $uploads['basedir'])) . '(.*)$|i', $file_name, $mat)) {
        $filename = basename($file);
        $new_file = $file;
        $url = $uploads['baseurl'] . $mat[1];
        $attachment = get_posts(array('post_type' => 'attachment', 'meta_key' => '_wp_attached_file', 'meta_value' => ltrim($mat[1], '/')));
        if (!empty($attachment)) {
            return new WP_Error('file_exists', __('Sorry, That file already exists in the WordPress media library.', 'colorway'));
        }

        //Ok, Its in the uploads folder, But NOT in WordPress's media library.
        if ('file' == $import_date) {
            $time = filemtime($file);
            if (preg_match("|(\d+)/(\d+)|", $mat[1], $datemat)) { //So lets set the date of the import to the date folder its in, IF its in a date folder.
                $hour = $min = $sec = 0;
                $day = 1;
                $year = $datemat[1];
                $month = $datemat[2];

                // If the files datetime is set, and it's in the same region of upload directory, set the minute details to that too, else, override it.
                if ($time && date('Y-m', $time) == "$year-$month") {
                    list($hour, $min, $sec, $day) = explode(';', date('H;i;s;j', $time));
                }
                $time = mktime($hour, $min, $sec, $month, $day, $year);
            }
            $time = gmdate('Y-m-d H:i:s', $time);

            // A new time has been found! Get the new uploads folder:
            // A writable uploads dir will pass this test. Again, there's no point overriding this one.
            if (!( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] ))
                return new WP_Error('upload_error', $uploads['error']);
            $url = $uploads['baseurl'] . $mat[1];
        }
    } else {
        $filename = wp_unique_filename($uploads['path'], basename($file));
        // copy the file to the uploads dir
        $new_file = $uploads['path'] . '/' . $filename;
        if (false === copy($file, $new_file))
            return new WP_Error('upload_error', sprintf(/* translators: %s - uploads path. */__('The selected file could not be copied to %s.', 'colorway'), $uploads['path']));

        // Set correct file permissions
        $stat = stat(dirname($new_file));
        $perms = $stat['mode'] & 0000666;
        chmod($new_file, $perms);
        // Compute the URL
        $url = $uploads['url'] . '/' . $filename;

        if ('file' == $import_date)
            $time = gmdate('Y-m-d H:i:s', filemtime($file));
    }

    //Apply upload filters
    $return = apply_filters('wp_handle_upload', array('file' => $new_file, 'url' => $url, 'type' => $type));
    $new_file = $return['file'];
    $url = $return['url'];
    $type = $return['type'];

    $title = preg_replace('!\.[^.]+$!', '', basename($file));
    $content = '';

    if ($time) {
        $post_date_gmt = $time;
        $post_date = $time;
    } else {
        $post_date = current_time('mysql');
        $post_date_gmt = current_time('mysql', 1);
    }

    // Construct the attachment array
    $attachment = array(
        'post_mime_type' => $type,
        'guid' => $url,
        'post_parent' => $post_id,
        'post_title' => $title,
        'post_name' => $title,
        'post_content' => $content,
        'post_date' => $post_date,
        'post_date_gmt' => $post_date_gmt
    );

    $attachment = apply_filters('afs-import_details', $attachment, $file, $post_id, $import_date);

    //Win32 fix:
    $new_file = str_replace(strtolower(str_replace('\\', '/', $uploads['basedir'])), $uploads['basedir'], $new_file);

    // Save the data

    $id = wp_insert_attachment($attachment, $new_file, $post_id);
    if (!is_wp_error($id)) {
        $data = wp_generate_attachment_metadata($id, $new_file);
        wp_update_attachment_metadata($id, $data);
    }
    //update_post_meta( $id, '_wp_attached_file', $uploads['subdir'] . '/' . $filename );

    return $id;
}

//if (is_admin() && isset($_GET['activated']) && $pagenow == "themes.php")
// wp_redirect('themes.php?page=colorway');

ob_clean();

function colorway_custom_excerpt_length($length) {
    if (colorway_get_option('inkthemes_excerpt_length') != '') {
        return colorway_get_option('inkthemes_excerpt_length');
    } else {
        return 20;
    }
}

add_filter('excerpt_length', 'colorway_custom_excerpt_length', 999);

/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function colorway_excerpt_more($more) {

    if (colorway_get_option('read_more_btn_text') != '') {
        $btn_text = colorway_get_option('read_more_btn_text', 'Read Now');
    } else {
        $btn_text = 'Read Now';
    }

    if (colorway_get_option('read_more_btn_border') != 'off') {
        $btn_border_color = colorway_get_option('read_btn_border_color', '');
        if ($btn_border_color != '') {
            ?>
            <style type="text/css">
                .read-button a.read_more{
                    border: 2px solid <?php echo esc_attr($btn_border_color); ?>;
                }
            </style>
            <?php
        }
    } else {
        $btn_border_color = colorway_get_option('read_btn_border_color', '');
        if ($btn_border_color != '') {
            ?>
            <style type="text/css">
                .read-button a.read_more{
                    border: none;
                }
            </style>
            <?php
        }
    }

    if (colorway_get_option('read_more_btn_border') != 'off') {
        $btn_border_color = colorway_get_option('read_btn_hover_border_color', '');
        if ($btn_border_color != '') {
            ?>
            <style type="text/css">
                .read-button a.read_more:hover{
                    border: 2px solid <?php echo esc_attr($btn_border_color); ?>;
                }
            </style>
            <?php
        }
    } else {
        $btn_border_color = colorway_get_option('read_btn_hover_border_color', '');
        if ($btn_border_color != '') {
            ?>
            <style type="text/css">
                .read-button a.read_more:hover{
                    border: none;
                }
            </style>
            <?php
        }
    }

    return sprintf('<div class="read-button read_more_btn_text"><a class="read_more" href="%1$s">%2$s<span class="arrow_readm"> &#x25BA;</span></a></div>', get_permalink(get_the_ID()), $btn_text
    );
}

add_filter('excerpt_more', 'colorway_excerpt_more');

/**
 * Google fonts Poppins
 */
function colorway_google_fonts_poppins() {
    $query_args = array(
        'family' => 'Poppins:400,600,700',
        'subset' => 'latin,latin-ext'
    );
    wp_enqueue_style('colorway_google_fonts_poppins', add_query_arg($query_args, "//fonts.googleapis.com/css"));
}

add_action('wp_enqueue_scripts', 'colorway_google_fonts_poppins');

add_action('woocommerce_after_shop_loop_item_title', 'add_star_rating', 5);

function add_star_rating() {
    global $woocommerce, $product;
    $average = $product->get_average_rating();
    if ($average == 0) {
        echo '<div class="star-rating"><span style="width:' . ( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">' . $average . '</strong> ' . __('out of 5', 'colorway') . '</span></div>';
    }
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (!file_exists(WP_PLUGIN_DIR . '/colorway-sites/colorway-sites.php') || is_plugin_inactive('colorway-sites/colorway-sites.php')) {

    add_action('admin_notices', 'general_admin_notice');
}

function general_admin_notice() {
    global $pagenow;
    if ($pagenow != '') {
        echo '<div id="colorway-sites-on-active" class="colorway-notice notice is-dismissible " data-repeat-notice-after="">
    <div class="notice-container">
<div class="notice-image">
<img src="' . get_template_directory_uri() . '/assets/images/logo.png" class="custom-logo" alt="Colorway"></div> 
<div class="notice-content">
<h2 class="notice-heading">
Thank you for installing Colorway!
</h2>
<p style="font-size:16px">Did you know Colorway comes with dozens of ready-to-use <b>Colorway Site templates</b>?</p>
<p style="font-size:14px">Clicking the button below will install and activate the Colorway Sites importer plugin.</p>
<div class="colorway-review-notice-container">';

        // Colorway Sites - Installed but Inactive.
        // Colorway Premium Sites - Inactive.
        if (file_exists(WP_PLUGIN_DIR . '/colorway-sites/colorway-sites.php') && is_plugin_inactive('colorway-sites/colorway-sites.php') && is_plugin_inactive('colorway-pro-sites/colorway-pro-sites.php')) {

            $class = 'button cwy-sites-inactive';
            $button_text = __('Get Started with Colorway', 'colorway');
           $data_slug = json_encode(array('colorway-sites', 'elementor'));
            $data_init = json_encode(array('/colorway-sites/colorway-sites.php','/elementor/elementor.php'));

            // Colorway Sites - Not Installed.
            // Colorway Premium Sites - Inactive.
        } elseif (!file_exists(WP_PLUGIN_DIR . '/colorway-sites/colorway-sites.php') && is_plugin_inactive('colorway-pro-sites/colorway-pro-sites.php')) {

            $class = 'button cwy-sites-notinstalled';
            $button_text = __('Get Started with Colorway', 'colorway');
            $data_slug = json_encode(array('colorway-sites', 'elementor'));
            $data_init = json_encode(array('/colorway-sites/colorway-sites.php','/elementor/elementor.php'));

            // Colorway Premium Sites - Active.
        } elseif (is_plugin_active('colorway-pro-sites/colorway-pro-sites.php')) {
            $class = 'active';
            $button_text = __('Start Building With Elementor »', 'colorway');
            $link = admin_url('themes.php?page=colorway-sites');
        } else {
            $class = 'active';
            $button_text = __('Start Building With Elementor »', 'colorway');
            $link = admin_url('themes.php?page=colorway-sites');
        }

        printf(
                '<a class="%1$s" %2$s %3$s %4$s> %5$s </a>', esc_attr($class), isset($link) ? 'href="' . esc_url($link) . '"' : '', isset($data_slug) ? 'data-slug="' . esc_attr($data_slug) . '"' : '', isset($data_init) ? 'data-init="' . esc_attr($data_init) . '"' : '', esc_html($button_text)
        );
        echo '<img src="' . get_template_directory_uri() . '/assets/images/left-orange-arrow.gif"/></div>	
</div>	
        </div>
<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    }
}

add_action('init', 'google_font_api_init');

function google_font_api_init() {
//initializing fonts    
    $font_family = array('ABeeZee', 'Abel', 'Abhaya Libre', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alegreya Sans', 'Alegreya Sans SC', 'Aleo', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Amiko', 'Amiri', 'Amita', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo', 'Archivo Black', 'Archivo Narrow', 'Aref Ruqaa', 'Arima Madurai', 'Arimo', 'Arizonia', 'Armata', 'Arsenal', 'Artifika', 'Arvo', 'Arya', 'Asap', 'Asap Condensed', 'Asar', 'Asset', 'Assistant', 'Astloch', 'Asul', 'Athiti', 'Atma', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'B612', 'B612 Mono', 'Bad Script', 'Bahiana', 'Bahianita', 'Bai Jamjuree', 'Baloo', 'Baloo Bhai', 'Baloo Bhaijaan', 'Baloo Bhaina', 'Baloo Chettan', 'Baloo Da', 'Baloo Paaji', 'Baloo Tamma', 'Baloo Tammudu', 'Baloo Thambi', 'Balthazar', 'Bangers', 'Barlow', 'Barlow Condensed', 'Barlow Semi Condensed', 'Barriecito', 'Barrio', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Bellefair', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'BioRhyme', 'BioRhyme Expanded', 'Biryani', 'Bitter', 'Black And White Picture', 'Black Han Sans', 'Black Ops One', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buda', 'Buenard', 'Bungee', 'Bungee Hairline', 'Bungee Inline', 'Bungee Outline', 'Bungee Shade', 'Butcherman', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Cairo', 'Calligraffitti', 'Cambay', 'Cambo', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Catamaran', 'Caudex', 'Caveat', 'Caveat Brush', 'Cedarville Cursive', 'Ceviche One', 'Chakra Petch', 'Changa', 'Changa One', 'Chango', 'Charm', 'Charmonman', 'Chathura', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Chonburi', 'Cinzel', 'Cinzel Decorative', 'Clicker Script', 'Coda', 'Coda Caption', 'Codystar', 'Coiny', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Cormorant', 'Cormorant Garamond', 'Cormorant Infant', 'Cormorant SC', 'Cormorant Unicase', 'Cormorant Upright', 'Courgette', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cute Font', 'Cutive', 'Cutive Mono', 'DM Sans', 'DM Serif Display', 'DM Serif Text', 'Damion', 'Dancing Script', 'Dangrek', 'David Libre', 'Dawning of a New Day', 'Days One', 'Dekko', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhurjati', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Do Hyeon', 'Dokdo', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'East Sea Dokdo', 'Eater', 'Economica', 'Eczar', 'El Messiri', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Encode Sans', 'Encode Sans Condensed', 'Encode Sans Expanded', 'Encode Sans Semi Condensed', 'Encode Sans Semi Expanded', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Exo 2', 'Expletus Sans', 'Fahkwang', 'Fanwood Text', 'Farsan', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Faustina', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fira Mono', 'Fira Sans', 'Fira Sans Condensed', 'Fira Sans Extra Condensed', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Frank Ruhl Libre', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gaegu', 'Gafata', 'Galada', 'Galdeano', 'Galindo', 'Gamja Flower', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gidugu', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Gothic A1', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Gugi', 'Gurajada', 'Habibi', 'Halant', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Harmattan', 'Headland One', 'Heebo', 'Henny Penny', 'Herr Von Muellerhoff', 'Hi Melody', 'Hind', 'Hind Guntur', 'Hind Madurai', 'Hind Siliguri', 'Hind Vadodara', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IBM Plex Mono', 'IBM Plex Sans', 'IBM Plex Sans Condensed', 'IBM Plex Serif', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Inknut Antiqua', 'Irish Grover', 'Istok Web', 'Italiana', 'Italianno', 'Itim', 'Jacques Francois', 'Jacques Francois Shadow', 'Jaldi', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Jomhuria', 'Josefin Sans', 'Josefin Slab', 'Joti One', 'Jua', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'K2D', 'Kadwa', 'Kalam', 'Kameron', 'Kanit', 'Kantumruy', 'Karla', 'Karma', 'Katibeh', 'Kaushan Script', 'Kavivanar', 'Kavoon', 'Kdam Thmor', 'Keania One', 'Kelly Slab', 'Kenia', 'Khand', 'Khmer', 'Khula', 'Kirang Haerang', 'Kite One', 'Knewave', 'KoHo', 'Kodchasan', 'Kosugi', 'Kosugi Maru', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'Krub', 'Kumar One', 'Kumar One Outline', 'Kurale', 'La Belle Aurore', 'Laila', 'Lakki Reddy', 'Lalezar', 'Lancelot', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemonada', 'Libre Barcode 128', 'Libre Barcode 128 Text', 'Libre Barcode 39', 'Libre Barcode 39 Extended', 'Libre Barcode 39 Extended Text', 'Libre Barcode 39 Text', 'Libre Baskerville', 'Libre Franklin', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Literata', 'Lobster', 'Lobster Two', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'M PLUS 1p', 'M PLUS Rounded 1c', 'Macondo', 'Macondo Swash Caps', 'Mada', 'Magra', 'Maiden Orange', 'Maitree', 'Major Mono Display', 'Mako', 'Mali', 'Mallanna', 'Mandali', 'Manuale', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Markazi Text', 'Marko One', 'Marmelad', 'Martel', 'Martel Sans', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Meera Inimai', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Mina', 'Miniver', 'Miriam Libre', 'Mirza', 'Miss Fajardose', 'Mitr', 'Modak', 'Modern Antiqua', 'Mogra', 'Molengo', 'Molle', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Mukta', 'Mukta Mahee', 'Mukta Malar', 'Mukta Vaani', 'Muli', 'Mystery Quest', 'NTR', 'Nanum Brush Script', 'Nanum Gothic', 'Nanum Gothic Coding', 'Nanum Myeongjo', 'Nanum Pen Script', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Niramit', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Notable', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Sans HK', 'Noto Sans JP', 'Noto Sans KR', 'Noto Sans SC', 'Noto Sans TC', 'Noto Serif', 'Noto Serif JP', 'Noto Serif KR', 'Noto Serif SC', 'Noto Serif TC', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'Nunito Sans', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Open Sans Condensed', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Overpass', 'Overpass Mono', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Padauk', 'Palanquin', 'Palanquin Dark', 'Pangolin', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Pattaya', 'Patua One', 'Pavanam', 'Paytone One', 'Peddana', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Poor Story', 'Poppins', 'Port Lligat Sans', 'Port Lligat Slab', 'Pragati Narrow', 'Prata', 'Preahvihear', 'Press Start 2P', 'Pridi', 'Princess Sofia', 'Prociono', 'Prompt', 'Prosto One', 'Proza Libre', 'Puritan', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Rajdhani', 'Rakkas', 'Raleway', 'Raleway Dots', 'Ramabhadra', 'Ramaraja', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Ranga', 'Rasa', 'Rationale', 'Ravi Prakash', 'Redressed', 'Reem Kufi', 'Reenie Beanie', 'Revalia', 'Rhodium Libre', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Mono', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Rozha One', 'Rubik', 'Rubik Mono One', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sahitya', 'Sail', 'Saira', 'Saira Condensed', 'Saira Extra Condensed', 'Saira Semi Condensed', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita', 'Sarabun', 'Sarala', 'Sarina', 'Sarpanch', 'Satisfy', 'Sawarabi Gothic', 'Sawarabi Mincho', 'Scada', 'Scheherazade', 'Schoolbell', 'Scope One', 'Seaweed Script', 'Secular One', 'Sedgwick Ave', 'Sedgwick Ave Display', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Shrikhand', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slabo 13px', 'Slabo 27px', 'Slackey', 'Smokum', 'Smythe', 'Sniglet', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Song Myung', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Source Serif Pro', 'Space Mono', 'Special Elite', 'Spectral', 'Spectral SC', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Sree Krushnadevaraya', 'Sriracha', 'Srisakdi', 'Staatliches', 'Stalemate', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Stylish', 'Sue Ellen Francisco', 'Suez One', 'Sumana', 'Sunflower', 'Sunshiney', 'Supermercado One', 'Sura', 'Suranna', 'Suravaram', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tajawal', 'Tangerine', 'Taprom', 'Tauri', 'Taviraj', 'Teko', 'Telex', 'Tenali Ramakrishna', 'Tenor Sans', 'Text Me One', 'Thasadith', 'The Girl Next Door', 'Tienne', 'Tillana', 'Timmana', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trirong', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturCook', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vesper Libre', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Vollkorn SC', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Work Sans', 'Yanone Kaffeesatz', 'Yantramanav', 'Yatra One', 'Yellowtail', 'Yeon Sung', 'Yeseva One', 'Yesteryear', 'Yrsa', 'ZCOOL KuaiLe', 'ZCOOL QingKe HuangYou', 'ZCOOL XiaoWei', 'Zeyada', 'Zilla Slab', 'Zilla Slab Highlight');

    update_option('cw_google_fonts', $font_family);
}

function colorway_disable_emoji_feature() {

    //Prevent Emoji from loading on the front-end
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    //Remove from admin area also
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    //Remove from RSS feeds also
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    //Remove from Embeds
    remove_filter('embed_head', 'print_emoji_detection_script');

    //Remove from emails
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Disable from TinyMCE editor. Currently disabled in block editor by default
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');

    /** Finally, disable it from the database also, 
     *  to prevent characters from converting
     *  Earlier, there was a setting under Writings to do this
     *  It is not ideal to get & update it here - but it works for now
     */
    if ((int) get_option('use_smilies') === 1) {
        update_option('use_smilies', 0);
    }
}

function disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        $plugins = array_diff($plugins, array('wpemoji'));
    }
    return $plugins;
}

add_action('init', 'colorway_disable_emoji_feature');

add_filter('script_loader_tag', 'colorway_defer_scripts', 10, 3);

function colorway_defer_scripts($tag, $handle, $src) {

    // The handles of the enqueued scripts we want to defer
    $defer_scripts = array(
        'jquery-migrate',
    );

    if (in_array($handle, $defer_scripts)) {
        return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
    }

    return $tag;
}

function general_admin_notice_sec() {
    if ( ! PAnD::is_admin_notice_active( 'disable-done-notice-forever' ) ) {
		return;
	}
    global $pagenow;
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('colorway-sites/colorway-sites.php')) {
        echo '<div id="colorway-sites-on-active" data-dismissible="disable-done-notice-forever" class="colorway-notice updated notice notice-success is-dismissible">
    <div class="notice-container">
<div class="notice-image">
<img src="' . get_template_directory_uri() . '/assets/images/logo.png" class="custom-logo" alt="Colorway"></div> 
<div class="notice-content">
<h2 class="notice-heading">
Thank you for installing Colorway!
</h2>
<p style="font-size:16px">Did you know Colorway comes with dozens of ready-to-use <b>Colorway Site templates</b>?</p>
<div class="colorway-review-notice-container"><button class="button button-primary youtube-link youtube-video" youtubeid="qKnIWGXa4Mg">How to use Colorway?</button><img src="' . get_template_directory_uri() . '/assets/images/left-orange-arrow.gif"/>
<div class="colorway-social-buttons"><a href="https://www.facebook.com/groups/colorwaytheme/" target="_blank"><span class="video_fb_wrap"><img class="video_fb" src="' . get_template_directory_uri() . '/assets/images/fb.png" alt="facebook" title="facebook"></span><span>Join Colorway Exclusive Community!
</span></a></div></div>

</div>	
        </div>
<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    }
}
add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action('admin_notices', 'general_admin_notice_sec');
