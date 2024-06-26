<?php
/**
 * Het Groene Doel functions and definitions
 */

/* ### HOOK IT UP ########################################################## */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @return void
 */
add_action( 'after_setup_theme', function() {

	// Enqueue scripts and styles to frontend
	add_action( 'wp_enqueue_scripts', 'hgd_add_frontend_styles_and_scripts' );

	// Enqueue scripts and styles to the editor
	add_action( 'enqueue_block_editor_assets', 'hgd_add_editor_styles_and_scripts' );

	// // Add support for block styles.
	// add_theme_support( 'wp-block-styles' );

	// Enqueue editor styles.
	add_editor_style( ['style.css', 'assets/css/hgd-portfolio.css'] );

	// Add class to body for featured image
	add_filter('body_class', function($classes) {

		if (is_singular() && has_post_thumbnail()) {
	    	$classes[] = 'has-post-thumbnail';
	    }
	    else {
	    	$classes[] = 'has-no-post-thumbnail';	    	
	    }
	    return $classes;
	});

	// Add 'button' class to the submit button for contact form 7
	add_filter( 'do_shortcode_tag', function($output, $tag, $atts, $m) {

	    if ($tag === 'contact-form-7') {
	        $output = str_replace(
	            'wpcf7-submit',
	            'wpcf7-submit wp-block-button__link',
	            $output
	        );
	    }

	    return $output;
	}, 10, 4 );
} );


/* ### DEFINE FUNCTIONS ##################################################### */

/**
 * Enqueue styles.
 *
 * @return void
 */
function hgd_add_frontend_styles_and_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'hgd',
		get_template_directory_uri() . '/style.css',
		array(),
		$theme_version
	);

	wp_enqueue_style(
		'hgd-portfolio',
		get_template_directory_uri() . '/assets/css/hgd-portfolio.css',
		array(),
		$theme_version
	);
}


/**
 * Add scripts and styles (editor)
 */
function hgd_add_editor_styles_and_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'hgd-editor-style',
		get_template_directory_uri() . '/assets/css/editor.css',
		array(),
		$theme_version
	);
}


function hgd_get_svg( $name = '' ) {

	$svg = '';

	if ($name) {
		
		$context = null; 

		/**
		 * Skip SSL check on local development due to SSL error
		 * 
		 * @link https://stackoverflow.com/questions/26148701/file-get-contents-ssl-operation-failed-with-code-1-failed-to-enable-crypto#26151993
		 */
		if ( wp_get_environment_type() == 'local' ) {

			$contextOptions = array(
			    "ssl" => array(
			        "verify_peer" => false,
			        "verify_peer_name" => false,
			    )
			);

			$context = stream_context_create( $contextOptions );
		}

		$svg = file_get_contents( get_stylesheet_directory() . "/assets/svg/{$name}.svg", false, $context );
		$svg = ($svg) ? $svg : '';
	}

	return $svg;
}
