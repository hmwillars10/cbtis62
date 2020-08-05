<?php
ob_start();
$css_style      = '';
$css_style_prev = '';
function colorway_setup() {
	add_theme_support(
		'custom-background',
		array(
			'default-color' => '#cecece',
			// 'default-image' => get_template_directory_uri() . '/assets/images/body-bg.png'
		)
	);
	add_theme_support( 'title-tag' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'post-thumbnails' );
	/**
	 * Woocommerce
	 */
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_editor_style();
	set_post_thumbnail_size( 262, 162 );
	add_image_size( 'post_thumbnail', 262, 162, true );
	// add_image_size('colorway_custom_size', 260, 350, true);

	add_theme_support( 'automatic-feed-links' );
	register_nav_menu( 'custom_menu', 'Main Menu' );

	load_theme_textdomain( 'colorway', get_template_directory() . '/languages' );

	add_theme_support(
		'custom-header',
		array(
			// 'default-image' => get_template_directory_uri() . '/assets/images/bg-img.jpg',
			'random-default'         => false,
			'width'                  => '',
			'height'                 => '',
			'flex-height'            => true,
			'flex-width'             => true,
			'default-text-color'     => '',
			'header-text'            => true,
			'uploads'                => true,
			'wp-head-callback'       => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		)
	);
}

add_action( 'after_setup_theme', 'colorway_setup' );


/*
 ----------------------------------------------------------------------------------- */
/*
 Custom Menus Function
  /*----------------------------------------------------------------------------------- */

// Add CLASS attributes to the first <ul> occurence in wp_page_menu
function colorway_add_menuclass( $ulclass ) {
	return preg_replace( '/<ul>/', '<ul class="ddsmoothmenu">', $ulclass, 1 );
}

add_filter( 'wp_page_menu', 'colorway_add_menuclass' );
add_filter( 'use_default_gallery_style', '__return_false' );

function colorway_nav() {
	if ( function_exists( 'wp_nav_menu' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'custom_menu',
				'items_wrap'     => colorway_menu_button(),
				'container_id'   => 'menu',
				'menu_class'     => 'sm sm-mint',
				'fallback_cb'    => 'colorway_nav_fallback',
			)
		);
	} else {
		colorway_nav_fallback();
	}
}

function colorway_nav_fallback() {
	?>
	<div id="menu">
		<ul class="sm sm-mint">
			<?php
			wp_list_pages( 'title_li=&show_home=1&sort_column=menu_order' );
			?>
		</ul>
	</div>
	<?php
}

function sticky_header() {
	wp_register_script( 'inkthemes_stickyheader_js', get_template_directory_uri() . '/assets/js/stickyheader.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'inkthemes_stickyheader_js' );
	wp_enqueue_style( 'inkthemes_stickyheader_css', get_template_directory_uri() . '/assets/css/stickyheader.css' );
}

if ( colorway_get_option( 'colorway_sticky_header', false ) == true ) {
	add_action( 'wp_enqueue_scripts', 'sticky_header' );
}

function colorway_menu_button() {
	// default value of 'items_wrap' is <ul id="%1$s" class="%2$s">%3$s</ul>'
	// open the <ul>, set 'menu_class' and 'menu_id' values
	$wrap = '<ul id="%1$s" class="%2$s">';
	// get nav items as configured in /wp-admin/
	$wrap .= '%3$s';
	// the static link
	if ( colorway_get_option( 'colorway_button_html' ) != '' && colorway_get_option( 'btn_on_off' ) != 'off' ) {
		$wrap .= "<li class='colorway_button_html'> " . html_entity_decode( colorway_get_option( 'colorway_button_html' ) ) . '</li>';
	}
	// close the <ul>
	$wrap .= '</ul>';
	// return the result
	return $wrap;
}

function colorway_new_nav_menu_items( $items ) {
	if ( is_home() ) {
		$homelink = '<li class="current_page_item">' . '<a href="' . home_url( '/' ) . '">' . __( 'Home', 'colorway' ) . '</a></li>';
	} else {
		$homelink = '<li>' . '<a href="' . home_url( '/' ) . '">' . 'Home' . '</a></li>';
	}
	if ( colorway_get_option( 'colorway_button_html' ) != '' && colorway_get_option( 'btn_on_off' ) != 'off' ) {
		$items .= "<li class='colorway_button_html'> " . html_entity_decode( colorway_get_option( 'colorway_button_html' ) ) . '</li>';
	}
	$items = $homelink . $items;
	return $items;
}

add_filter( 'wp_list_pages', 'colorway_new_nav_menu_items' );

// function add_last_nav_item($items) {
// echo $items .= colorway_menu_button();
// }
// add_filter('wp_nav_menu_menu_items','add_last_nav_item');



if ( ! function_exists( 'colorway_comment' ) ) :

	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own colorway_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	function colorway_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case '':
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<div id="comment-<?php comment_ID(); ?>">
						<div class="comment-author vcard"> <?php echo get_avatar( $comment, 40 ); ?> <?php printf( '%s <span class="says">says:</span>' . sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?> </div>
						<!-- .comment-author .vcard -->
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em> <?php echo ( 'Your comment is awaiting moderation.' ); ?> </em> <br />
						<?php endif; ?>
						<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<?php
								/* translators: 1: date, 2: time */
								printf( __( '%1$s at %2$s', 'colorway' ) . get_comment_date(), get_comment_time() );
								?>
							</a>
							<?php
							edit_comment_link( '(Edit)', ' ' );
							?>
						</div>
						<!-- .comment-meta .commentmetadata -->
						<div class="comment-body">
							<?php comment_text(); ?>
						</div>
						<div class="reply">
							<?php
							comment_reply_link(
								array_merge(
									$args,
									array(
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
									)
								)
							);
							?>
						</div>
						<!-- .reply -->
					</div>
					<!-- #comment-##  -->
					<?php
				break;
			case 'pingback':
			case 'trackback':
				?>
				<li class="post pingback">
					<p> <?php echo ( 'Pingback:' ); ?>
					<?php comment_author_link(); ?>
					<?php edit_comment_link( '(Edit)', ' ' ); ?>
					</p>
					<?php
				break;
			endswitch;
	}

	endif;

	/**
	 * Set the content width based on the theme's design and stylesheet.
	 *
	 * Used to set the width of images and content. Should be equal to the width the theme
	 * is designed for, generally via the style.css stylesheet.
	 */
if ( ! isset( $content_width ) ) {
	$content_width = 590;
}

	/**
	 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
	 *
	 * To override colorway_widgets_init() in a child theme, remove the action hook and add your own
	 * function tied to the init hook.
	 *
	 * @uses register_sidebar
	 */
function colorway_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar(
		array(
			'name'          => __( 'Primary Widget Area', 'colorway' ),
			'id'            => 'primary-widget-area',
			'description'   => __( 'The primary widget area', 'colorway' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar(
		array(
			'name'          => __( 'Secondary Widget Area', 'colorway' ),
			'id'            => 'secondary-widget-area',
			'description'   => __( 'The secondary widget area', 'colorway' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
	// Area 3, located in the footer. Empty by default.
	register_sidebar(
		array(
			'name'          => __( 'First Footer Widget Area', 'colorway' ),
			'id'            => 'footer-widget-area1',
			'description'   => __( 'The first footer widget area', 'colorway' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);
	// Area 4, located in the footer. Empty by default.
	register_sidebar(
		array(
			'name'          => __( 'Second Footer Widget Area', 'colorway' ),
			'id'            => 'footer-widget-area2',
			'description'   => __( 'The second footer widget area', 'colorway' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);
	// Area 5, located in the footer. Empty by default.
	register_sidebar(
		array(
			'name'          => __( 'Third Footer Widget Area', 'colorway' ),
			'id'            => 'footer-widget-area3',
			'description'   => __( 'The third footer widget area', 'colorway' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);
	// Area 6, located in the footer. Empty by default.
	register_sidebar(
		array(
			'name'          => __( 'Fourth Footer Widget Area', 'colorway' ),
			'id'            => 'footer-widget-area4',
			'description'   => __( 'The fourth footer widget area', 'colorway' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);
	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar(
		array(
			'name'          => __( 'Home Page Left Feature Widget Area', 'colorway' ),
			'id'            => 'home-page-right-feature-widget-area',
			'description'   => __( 'The Home Page Left Feature widget area', 'colorway' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		)
	);
	// default header
	register_default_headers(
		array(
			'default-image' => array(
				'url'           => get_template_directory_uri() . '/assets/images/3.jpg',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/3.jpg',
				'description'   => __( 'Default Header Image', 'colorway' ),
			),
		)
	);
}

	/** Register sidebars by running colorway_widgets_init() on the widgets_init hook. */
	add_action( 'widgets_init', 'colorway_widgets_init' );

	add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 2 );

	/**
	 * Display navigation to next/previous pages when applicable
	 */
function colorway_content_nav( $nav_id ) {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) :
		?>
			<nav id="<?php echo esc_attr( $nav_id ); ?>">
				<h3 class="assistive-text"><?php echo esc_html__( 'Post navigation', 'colorway' ); ?></h3>
				<div class="nav-next">
				<?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'colorway' ) ); ?>
				</div>
				<div class="nav-previous">
				<?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'colorway' ) ); ?>
				</div>
			</nav>
			<!-- #nav-above -->
			<?php
		endif;
}

	/**
	 * Pagination
	 */
function colorway_pagination( $pages = '', $range = 2 ) {
	$showitems = ( $range * 2 ) + 1;
	global $paged;
	if ( empty( $paged ) ) {
		$paged = 1;
	}
	if ( $pages == '' ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}
	if ( 1 != $pages ) {
		echo "<ul class='paging'>";
		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
			echo "<li><a href='" . esc_url( get_pagenum_link( 1 ) ) . "'>&laquo;</a></li>";
		}
		if ( $paged > 1 && $showitems < $pages ) {
			echo "<li><a href='" . esc_url( get_pagenum_link( $paged - 1 ) ) . "'>&lsaquo;</a></li>";
		}
		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				echo ( $paged == $i ) ? "<li><a href='" . esc_url( get_pagenum_link( $i ) ) . "' class='current' >" . esc_html( $i ) . '</a></li>' : "<li><a href='" . esc_url( get_pagenum_link( $i ) ) . "' class='inactive' >" . esc_html( $i ) . '</a></li>';
			}
		}
		if ( $paged < $pages && $showitems < $pages ) {
			echo "<li><a href='" . esc_url( get_pagenum_link( $paged + 1 ) ) . "'>&rsaquo;</a></li>";
		}
		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
			echo "<li><a href='" . esc_url( get_pagenum_link( $pages ) ) . "'>&raquo;</a></li>";
		}
		echo "</ul>\n";
	}
}

	/*
	 ----------------------------------------------------------------------------------- */
	/*
	 Show analytics code in footer */
	/* ----------------------------------------------------------------------------------- */

function colorway_analytics() {
	$output = colorway_get_option( 'colorway_analytics' );
	if ( $output <> '' ) {
		echo html_entity_decode( $output );
	}
}

	add_action( 'wp_head', 'colorway_analytics' );

// Green color style
function colorway_green_css() {
	?>
	<?php
	// wp_enqueue_style('colorway-green-css', get_template_directory_uri() . '/assets/css/green.css', '', '', 'all');
}

	add_action( 'wp_head', 'colorway_green_css' );

// Trm post title
function the_titlesmall( $before = '', $after = '', $echo = true, $length = false ) {
	$title = get_the_title();
	if ( $length && is_numeric( $length ) ) {
		$title = substr( $title, 0, $length );
	}
	if ( strlen( $title ) > 0 ) {
		$title = apply_filters( 'the_titlesmall', $before . $title . $after, $before, $after );
		if ( $echo ) {
			echo esc_attr( $title );
		} else {
			return esc_attr( $title );
		}
	}
}

	ob_clean();

	/*
	 * * Enqueue Google Fonts
	 */

function colorway_gfonts_scripts() {
	wp_enqueue_style( 'colorway-google-fonts', colorway_google_fonts() );
	add_action( 'wp_head', 'colorway_typography' );
}

	add_action( 'wp_enqueue_scripts', 'colorway_gfonts_scripts' );

	$fontwt = array( 'Default', 'normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900' );
	$fontsz = array( '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40' );


	/* String of Google fonts */
function colorway_google_fonts() {

	// global $fonts;
	global $fontwt;
	global $fontsz;
	global $cwfonts;
	$cwfonts  = get_option( 'cw_google_fonts' );
	$cwindex  = colorway_get_option( 'typography_logo_family', '684' );
	$cwindex0 = colorway_get_option( 'typography_menu_family', '684' );
	$cwindex1 = colorway_get_option( 'typography_nav_family', '462' );
	$cwindex2 = colorway_get_option( 'typography_heading1', '684' );
	$cwindex3 = colorway_get_option( 'typography_heading2', '684' );
	$cwindex4 = colorway_get_option( 'typography_heading3', '684' );
	$cwindex5 = colorway_get_option( 'typography_heading4', '684' );
	$cwindex6 = colorway_get_option( 'typography_heading5', '684' );
	$cwindex7 = colorway_get_option( 'typography_heading6', '684' );
	$cwindex8 = colorway_get_option( 'typography_para' );
	$cwindex9 = colorway_get_option( 'typography_read_more_btn', '684' );

	$fonts_collection = add_query_arg(
		array(
			'family'      => implode( '|', array_unique( array( $cwfonts[ $cwindex ], $cwfonts[ $cwindex0 ], $cwfonts[ $cwindex1 ], $cwfonts[ $cwindex2 ], $cwfonts[ $cwindex3 ], $cwfonts[ $cwindex4 ], $cwfonts[ $cwindex5 ], $cwfonts[ $cwindex6 ], $cwfonts[ $cwindex7 ], $cwfonts[ $cwindex8 ], $cwfonts[ $cwindex9 ] ) ) ),
			'font-weight' => implode( '|', $fontwt ),
			'font-size'   => implode( '|', $fontsz ),
			'subset'      => 'latin',
		),
		'//fonts.googleapis.com/css'
	);
	return $fonts_collection;
}

	/* typography style CSS code function */

function colorway_typography() {
	// global $fonts;
	global $cwfonts;
	global $fontwt;
	global $fontsz;

	$cwfonts = get_option( 'cw_google_fonts' );
	$index   = colorway_get_option( 'typography_logo_family', '684' );
	$index0  = colorway_get_option( 'typography_menu_family', '684' );
	$index1  = colorway_get_option( 'typography_nav_family', '462' );
	$index2  = colorway_get_option( 'typography_heading1', '684' );
	$index3  = colorway_get_option( 'typography_heading2', '684' );
	$index4  = colorway_get_option( 'typography_heading3', '684' );
	$index5  = colorway_get_option( 'typography_heading4', '684' );
	$index6  = colorway_get_option( 'typography_heading5', '684' );
	$index7  = colorway_get_option( 'typography_heading6', '684' );
	$index8  = colorway_get_option( 'typography_para' );
	$index9  = colorway_get_option( 'typography_read_more_btn', '684' );

	$fontweight      = colorway_get_option( 'typography_title_fontweight', '10' );
	$fontweight0     = colorway_get_option( 'typography_tagline_fontweight', '8' );
	$fontweight_menu = colorway_get_option( 'typography_fontweight_navmenu', '8' );
	$fontweight1     = colorway_get_option( 'typography_fontweight_heading1', '10' );
	$fontweight2     = colorway_get_option( 'typography_fontweight_heading2', '10' );
	$fontweight3     = colorway_get_option( 'typography_fontweight_heading3', '10' );
	$fontweight4     = colorway_get_option( 'typography_fontweight_heading4', '8' );
	$fontweight5     = colorway_get_option( 'typography_fontweight_heading5', '10' );
	$fontweight6     = colorway_get_option( 'typography_fontweight_heading6', '8' );
	$fontweight7     = colorway_get_option( 'typography_fontweight_para' );
	$fontweight8     = colorway_get_option( 'typography_fontweight_read_more_btn', '10' );

	$font_menu_size = colorway_get_option( 'typography_menu_fontsize', '8' );
	$font_btn_size  = colorway_get_option( 'typography_btn_fontsize', '9' );
	$font_para      = colorway_get_option( 'typography_fontsize_para' );
	$fontsize1      = colorway_get_option( 'typography_fontsize_heading1', '27' );
	$fontsize2      = colorway_get_option( 'typography_fontsize_heading2', '24' );
	$fontsize3      = colorway_get_option( 'typography_fontsize_heading3', '21' );
	$fontsize4      = colorway_get_option( 'typography_fontsize_heading4', '16' );
	$fontsize5      = colorway_get_option( 'typography_fontsize_heading5', '16' );
	$fontsize6      = colorway_get_option( 'typography_fontsize_heading6', '14' );
	$fontsize7      = colorway_get_option( 'footer_fontsize_para' );
	$fontsize8      = colorway_get_option( 'footer_fontsize_link', '8' );
	$fontsize9      = colorway_get_option( 'typography_fontsize_read_more_btn', '8' );

	global $css_style_prev;

	if ( $index != '' ) {
		$css_style_prev .= 'h1.site-title, p.site-description{ font-family: ' . esc_attr( $cwfonts[ $index ] ) . ', Sans-Serif}';
	}
	if ( $index0 != '' ) {
		$css_style_prev .= '#menu .sm.sm-mint li a, ul.sm.sm-mint button{ font-family: ' . esc_attr( $cwfonts[ $index0 ] ) . ', Sans-Serif}';
	}
	if ( $index1 != '' ) {
		$css_style_prev .= ' body{ font-family: ' . esc_attr( $cwfonts[ $index1 ] ) . ', Sans-Serif}';
	}
	if ( $index2 != '' ) {
		$css_style_prev .= 'h1{ font-family: ' . esc_attr( $cwfonts[ $index2 ] ) . ', Sans-Serif}';
	}
	if ( $index3 != '' ) {
		$css_style_prev .= 'h2{ font-family: ' . esc_attr( $cwfonts[ $index3 ] ) . ', Sans-Serif }';
	}
	if ( $index4 != '' ) {
		$css_style_prev .= ' h3{ font-family: ' . esc_attr( $cwfonts[ $index4 ] ) . ', Sans-Serif }';
	}
	if ( $index5 != '' ) {
		$css_style_prev .= ' h4{ font-family: ' . esc_attr( $cwfonts[ $index5 ] ) . ', Sans-Serif;}';
	}
	if ( $index6 != '' ) {
		$css_style_prev .= 'h5{ font-family: ' . esc_attr( $cwfonts[ $index6 ] ) . ', Sans-Serif;}';
	}
	if ( $index7 != '' ) {
		$css_style_prev .= "h6{ font-family: '" . esc_attr( $cwfonts[ $index7 ] ) . "', Sans-Serif;}";
	}
	if ( $index8 != '' ) {
		$css_style_prev .= 'p{ font-family: ' . esc_attr( $cwfonts[ $index8 ] ) . ', Sans-Serif;}';
	}
	if ( $index9 != '' ) {
		$css_style_prev .= ' .read-button a.read_more{ font-family: ' . esc_attr( $cwfonts[ $index9 ] ) . ', Sans-Serif;}';
	}

	// Font Weight Typography
	if ( $fontweight != '' ) {
		$css_style_prev .= ' h1.site-title{ font-weight: ' . esc_attr( $fontwt[ $fontweight ] ) . '}';
	}
	if ( $fontweight0 != '' ) {
		$css_style_prev .= ' p.site-description{ font-weight: ' . esc_attr( $fontwt[ $fontweight0 ] ) . '}';
	}
	if ( $fontweight_menu != '' ) {
		$css_style_prev .= ' #menu .sm.sm-mint li a{ font-weight: ' . esc_attr( $fontwt[ $fontweight_menu ] ) . '}';
	}
	if ( $fontweight1 != '' ) {
		$css_style_prev .= ' h1{ font-weight:' . esc_attr( $fontwt[ $fontweight1 ] ) . '}';
	}
	if ( $fontweight2 != '' ) {
		$css_style_prev .= ' h2{ font-weight:' . esc_attr( $fontwt[ $fontweight2 ] ) . '; }';
	}
	if ( $fontweight3 != '' ) {
		$css_style_prev .= ' h3{ font-weight:' . esc_attr( $fontwt[ $fontweight3 ] ) . '; }';
	}
	if ( $fontweight4 != '' ) {
		$css_style_prev .= ' h4{ font-weight:' . esc_attr( $fontwt[ $fontweight4 ] ) . '; }';
	}
	if ( $fontweight5 != '' ) {
		$css_style_prev .= ' h5{ font-weight:' . esc_attr( $fontwt[ $fontweight5 ] ) . '; }';
	}
	if ( $fontweight6 != '' ) {
		$css_style_prev .= ' h6{ font-weight:' . esc_attr( $fontwt[ $fontweight6 ] ) . '; }';
	}
	if ( $fontweight7 != '' ) {
		$css_style_prev .= ' p{ font-weight:' . esc_attr( $fontwt[ $fontweight7 ] ) . '; }';
	}
	if ( $fontweight8 != '' ) {
		$css_style_prev .= ' .read-button a.read_more{ font-weight:' . esc_attr( $fontwt[ $fontweight8 ] ) . '; }';
	}
	if ( $font_menu_size != '' ) {
		$css_style_prev .= ' #menu .sm.sm-mint li a{ font-size:' . esc_attr( $fontsz[ $font_menu_size ] ) . 'px; }';
	}
	if ( $font_btn_size != '' ) {
		$css_style_prev .= ' li.colorway_button_html button{ font-size: ' . esc_attr( $fontsz[ $font_btn_size ] ) . 'px; }';
	}
	if ( $font_para != '' ) {
		$css_style_prev .= ' p{ font-size:' . esc_attr( $fontsz[ $font_para ] ) . 'px; }';
	}
	if ( $fontsize1 != '' ) {
		$css_style_prev .= ' h1{ font-size:' . esc_attr( $fontsz[ $fontsize1 ] ) . 'px; }';
	}
	if ( $fontsize2 != '' ) {
		$css_style_prev .= ' h2{ font-size:' . esc_attr( $fontsz[ $fontsize2 ] ) . 'px; }';
	}
	if ( $fontsize3 != '' ) {
		$css_style_prev .= ' h3{ font-size:' . esc_attr( $fontsz[ $fontsize3 ] ) . 'px; }';
	}
	if ( $fontsize4 != '' ) {
		$css_style_prev .= ' h4{ font-size:' . esc_attr( $fontsz[ $fontsize4 ] ) . 'px; }';
	}
	if ( $fontsize5 != '' ) {
		$css_style_prev .= ' h5{ font-size:' . esc_attr( $fontsz[ $fontsize5 ] ) . 'px; }';
	}
	if ( $fontsize6 != '' ) {
		$css_style_prev .= ' .footer-container h6, .footer .widget_inner h4{ font-size:' . esc_attr( $fontsz[ $fontsize6 ] ) . 'px; }';
	}
	if ( $fontsize7 != '' ) {
		$css_style_prev .= ' .footer p, .footer a{ font-size:' . esc_attr( $fontsz[ $fontsize7 ] ) . 'px; }';
	}
	if ( $fontsize8 != '' ) {
		$css_style_prev .= ' .footer-navi a{ font-size:' . esc_attr( $fontsz[ $fontsize8 ] ) . 'px; }';
	}
	if ( $fontsize9 != '' ) {
		$css_style_prev .= ' .read-button a.read_more{ font-size: ' . esc_attr( $fontsz[ $fontsize9 ] ) . 'px; }';
	}
	echo "<style type='text/css'> $css_style_prev </style>";

}

function menu_link_color_styles() {
	$header_bg                    = colorway_get_option( 'header_bg_color' );
	$site_title                   = colorway_get_option( 'site_title_color', '#3868bb' );
	$site_tagline                 = colorway_get_option( 'site_tagline_color' );
	$menu_color                   = colorway_get_option( 'menu_link_color' );
	$menu_hover_color             = colorway_get_option( 'menu_hover_color' );
	$menu_bg_color                = colorway_get_option( 'menu_background_color' );
	$menu_bg_hover_color          = colorway_get_option( 'menu_background_hover_color' );
	$menu_sticky_text_color       = colorway_get_option( 'sticky_menu_text_color' );
	$menu_sticky_bg_hover_color   = colorway_get_option( 'sticky_menu_background_hover_color' );
	$menu_sticky_hover_link_color = colorway_get_option( 'sticky_menu_hover_link_color' );

	$button_color       = colorway_get_option( 'button_link_color' );
	$button_hover_color = colorway_get_option( 'button_link_hover_color' );
	$button_bg_color    = colorway_get_option( 'button_bg_color' );
	$button_bg_hover    = colorway_get_option( 'button_bg_hover_color' );

	$theme_link       = colorway_get_option( 'theme_link_color' );
	$theme_link_hover = colorway_get_option( 'theme_link_hover_color' );
	$theme_h1         = colorway_get_option( 'theme_h1_color' );
	$theme_h2         = colorway_get_option( 'theme_h2_color' );
	$theme_h3         = colorway_get_option( 'theme_h3_color' );
	$theme_h4         = colorway_get_option( 'theme_h4_color' );
	$theme_h5         = colorway_get_option( 'theme_h5_color' );
	$theme_h6         = colorway_get_option( 'theme_h6_color' );
	$theme_para       = colorway_get_option( 'theme_para_color' );

	$footer_link         = colorway_get_option( 'footer_link_color', '#949494' );
	$footer_link_hover   = colorway_get_option( 'footer_link_hover_color' );
	$footer_text         = colorway_get_option( 'footer_text_color', '#949494' );
	$footer_head_col     = colorway_get_option( 'footer_header_color', '#cccccc' );
	$footer_col          = colorway_get_option( 'footer_col_bg_color', '#343434' );
	$footer_bottom       = colorway_get_option( 'bottom_footer_bg_color', '#292929' );
	$btn_bg_color        = colorway_get_option( 'read_btn_bg_color', '#eedec3' );
	$btn_txt_color       = colorway_get_option( 'read_btn_txt_color', '#354053' );
	$btn_hover_bg_color  = colorway_get_option( 'read_btn_hover_bg_color', '#ede3d5' );
	$btn_hover_txt_color = colorway_get_option( 'read_btn_hover_txt_color', '#0e88b5' );
	$btn_rad             = get_option( 'btn_rad' );
	$read_more_btn_rad   = get_option( 'read_more_btn_rad' );
	// $read_more_btn_border = get_option('read_btn_border_on_off');
	$btn_h_pad         = get_option( 'btn_h_pad' );
	$btn_v_pad         = get_option( 'btn_v_pad' );
	$header_v_pad      = get_option( 'header_v_pad', 45 );
	$header_h_pad      = get_option( 'header_h_pad', 85 );
	$content_h_pad     = get_option( 'content_h_pad', 100 );
	$content_v_pad     = get_option( 'content_v_pad' );
	$bottom_footer_css = get_option( 'container-layout' );
	$para_line_height  = get_option( 'para_line_height', 1.8 );

	global $css_style;
	if ( $btn_rad != '' ) {
		$css_style .= 'ul.sm.sm-mint li.colorway_button_html button, .mean-container .mean-nav ul li button{ border-radius:' . esc_attr( $btn_rad ) . 'px;} ul.sm.sm-mint li.colorway_button_html span button { border-radius: 50%;}';
	}

	if ( $read_more_btn_rad != '' ) {
		$css_style .= 'read-button a.read_more{ border-radius:' . esc_attr( $read_more_btn_rad ) . 'px;}';
	}

	if ( $btn_h_pad != '' ) {
		$css_style .= 'ul.sm.sm-mint li.colorway_button_html button, .mean-container .mean-nav ul li button{ padding:' . esc_attr( $btn_v_pad ) . 'px ' . esc_attr( $btn_h_pad ) . 'px;} ul.sm.sm-mint li.colorway_button_html span button { padding: inherit;}';
	}

	if ( $header_bg != '#000' ) {
		$css_style .= '.container-h {background-color:' . esc_attr( $header_bg ) . '}';
	}
	if ( $site_title != '#000' ) {
		$css_style .= 'h1.site-title {color:' . esc_attr( $site_title ) . '}';
	}
	if ( $site_tagline != '#000' ) {
		$css_style .= 'p.site-description { color:' . esc_attr( $site_tagline ) . '}';
	}

	if ( $header_v_pad != '' ) {
		$css_style .= '.header{ padding:' . esc_attr( $header_v_pad ) . 'px 0;}';
	}
	if ( $header_h_pad != '' ) {
		$css_style .= '@media only screen and ( min-width: 968px ){ .container{ width:' . esc_attr( $header_h_pad ) . '%;}}';
	}
	if ( $content_h_pad != '' ) {
		$css_style .= '.cyw-container{ width:' . esc_attr( $content_h_pad ) . '%;}';
	}
	if ( $content_v_pad != '' ) {
		$css_style .= '.cw-content.container-fluid{ padding-top:' . esc_attr( $content_v_pad ) . 'px;}';
	}
	if ( $menu_color != '' ) {
		$css_style .= '.sm.sm-mint li.page_item  a, .sm.sm-mint li.page_item  li a:link, .sm.sm-mint li.menu-item a, .sm.sm-mint li.menu-item li a:link{color:' . esc_attr( $menu_color ) . '}';
	}
	if ( $menu_hover_color != '' ) {
		$css_style .= '#menu .sm.sm-mint li.menu-item a:hover, #menu .sm.sm-mint li.page_item a:hover, #menu .sm.sm-mint li.page_item li a:hover, #menu .sm.sm-mint li.menu-item li a:link:hover{color:' . esc_attr( $menu_hover_color ) . '}';
	}
	if ( $menu_bg_color != '#2B4908' ) {
		$css_style .= '#menu li.current_page_item a, #menu li.current_page_parent a, #menu .sm.sm-mint li li {background-color:' . esc_attr( $menu_bg_color ) . '}';
	}
	if ( $menu_bg_hover_color != '#2B4908' ) {
		$css_style .= '#menu li.current_page_item a:hover, #menu .sm.sm-mint li.menu-item a:hover, #menu .sm.sm-mintli.page_item a:hover {background-color:' . esc_attr( $menu_bg_hover_color ) . '}';
	}

	if ( $menu_sticky_text_color != '#354053' ) {
		$css_style .= '.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.menu-item a,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.page_item a,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.page_item li a,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.menu-item li a:link,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.current_page_item a{color:' . esc_attr( $menu_sticky_text_color ) . '}';
	}
	if ( $menu_sticky_bg_hover_color != 'transparent' ) {
		$css_style .= '.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.menu-item a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.page_item a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.page_item li a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.menu-item li a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.current_page_item a:hover{background-color:' . esc_attr( $menu_sticky_bg_hover_color ) . '}';
	}
	if ( $menu_sticky_hover_link_color != '#949090' ) {
		$css_style .= '.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.menu-item a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.page_item a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.page_item li a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.menu-item li a:hover,.container-h.container-fluid.is-sticky #menu .sm.sm-mint li.current_page_item a:hover{color:' . esc_attr( $menu_sticky_hover_link_color ) . '}';
	}
	if ( $button_color != '#2B4908' ) {
		$css_style .= 'ul.sm.sm-mint button, ul.sm.sm-mint button a, .mean-container .mean-nav ul li button a{color:' . esc_attr( $button_color ) . '}';
	}
	if ( $button_hover_color != '#2B4908' ) {
		$css_style .= 'ul.sm.sm-mint button:hover, ul.sm.sm-mint button a:hover, .mean-container .mean-nav ul li button a:hover {color:' . esc_attr( $button_hover_color ) . '}';
	}

	if ( $button_bg_color != '#2B4908' ) {
		$css_style .= 'ul.sm.sm-mint button,.mean-container .mean-nav ul li button {background-color:' . esc_attr( $button_bg_color ) . '}';
	}
	if ( $button_bg_hover != '#2B4908' ) {
		$css_style .= '#menu .sm.sm-mint li button:hover, .mean-container .mean-nav ul li button:hover  {background-color:' . esc_attr( $button_bg_hover ) . '}';
	}
	if ( $theme_link != '#2B4908' ) {
		$css_style .= '.cw-content a {
                    color:' . esc_attr( $theme_link ) . '}';
	}
	if ( $theme_link_hover != '#2B4908' ) {
		$css_style .= '.cw-content a:hover {color:' . esc_attr( $theme_link_hover ) . '}';
	}
	if ( $theme_h1 != '#2B4908' ) {
		$css_style .= '.cw-content h1{color:' . esc_attr( $theme_h1 ) . '}';
	}
	if ( $theme_h2 != '#2B4908' ) {
		$css_style .= '.cw-content h2 {color:' . esc_attr( $theme_h2 ) . '}';
	}
	if ( $theme_h3 != '#2B4908' ) {
		$css_style .= '.cw-content h3 {color:' . esc_attr( $theme_h3 ) . '}';
	}
	if ( $theme_h4 != '#2B4908' ) {
		$css_style .= '.cw-content h4 {color:' . esc_attr( $theme_h4 ) . '}';
	}
	if ( $theme_h5 != '#2B4908' ) {
		$css_style .= '.cw-content h5 {color:' . esc_attr( $theme_h5 ) . '}';
	}
	if ( $theme_h6 != '#2B4908' ) {
		$css_style .= '.cw-content h6 {color:' . esc_attr( $theme_h6 ) . '}';
	}
	if ( $theme_para != '' ) {
		$css_style .= '.cw-content p {color:' . esc_attr( $theme_para ) . '}';
	}
	if ( $footer_link != '#fff' ) {
		$css_style .= '.footer-navi a {color:' . esc_attr( $footer_link ) . '}';
	}

	if ( $footer_link != '#fff' ) {
		$css_style .= '.footer a {color:' . esc_attr( $footer_link ) . '}';
	}

	if ( $footer_link_hover != '#fff' ) {
		$css_style .= '.footer-navi a:hover {color:' . esc_attr( $footer_link_hover ) . '}';
	}

	if ( $footer_link_hover != '#fff' ) {
		$css_style .= '.footer a:hover {color:' . esc_attr( $footer_link_hover ) . '}';
	}

	if ( $footer_text != '' ) {
		$css_style .= '.footer h1, .footer h2, .footer h3, .footer h4, .footer h5, .footer p {color:' . esc_attr( $footer_text ) . '}';
	}

	if ( $footer_head_col != '' ) {
		$css_style .= '.footer h6,.footer .widget_inner h6 {color:' . esc_attr( $footer_head_col ) . '}';
	}

	if ( $footer_col != '#4F7327' ) {
		$css_style .= '.footer-container {background-color:' . esc_attr( $footer_col ) . '}';
	}

	if ( $footer_bottom != '#2b4908' ) {
		$css_style .= '.footer-navi {background-color:' . esc_attr( $footer_bottom ) . '}';
	}

	if ( $btn_bg_color != '#eedec3' ) {
		$css_style .= '.read-button a.read_more {background-color:' . esc_attr( $btn_bg_color ) . '}';
	}
	if ( $btn_txt_color != '#354053' ) {
		$css_style .= '.read-button a.read_more {color:' . esc_attr( $btn_txt_color ) . '}';
	}
	if ( $btn_hover_bg_color != '#eedec3' ) {
		$css_style .= '.read-button a.read_more:hover {background-color:' . esc_attr( $btn_hover_bg_color ) . '}';
	}

	if ( $btn_hover_txt_color != '#1e73be' ) {
		$css_style .= '.read-button a.read_more:hover {color:' . esc_attr( $btn_hover_txt_color ) . '}';
	}
	if ( $para_line_height != '' ) {
		$css_style .= ' p{ line-height:' . esc_attr( $para_line_height ) . 'em;}';
	}
	echo "<style type='text/css'> $css_style </style>";

}

	add_action( 'wp_head', 'menu_link_color_styles' );

