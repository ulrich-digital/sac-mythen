<?php
/*
*  Author: Matthias Ulrich
*  URL: https://ulrich.digital
*/

setlocale(LC_TIME, "de_DE.utf8");

/* =============================================================== *\ 
 	 
	 Customized Core
	 
\* =============================================================== */ 

/* =============================================================== *\ 
	 Super-Admins 
	 - kann abgefragt werden mit: if(is_my_super_admin() == true)
	 - z.B. um gewisse Seiten zu verstecken 
\* =============================================================== */ 
$my_super_admins = array("1","2");
function is_my_super_admin(){
	global $my_super_admins;	
	$is_super_admin = false;
	foreach($my_super_admins as $my_super_admin):
		if(get_current_user_id()==$my_super_admin):
			$is_super_admin = true;
		else:
			$is_usper_admin = false;
		endif;
	endforeach;
	return($is_super_admin);
}

/* =============================================================== *\ 
   JavaScripts + Styles
\* =============================================================== */ 

/* Backend */
add_action( 'admin_enqueue_scripts', 'add_backend_javascripts' );
function add_backend_javascripts() {
	wp_enqueue_media();

	$url_h0 = get_stylesheet_directory_uri() . '/js/ulrich_admin.js?v='. time() . '';
   	//wp_enqueue_script( 'jquery' );
   	//wp_enqueue_script( 'my-admin-js' );
	wp_register_script( 'my_admin_js_ajax', $url_h0, array('jquery', 'acf', 'acf-input'), '1.0.0', true );
	wp_enqueue_script('my_admin_js_ajax');     
	global $wp_query;
	//wp_localize_script(handler, variable in js,)
	wp_localize_script( 'my_admin_js_ajax', 'ajaxcall_admin', array(
		'my_edit' => json_encode(get_current_screen()),
    	)
	);
}


add_action('admin_enqueue_scripts', 'add_backend_styles');  
function add_backend_styles() {	
	wp_enqueue_style('admin-styles', get_template_directory_uri().'/style-admin.css');
	wp_register_style('font_awesome', get_stylesheet_directory_uri() . '/css/all.css', array(), '1.0', 'all');
	wp_enqueue_style('font_awesome');
}


/* Frontend */
add_action( 'wp_enqueue_scripts', 'add_frontend_javascripts' );
function add_frontend_javascripts() {
	$url_h0 = get_stylesheet_directory_uri().'/js/jquery-ui.min.js';
	$url_h1 = get_stylesheet_directory_uri().'/js/isotope.pkgd.min.js?v='. time() . '';
	$url_h2 = get_stylesheet_directory_uri().'/js/tinymce.min.js?v='. time() . '';
	$slick_url = get_stylesheet_directory_uri().'/js/slick.min.js?v='. time() . '';
	$url_h3 = get_stylesheet_directory_uri().'/js/ulrich.js?v='. time() . '';

	//wp_enqueue_script( 'eigener_Name', pfad_zum_js, abhaengigkeit (zb jquery zuerst laden), versionsnummer, bool (true=erst im footer laden) );
	wp_enqueue_script( 'handler_name_0', $url_h0, array('jquery'), null, false );
	wp_enqueue_script( 'handler_name_1', $url_h1, array('jquery'), null, true );
	wp_enqueue_script( 'handler_name_2', $url_h2, array('jquery'), null, true );
	wp_enqueue_script( 'slick', $slick_url, array('jquery'), null, true );
	wp_enqueue_script( 'ajax-call', $url_h3, array('jquery'), null, true );

	// Confetti nur bei betr. Seite laden
	if ( is_page( 'form-tourenbericht-thank-you' ) ) {
		$url_h4 = get_stylesheet_directory_uri().'/js/confetti.min.js';
		wp_enqueue_script( 'confetti', $url_h4, array('jquery'), null, true );
    } 
}


add_action('wp_enqueue_scripts', 'add_frontend_styles');
function add_frontend_styles() {
	//wp_register_style( $handle, $src, $deps, $ver, $media );
    wp_register_style('main',  get_stylesheet_directory_uri() . "/style.css?" . date("h:i:s"), array(), '1.0', 'all');
    wp_enqueue_style('main');
	
	wp_register_style('font_awesome', get_stylesheet_directory_uri() . '/webfonts/all.css', array(), '1.0', 'all');
    wp_enqueue_style('font_awesome');
	
	wp_register_style('slick',  get_stylesheet_directory_uri() . '/js/slick.css', array(), '1.0', 'all');
	wp_enqueue_style('slick');
	/*
	wp_register_style('slick-theme',  get_stylesheet_directory_uri() . '/js/slick-theme.css', array(), '1.0', 'all');
	wp_enqueue_style('slick-theme');
	
	wp_register_style('slick-lightbox', get_stylesheet_directory_uri() . '/js/slick-lightbox.css', array(), '1.0', 'all');
	wp_enqueue_style('slick-lightbox');	
	/*
	wp_register_style('adobe_fonts', 'https://use.typekit.net/owr0crc.css', array(), '1.0', 'all');
	wp_enqueue_style('adobe_fonts');
	

    */
}

/* =============================================================== *\ 
 	 Clean-Up <header>
\* =============================================================== */ 
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action( 'wp_head', 'rest_output_link_wp_head');
remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
/// emojis weg
add_action('init', 'remove_emoji');
function remove_emoji(){
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'remove_tinymce_emoji');
}

function remove_tinymce_emoji($plugins){
	if (!is_array($plugins)){
		return array();
	}
	return array_diff($plugins, array('wpemoji'));
}

/* =============================================================== *\ 
 	 Admin
	 - Remove Admin-Menu-Elements
	 - Remove Admin-Menu-Bar-Elements
	 - Custom Admin-Menu Order
\* =============================================================== */ 
add_action('admin_menu', 'remove_menus');
function remove_menus () {
	global $menu;
	$restricted = array(__('Beiträge'), __('Kommentare'));
	$restricted = array(__('Kommentare'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
			unset($menu[key($menu)]);
		}
	}
}

add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );
function mytheme_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('new-content');
	/*
	my-account – link to your account (avatars disabled)
	my-account-with-avatar – link to your account (avatars enabled)
	my-blogs – the "My Sites" menu if the user has more than one site
	get-shortlink – provides a Shortlink to that page
	edit – link to the Edit/Write-Post page
	new-content – link to the "Add New" dropdown list
	comments – link to the "Comments" dropdown
	appearance – link to the "Appearance" dropdown
	updates – the "Updates" dropdown
	*/
}

/* Benutzerdefinierte Reihenfolge des Backend-Menu */
//add_filter( 'custom_menu_order', '__return_true' );
//add_filter( 'menu_order', 'wpse_custom_menu_order', 10, 1 );
function wpse_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
 	return array(
     'index.php', // Dashboard
     'link-manager.php', // Links
     'edit.php?post_type=page', // Pages
     'users.php', // Users
     'upload.php', // Media
     'separator1', // First separator
     'themes.php', // Appearance
     'plugins.php', // Plugins
     'tools.php', // Tools
     'options-general.php', // Settings
     'separator2', // Second separator
     'separator-last', // Last separator
 	);
}

/* =============================================================== *\ 
 	 Add Options-Page 
\* =============================================================== */ 
//include('theme_options.php');

/* =============================================================== *\ 
 	 Load Comment-Reply-Script 
\* =============================================================== */ 
add_action( 'comment_form_before', 'enqueue_comment_reply_script' );
function enqueue_comment_reply_script() {
	if ( get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/* =============================================================== *\ 
 	 Remove "Load-More"-Button in Media-Library 
\* =============================================================== */ 
add_filter( 'media_library_infinite_scrolling', '__return_true' );

/* =============================================================== *\ 
 	 Add Title-Separator 
\* =============================================================== */ 
add_filter( 'document_title_separator', 'document_title_separator' );
function document_title_separator( $sep ) {
    $sep = '|';
    return $sep;
}

/* =============================================================== *\ 
 	 Add ... to title, if necessary 
\* =============================================================== */ 
add_filter( 'the_title', 'mytitle' );
function mytitle( $title ) {
    if ( $title == '' ) {
        return '...';
    } else {
        return $title;
    }
}

/* =============================================================== *\ 
 	 Remove automatically <p>-Tags 
\* =============================================================== */ 
$priority = has_filter( 'the_content', 'wpautop' );
if ( false !== $priority ) {
	remove_filter( 'the_content', 'wpautop', $priority );
}

/* =============================================================== *\ 
 	 Add Title-Tag to <head> 
	 Add Post-thumbnails 
	 Remove unnecessary "type"-attribute from javascript files
	 Add RSS feed links to HTML <head>	 
	 Register Nav-Menus
\* =============================================================== */ 
//https://developer.wordpress.org/reference/functions/add_theme_support/
add_action( 'after_setup_theme', 'ulrich_digital_setup' );
function ulrich_digital_setup(){
    add_theme_support( 'title-tag' );
    //add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'script', 'style' ] );
	add_theme_support( 'automatic-feed-links' );
    register_nav_menus(
	   array(
		   'hauptmenue' => 		'Hauptmenü',
		   'main-menu' => 		'Main Menu',
		   'footer_menu_1' => 	'Footer Menu 1',
		   'footer_menu_2' => 	'Footer Menu 2',
		   'footer_menu_3' => 	'Footer Menu 3',
		   'footer_menu_impressum_datenschutz' => 'Footer Menu – Impressum und Datenschutz',
	    )
    );
}

/* =============================================================== *\ 
 	 Add Custom Image-Sizes 
	 Add Custom Image-Sizes to Backend-Choose
	 Enable SVG
\* =============================================================== */ 

add_action('after_setup_theme', 'eigene_bildgroessen', 11);
function eigene_bildgroessen() {
	add_image_size('card_image', 800, 530, true);
	add_image_size('slick_slider', 2000, 1500, true);
	add_image_size('card_main_slick_slider', 800, 800, true);
}

/* Add Image-Sizes to Backend-Choose */
add_filter('image_size_names_choose', 'bildgroessen_auswaehlen');
function bildgroessen_auswaehlen($sizes) {
		$custom_sizes = array('card_image' => 'Vorschaubild Card');
	return array_merge($sizes, $custom_sizes);
}

/* SVG erlauben */
add_filter('upload_mimes', 'add_svg_to_upload_mimes');
function add_svg_to_upload_mimes($upload_mimes){
	$upload_mimes['svg'] = 'image/svg+xml';
	$upload_mimes['svgz'] = 'image/svg+xml';
	return $upload_mimes;
}

/* =============================================================== *\ 
 	 Allow Contributors to uplaod media 
\* =============================================================== */ 
if ( current_user_can('contributor') && !current_user_can('upload_files') ){
    add_action('admin_init', 'allow_contributor_uploads');
}
function allow_contributor_uploads() {
    $contributor = get_role('contributor');
    $contributor->add_cap('upload_files');
}

/* =============================================================== *\ 
 	 Enable Widgets 
\* =============================================================== */ 
add_action( 'widgets_init', 'ulrichdigital_blank_widgets_init' );
function ulrichdigital_blank_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar Widget Area', 'ulrich_digital_blank' ),
		'id' => 'primary-widget-area',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

/* =============================================================== *\ 
 	 Custom Admin-Logo 
\* =============================================================== */ 
add_action( 'login_enqueue_scripts', 'my_login_logo' );
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/BG_logo_rgb.svg);
            padding-bottom: 60px;
            width:320px;
            background-repeat: no-repeat;
 			background-size: 250px auto;
        }
    </style>
<?php }


/* =============================================================== *\ 
	Admin
 	- Add Custom Footer 
\* =============================================================== */ 
add_filter( 'admin_footer_text', 'backend_entwickelt_mit_herz',100 );
function backend_entwickelt_mit_herz( $text ) {
	return ('<span style="color:black;">Entwickelt mit </span><span style="color: red;font-size:20px;vertical-align:-3px">&hearts;</span><span style="color:black;"</span><span> von <a href="https://ulrich.digital" target="_blank">ulrich.digital</a></span>' );
}

/* =============================================================== *\ 
   Custom-Post-Types 
\* =============================================================== */ 
add_action('init','ab_register_post_types');
//unregister_post_type("aktuell");


function ab_register_post_types(){
	// Touren
	$touren_supports = array('title', 'editor', 'custom-fields', 'revisions');
	$touren_labels = array(
		'menu_name' => 'Touren',
	    'name' => 'Touren',
	    'singular_name' => 'Touren',
	    'add_new' => 'Tour hinzuf&uuml;gen',
	    'add_new_item' => 'Neue Tour hinzuf&uuml;gen',
	    'edit_item' => 'Tour bearbeiten',
	    'new_item' => 'Neue Tour',
	    'view_item' => 'Tour anzeigen',
	    'search_items' => 'Tour suchen',
	    'not_found' => 'Keine Tour gefunden',
	    'not_found_in_trash' => 'Keine Tour im Papierkorb',
		);
	$touren_args = array(
	    'supports' => $touren_supports,
	    'labels' => $touren_labels,
	    'description' => 'Post-Type f&uuml;r Touren',
	    'public' => true,
	    'show_in_nav_menus' => true,
	    'show_in_menu' => true,
		'show_in_rest' => true,
	    'has_archive' => true,
	    'query_var' => true,
		'menu_icon' => 'dashicons-hammer',
	    'taxonomies' => array('topics', 'category'),
	    'rewrite' => array(
	        'slug' => 'touren',
	        'with_front' => true
	   		),
		);
	register_post_type('touren', $touren_args);
	
	// Tourenbericht
	$tourenberichte_supports = array('title', 'editor', 'custom-fields', 'revisions');
	$tourenberichte_labels = array(
		'menu_name' => 'Tourenberichte',
		'name' => 'Tourenberichte',
		'singular_name' => 'Tourenbericht',
		'add_new' => 'Bericht hinzuf&uuml;gen',
		'add_new_item' => 'Neuer Bericht hinzuf&uuml;gen',
		'edit_item' => 'Bericht bearbeiten',
		'new_item' => 'Neuer Bericht',
		'view_item' => 'Bericht anzeigen',
		'search_items' => 'Bericht suchen',
		'not_found' => 'Kein Bericht gefunden',
		'not_found_in_trash' => 'Kein Bericht im Papierkorb',
		);
	$tourenberichte_args = array(
		'supports' => $tourenberichte_supports,
		'labels' => $tourenberichte_labels,
		'description' => 'Post-Type f&uuml;r Tourenberichte',
		'public' => true,
		'show_in_nav_menus' => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'has_archive' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-hammer',
		'taxonomies' => array('topics', 'category'),
		'rewrite' => array(
			'slug' => 'tourenbericht',
			'with_front' => true
			),
		);
	register_post_type('tourenbericht', $tourenberichte_args);

	$aktuelles_supports = array('title','editor', 'custom-fields', 'revisions');
	$aktuelles_labels = array(
		'menu_name' => 'Aktuelles',
		'name' => 'Aktuelles',
		'singular_name' => 'Aktuell',
		'add_new' => 'Beitrag hinzuf&uuml;gen',
		'add_new_item' => 'Neuer Beitrag hinzuf&uuml;gen',
		'edit_item' => 'Beitrag bearbeiten',
		'new_item' => 'Neuer Beitrag',
		'view_item' => 'Beitrag anzeigen',
		'search_items' => 'Beitrag suchen',
		'not_found' => 'Kein Beitrag gefunden',
		'not_found_in_trash' => 'Kein Beitrag im Papierkorb',
		);
	$aktuelles_args = array(
		'supports' => $aktuelles_supports,
		'labels' => $aktuelles_labels,
		'description' => 'Post-Type f&uuml;r Aktualitäten',
		'public' => true,
		'show_in_nav_menus' => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'has_archive' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-hammer',
		'taxonomies' => array('topics', 'category'),
		'rewrite' => array(
			'slug' => 'aktuell',
			'with_front' => true,
			'pages' => true,

			),
		);
	register_post_type('aktuell', $aktuelles_args);
	
}

/* =============================================================== *\ 

 	 Archiv für «Aktuelles aus der Sektion» 
	 - wird über eine Page mit dem Seiten-Template page-aktuell-archive.php ausgegeben
	 - die Templates in den Optionen unsichtbar machen > 
	 https://wptheming.com/2014/02/hiding-page-templates-in-the-admin/
\* =============================================================== */ 
  
/* =============================================================== *\ 
	Add Category Meta Box to Pages 
	Add Tag Meta Box to Pages 	
\* =============================================================== */ 
function add_categories_to_pages() {  
	register_taxonomy_for_object_type('category', 'page');  
	//register_taxonomy_for_object_type('post_tag', 'page'); 
}
add_action( 'init', 'add_categories_to_pages' );

// OPTIMIZE: löschen
function tcp_add_post_types_in_category_and_tag_template($query) {
  if (is_category() && $query->is_main_query()){
	
    	$query->set('post_type','page');
		return $query;  
  	}
}
add_filter('pre_get_posts', 'tcp_add_post_types_in_category_and_tag_template');



/* =============================================================== *\ 
 	 Meta-Keys befüllen 
	 - Tourdatum
	 - Autor
	 - Bereiche
\* =============================================================== */ 
//add_action('pre_post_update', 'before_data_is_saved_function', 100, 2);
add_action('save_post', 'save_in_meta', 10, 2);

function save_in_meta($post_id) {
	if('touren'==get_post_type($post_id)):	
		$meta = get_post_meta($post_id);
		
		$my_tourdatum = get_current_tourdatum_for_meta($post_id);
		$my_tourenleiter_name = get_current_tourenleiter_name($post_id);
		$my_bereiche = get_current_bereiche($post_id);
		
		if(isset($meta['current_tour_date'])):
			delete_post_meta($post_id, 'current_tour_date');
		endif;
		
		if(isset($meta['tourenleiter_name'])):
			delete_post_meta($post_id, 'tourenleiter_name');
		endif;
		
		if(isset($meta['bereiche'])):
			delete_post_meta($post_id, 'bereiche');
		endif;
		
		update_post_meta( $post_id, 'current_tour_date',  $my_tourdatum);
		update_post_meta( $post_id, 'tourenleiter_name',  $my_tourenleiter_name);
		update_post_meta( $post_id, 'bereiche',  $my_bereiche);
	endif;
} 


/* =============================================================== *\ 

 	 Customized Plugins

\* =============================================================== */ 
  
/* ACF - Options-Page */ 
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}


/* =============================================================== *\ 

 	 ACF-Konstanten 

\* =============================================================== */ 
// Button-Beschriftungen holen
add_action( 'init', 'acf_konstanten' );
function acf_konstanten(){
	if(get_field("aktuelle_touren", "options")):
		define("LABEL_AKTUELLE_TOUREN", get_field("aktuelle_touren", "options"));
	else:
		define("LABEL_AKTUELLE_TOUREN", "Aktuelle Touren");	
	endif;

	if(get_field("jahresprogramme", "options")):
		define("LABEL_JAHRESPROGRAMME", get_field("jahresprogramme", "options"));
	else:
		define("LABEL_JAHRESPROGRAMME", "Jahresprogramme");	
	endif;

	if(get_field("touren_archiv", "options")):
		define("LABEL_TOUREN_ARCHIV", get_field("touren_archiv", "options"));
	else:
		define("LABEL_TOUREN_ARCHIV", "Touren-Archiv");	
	endif;

	if(get_field("tourenberichte", "options")):
		define("LABEL_TOUREN_BERICHTE", get_field("tourenberichte", "options"));
	else:
		define("LABEL_TOUREN_BERICHTE", "Tourenberichte");	
	endif;
	
	if(get_field("vorherige_tour", "options")):
		define("LABEL_PREVIOUS_TOUR", get_field("vorherige_tour", "options"));
	else:
		define("LABEL_PREVIOUS_TOUR", "vorherige Tour");	
	endif;
	
	if(get_field("nachste_tour", "options")):
		define("LABEL_NEXT_TOUR", get_field("nachste_tour", "options"));
	else:
		define("LABEL_NEXT_TOUR", "nächste Tour");	
	endif;
	
	if(get_field("titel_neuigkeiten_aus_der_sektion", "options")):
		define("TITLE_NEUIGKEITEN_ARCHIV", get_field("titel_neuigkeiten_aus_der_sektion", "options"));
	else:
		define("TITLE_NEUIGKEITEN_ARCHIV", "Neuigkeiten aus der Sektion");	
	endif;
	
	if(get_field("weitere_neuigkeiten_aus_der_sektion", "options")):
		define("LABEL_NEUIGKEITEN_ARCHIV", get_field("weitere_neuigkeiten_aus_der_sektion", "options"));
	else:
		define("LABEL_NEUIGKEITEN_ARCHIV", "Weitere Neuigkeiten aus der Sektion");	
	endif;

	if(get_field("titel_tourenberichte_archiv", "options")):
		define("TITLE_TOURENBERICHTE_ARCHIV", get_field("titel_tourenberichte_archiv", "options"));
	else:
		define("TITLE_TOURENBERICHTE_ARCHIV", "Touren-Berichte");	
	endif;
	
	if(get_field("titel_aktuelle_touren_archiv", "options")):
		define("TITLE_AKTUELLE_TOUREN_ARCHIV", get_field("titel_aktuelle_touren_archiv", "options"));
	else:
		define("TITLE_AKTUELLE_TOUREN_ARCHIV", "Unsere nächsten Touren");	
	endif;
	
	if(get_field("titel_vergangene_touren_archiv", "options")):
		define("TITLE_VERGANGENE_TOUREN_ARCHIV", get_field("titel_vergangene_touren_archiv", "options"));
	else:
		define("TITLE_VERGANGENE_TOUREN_ARCHIV", "Touren-Archiv");	
	endif;
}

/* =============================================================== *\ 
 	 ACF-Blocks 
\* =============================================================== */ 
add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'touren-kurzinfo',
            'title'             => 'Touren-Kurzinfo',
            'description'       => 'Info-Daten der Tour',
            'render_template'   => 'blocks/acf-touren-kurzinfo/block.php',
            'category'          => 'formatting',
            'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mountain" class="svg-inline--fa fa-mountain fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M503.2 393.8L280.1 44.25c-10.42-16.33-37.73-16.33-48.15 0L8.807 393.8c-11.11 17.41-11.75 39.42-1.666 57.45C17.07 468.1 35.92 480 56.31 480h399.4c20.39 0 39.24-11.03 49.18-28.77C514.9 433.2 514.3 411.2 503.2 393.8zM256 111.8L327.8 224H256L208 288L177.2 235.3L256 111.8z"></path></svg>',
            'keywords'          => array( 'testimonial', 'quote' ),
			'mode'			=> 'edit',
			'supports'		=> [
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
			]
        ));
		
		acf_register_block_type(array(
            'name'              => 'tourdatum',
            'title'             => 'Datum der Tour',
            'description'       => 'Fügt ein Datum ein.',
            'render_template'   => 'blocks/acf-tourdatum/block.php',
            'category'          => 'formatting',
            'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="calendar-days" class="svg-inline--fa fa-calendar-days fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M0 464C0 490.5 21.5 512 48 512h352c26.5 0 48-21.5 48-48V192H0V464zM320 272C320 263.2 327.2 256 336 256h32C376.8 256 384 263.2 384 272v32c0 8.836-7.162 16-16 16h-32C327.2 320 320 312.8 320 304V272zM320 400c0-8.836 7.164-16 16-16h32c8.838 0 16 7.164 16 16v32c0 8.836-7.162 16-16 16h-32c-8.836 0-16-7.164-16-16V400zM192 272C192 263.2 199.2 256 208 256h32C248.8 256 256 263.2 256 272v32c0 8.836-7.162 16-16 16h-32C199.2 320 192 312.8 192 304V272zM192 400C192 391.2 199.2 384 208 384h32c8.838 0 16 7.164 16 16v32c0 8.836-7.162 16-16 16h-32C199.2 448 192 440.8 192 432V400zM64 272C64 263.2 71.16 256 80 256h32C120.8 256 128 263.2 128 272v32C128 312.8 120.8 320 112 320h-32C71.16 320 64 312.8 64 304V272zM64 400C64 391.2 71.16 384 80 384h32C120.8 384 128 391.2 128 400v32C128 440.8 120.8 448 112 448h-32C71.16 448 64 440.8 64 432V400zM400 64H352V31.1C352 14.4 337.6 0 320 0C302.4 0 288 14.4 288 31.1V64H160V31.1C160 14.4 145.6 0 128 0S96 14.4 96 31.1V64H48C21.49 64 0 85.49 0 112V160h448V112C448 85.49 426.5 64 400 64z"></path></svg>',
            'keywords'          => array( 'datum', '' ),
			'mode'			=> 'edit',
			'supports'		=> [
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
			]
        ));
		acf_register_block_type(array(
			'name'              => 'touren-details',
			'title'             => 'Details der Tour',
			'description'       => 'Details der Tour erfassen',
			'render_template'   => 'blocks/acf-touren-details/block.php',
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( 'datum', '' ),
			'mode' => 'edit',
			'supports' =>[
				'mode' => false,
				]
		));
		
		acf_register_block_type(array(
			'name'              => 'verknuepfung-zur-tour',
			'title'             => 'Verknüpfung zur Tour',
			'description'       => 'Angaben der verknüpften Tour',
			'render_template'   => 'blocks/acf-verknuepfung-zur-tour/block.php',
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( '' ),
			'mode' => 'edit',
			'supports' =>[
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));
		
		acf_register_block_type(array(
			'name'              => 'slick-slider',
			'title'             => 'Bilder-Slider',
			'description'       => 'Fügt einen Bilder-Slider hinzu',
			'render_template'   => 'blocks/acf-slick-slider/block.php',
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( '' ),
			'mode' => 'edit',
			'supports' =>[
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));
		

		

		
		acf_register_block_type(array(
			'name'              => 'aktuelles',
			'title'             => 'Aktuelles',
			'description'       => 'Fügt eine Aktualität ein',
			'render_template'   => 'blocks/acf-aktuelles/block.php',		
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( '' ),
			'mode' => 'edit',
//			'post_types' => array('aktuelles'),
			'supports' =>[
//				'multiple' => false,
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));
		
		/* =============================================================== *\ 
 	 	   Front-Page 
		\* =============================================================== */ 
		acf_register_block_type(array(
			'name'              => 'slick-slider-front-page',
			'title'             => 'Bilder-Slider Front-Page',
			'description'       => 'Fügt einen Bilder-Slider hinzu',
			'render_template'   => 'blocks/acf-slick-slider/block.php',
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( '' ),
			'mode' => 'edit',
			'supports' =>[
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));
		
		acf_register_block_type(array(
			'name'              => 'aktuelle-touren-auf-startseite',
			'title'             => 'Aktuelle Touren',
			'description'       => 'Fügt die aktuellen Touren ein',
			'render_template'   => 'blocks/acf-front_page_touren/block.php',
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( '' ),
			'mode' => 'edit',
			'post_types' => array('page'),
			'supports' =>[
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));  

		acf_register_block_type(array(
			'name'              => 'neue_tourenberichte',
			'title'             => 'Neue Tourenberichte',
			'description'       => 'Fügt neue Tourenbericht ein',
			'render_template'   => 'blocks/acf-front_page_tourenberichte/block.php',		
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( 'neuer Tourebericht' ),
			'mode' => 'edit',
			'supports' =>[
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));
		
		acf_register_block_type(array(
			'name'              => 'aktuelles-auf-startseite',
			'title'             => 'Aktuelles auf Startseite',
			'description'       => 'Fügt eine Aktualität auf der Startseite ein',
			'render_template'   => 'blocks/acf-front_page_aktuelles/block.php',
			
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( '' ),
			'mode' => 'edit',
			'post_types' => array('page'),
			'supports' =>[
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));		
		
		acf_register_block_type(array(
			'name'              => 'downloads',
			'title'             => 'Downloads',
			'description'       => 'Fügt Downloads ein',
			'render_template'   => 'blocks/acf-downloads/block.php',
			
			'category'          => 'formatting',
			'icon'              => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person-hiking" class="svg-inline--fa fa-person-hiking fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M240 96c26.5 0 48-21.5 48-48S266.5 0 240 0C213.5 0 192 21.5 192 48S213.5 96 240 96zM80.01 287.1c7.31 0 13.97-4.762 15.87-11.86L137 117c.3468-1.291 .5125-2.588 .5125-3.866c0-7.011-4.986-13.44-12.39-15.13C118.4 96.38 111.7 95.6 105.1 95.6c-36.65 0-70 23.84-79.32 59.53L.5119 253.3C.1636 254.6-.0025 255.9-.0025 257.2c0 7.003 4.961 13.42 12.36 15.11L76.01 287.5C77.35 287.8 78.69 287.1 80.01 287.1zM368 160h-15.1c-8.875 0-15.1 7.125-15.1 16V192h-34.75l-46.75-46.75C243.4 134.1 228.6 128 212.9 128C185.9 128 162.5 146.3 155.9 172.5L129 280.3C128.4 282.8 128 285.5 128 288.1c0 8.325 3.265 16.44 9.354 22.53l86.62 86.63V480c0 17.62 14.37 32 31.1 32s32-14.38 32-32v-82.75c0-17.12-6.625-33.13-18.75-45.25l-46.87-46.88c.25-.5 .5-.875 .625-1.375l19.1-79.5l22.37 22.38C271.4 252.6 279.5 256 288 256h47.1v240c0 8.875 7.125 16 15.1 16h15.1C376.9 512 384 504.9 384 496v-320C384 167.1 376.9 160 368 160zM81.01 472.3c-.672 2.63-.993 5.267-.993 7.86c0 14.29 9.749 27.29 24.24 30.89C106.9 511.8 109.5 512 112 512c14.37 0 27.37-9.75 30.1-24.25l25.25-101l-52.75-52.75L81.01 472.3z"></path></svg>',
			'keywords'          => array( '' ),
			'mode' => 'edit',
			'post_types' => array('page'),
			'supports' =>[
				'align'			=> false,
				'mode'			=> false,
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> false,
				]
		));		
    }
}


/* =============================================================== *\ 
   Block-Template: Tourenportal 
   @link https://developer.wordpress.org/block-editor/developers/block-api/block-templates/
\* =============================================================== */ 
function block_template_tourenportal() {
	$page_type_object = get_post_type_object( 'touren' );
	$page_type_object->template = [
		//[ 'core/group', [], [
		[ 'acf/tourdatum'],
		[ 'acf/touren-kurzinfo'],
		[ 'acf/touren-details'],
	];
}
add_action( 'init', 'block_template_tourenportal' );

function block_template_tourenberichte() {
	$page_type_object = get_post_type_object( 'tourenbericht' );
	$page_type_object->template = [
		[ 'acf/verknuepfung-zur-tour'],
	];
}
add_action( 'init', 'block_template_tourenberichte' );


/* =============================================================== *\ 
 	 Custom Post Status 
\* =============================================================== */ 
function wpdocs_custom_post_status(){
    register_post_status( 'archiv', array(
        'label'                     => 'Archiv',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Archiv <span class="count">(%s)</span>', 'Archiv <span class="count">(%s)</span>' ),
		'show_in_metabox_dropdown'  => true,
                    'show_in_inline_dropdown'   => true,
                    'dashicon'                  => 'dashicons-businessman',
    ) );
}
add_action( 'init', 'wpdocs_custom_post_status' );


add_filter( 'display_post_states', function( $statuses ) {
    global $post;
	if($post!=NULL):
    	if( $post->post_type == 'touren') {
        	if ( get_query_var( 'post_status' ) != 'archiv' ) { // not for pages with all posts of this status
            	if ( $post->post_status == 'archiv' ) {
                	return array( 'Archiv' );
            	}
        	}
		}
    	return $statuses;
	endif;
});

function my_custom_status_add_in_quick_edit() {
	echo "<script>
	jQuery(document).ready( function() {
	jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"archiv\">Archiv</option>' );      
	}); 
	</script>";
}
add_action('admin_footer-edit.php','my_custom_status_add_in_quick_edit');

function my_custom_status_add_in_post_page() {
	echo "<script>
	jQuery(document).ready( function() {        
	jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"archiv\">Archiv</option>' );
	});
	</script>";
	}
add_action('admin_footer-post.php', 'my_custom_status_add_in_post_page');
add_action('admin_footer-post-new.php', 'my_custom_status_add_in_post_page');



/* =============================================================== *\ 

 	 Archive Queries 
	 Query für Archive Pages 
	 - Aktuelle Touren
	 - Touren-Archiv
	 - Tourenberichte
	 - Aktuelles Archiv
\* =============================================================== */ 
function my_archive_queries(){
	global $wp_query;

	$is_aktuelle_touren = false;
	$is_vergangene_touren = false; 
	$is_tourenbericht = false;
	$is_aktuell_archiv = false;

	if("touren"==get_post_type()):
		$is_aktuelle_touren = true;
	endif;

	if("archive-touren-archive.php" == get_page_template_slug()):
		$is_vergangene_touren = true; 
	endif;

	if("tourenbericht"==get_post_type()):
		$is_tourenbericht = true;
	endif;

	if("page-aktuell-archive.php" == get_page_template_slug()):
		$is_aktuell_archiv = true; 
	endif;
	$my_post_type = "post";
	$my_post_status = 'publish';
	$my_meta_key = '';
	$my_orderby = 'id';
	$my_grid_sizer_width = "400px";
	$my_order = "ASC";
	$my_posts_per_page = get_option('posts_per_page', 10);
	$my_paged = get_query_var('paged') ? get_query_var('paged') : 1; // gibt an, auf welcher Seite der Pagination sich der Anwender befindet.

	$my_offset = ($my_paged - 1) * $my_posts_per_page;
	$my_page_title = "";

	// Tourenberichte
	if($is_tourenbericht == true):
		$my_post_type = "tourenbericht";
		$my_page_title = "Touren-Berichte";
		$my_order = "DESC";
		$my_posts_per_page = -1;
		
	// Aktuelle Touren
	elseif($is_aktuelle_touren==true):
		$my_post_type = "touren";
		$my_meta_key = 'current_tour_date';
		$my_orderby = 'meta_value';
		$my_page_title = "Unsere nächsten Touren";

	// Vergangene Touren
	elseif($is_vergangene_touren==true):
		$my_post_type = "touren";
		$my_post_status = 'archiv';		
		$my_meta_key = 'current_tour_date';
		$my_orderby = 'meta_value';
		$my_order = "DESC";
		$my_page_title = "Touren-Archiv";    
		$my_grid_sizer_width = "1000px"; 
		$is_vergangene_touren = true;
	
	// Aktuell Archiv
	elseif($is_aktuell_archiv==true):
		$my_post_type = 'aktuell';
		$my_order = "DESC";
	  	$my_posts_per_page = 3;
		$is_aktuell_archiv = true;
		$my_page_title = "Neuigkeiten aus der Sektion";
		$my_offset = ($my_paged - 1) * $my_posts_per_page;



	  	//$my_offset = ($paged - 1) * $post_per_page;
  	endif;
	  
	  
	$args = array( 
		'post_type' => $my_post_type,
		'post_status' => $my_post_status,
		'meta_key' => $my_meta_key,
		'orderby' => $my_orderby,
		'order' => $my_order,    
		'posts_per_page' => $my_posts_per_page,
		'paged' => $my_paged,
		'offset' => $my_offset,
		'aktuelle_touren' => $is_aktuelle_touren,
		'vergangene_touren' => $is_vergangene_touren,
		'touren_bericht' => $is_tourenbericht,
		'aktuell_archiv' => $is_aktuell_archiv,
		'my_page_title' => $my_page_title,
		'my_grid_sizer_width' => $my_grid_sizer_width,    
	);
	wp_reset_postdata();
	return($args);
}





/* =============================================================== *\ 

 	 Alle Touren auslesen 
	 - nur bei Startseiten-Block

\* =============================================================== */ 
function get_aktuelle_touren(){
	$args = array( 
	    'post_type' => 'touren',
	    'post_status' => 'publish',
	    'meta_key' => 'current_tour_date',
	    'orderby' => 'meta_value',
	    'order' => "ASC",    
	    'posts_per_page' => '-1',	    
	);
	$statrseiten_query = new WP_Query($args);
	
	$my_posts_array = array();
	if ( $statrseiten_query -> have_posts() ) : 
		while($statrseiten_query->have_posts()):
			$statrseiten_query->the_post();	
			array_push($my_posts_array, get_the_ID());
		endwhile;
	endif;	
	return($my_posts_array);
	wp_reset_postdata();
}


/* =============================================================== *\ 

 	 Max 3 Tourenberichte auslesen 
	- nur bei Startseiten-Block

\* =============================================================== */ 
function get_aktuelle_tourenberichte(){
   $args = array( 
	   'post_type' => 'tourenbericht',
	   'post_status' => 'publish',
	   'order' => "DESC",    
	   'posts_per_page' => '3',	    
   );
   $startseiten_query = new WP_Query($args);
   
   $my_posts_array = array();
   if ( $startseiten_query -> have_posts() ) : 
	   while($startseiten_query->have_posts()):
		   $startseiten_query->the_post();	   
		   array_push($my_posts_array, get_the_ID());
	   endwhile;
   endif;
   return($my_posts_array);
   wp_reset_postdata();
} 


/* =============================================================== *\ 
 	 Bereiche  
	 JO, Vetereanen usw.
	 https://florianbrinkmann.com/bestimmte-gutenberg-bloecke-eines-beitrags-im-theme-an-anderer-stelle-ausgeben-6602/
\* =============================================================== */ 
function get_all_bereiche_als_chip($post_id){
	$alle_bereiche_arr = array();
	$bereich_classes = ""; // Klassen: jo veteranen
	$bereich_data_temp_filter = ""; // data-temp-filter: .jo .veteranen
	$bereich_chip = ""; // <div class="chip veteranen">Veteranen</div>
	$bereiche_iterator = 1;
	$blocks = parse_blocks(get_the_content(NULL, false, $post_id));
	foreach($blocks as $block):
	
		if("acf/touren-kurzinfo"==$block['blockName']):
			foreach($block['attrs']['data']['bereich'] as $bereich):	
				array_push($alle_bereiche_arr, $bereich);
			endforeach;
		endif;
	endforeach;
		
	// Da Touren-Berichte über eine Page geladen werden
	if(get_post_type()=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;

		$tour_post = get_post( $tour_id ); 		
		$blocks = parse_blocks($tour_post->post_content);
		foreach($blocks as $block):
			if("acf/touren-kurzinfo"==$block['blockName']):
				foreach($block['attrs']['data']['bereich'] as $bereich):	
					array_push($alle_bereiche_arr, $bereich);
				endforeach;
				endif;
		endforeach;
		
	endif;
	//HTML-Ausgabe als String vorbereiten
	foreach($alle_bereiche_arr as $bereich):
		$bereich_classes .= $bereich; 
		$bereich_data_temp_filter .= "." . $bereich; 
		$bereich_chip .= '<div class="chip bordered ' . strtolower($bereich) . '">' . $bereich . '</div>';
		if(count($alle_bereiche_arr)>$bereiche_iterator):
			$bereich_classes .= " "; //leerschlag zwischen mehreren Werten
			$bereich_data_temp_filter .= " ";
		endif;
		$bereiche_iterator++;
	endforeach;

	return($bereich_chip);
}

function get_all_bereiche_als_chip_tourenbericht($post_id, $my_post_type){
	$alle_bereiche_arr = array();
	$bereich_classes = ""; // Klassen: jo veteranen
	$bereich_data_temp_filter = ""; // data-temp-filter: .jo .veteranen
	$bereich_chip = ""; // <div class="chip veteranen">Veteranen</div>
	$bereiche_iterator = 1;
	$blocks = parse_blocks(get_the_content(NULL, false, $post_id));
	foreach($blocks as $block):
	
		if("acf/touren-kurzinfo"==$block['blockName']):
			foreach($block['attrs']['data']['bereich'] as $bereich):	
				array_push($alle_bereiche_arr, $bereich);
			endforeach;
		endif;
	endforeach;
		
	// Da Touren-Berichte über eine Page geladen werden
	if($my_post_type=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;

		$tour_post = get_post( $tour_id ); 		
		$blocks = parse_blocks($tour_post->post_content);
		foreach($blocks as $block):
			if("acf/touren-kurzinfo"==$block['blockName']):
				foreach($block['attrs']['data']['bereich'] as $bereich):	
					array_push($alle_bereiche_arr, $bereich);
				endforeach;
				endif;
		endforeach;
		
	endif;
	//HTML-Ausgabe als String vorbereiten
	foreach($alle_bereiche_arr as $bereich):
		$bereich_classes .= $bereich; 
		$bereich_data_temp_filter .= "." . $bereich; 
		$bereich_chip .= '<div class="chip bordered ' . strtolower($bereich) . '">' . $bereich . '</div>';
		if(count($alle_bereiche_arr)>$bereiche_iterator):
			$bereich_classes .= " "; //leerschlag zwischen mehreren Werten
			$bereich_data_temp_filter .= " ";
		endif;
		$bereiche_iterator++;
	endforeach;

	return($bereich_chip);
}


function get_current_alle_bereiche($post_id){
	$alle_bereiche_arr = array();
	$blocks = parse_blocks(get_the_content(NULL, false, $post_id));
	foreach($blocks as $block):
		if("acf/touren-kurzinfo"==$block['blockName']):
			foreach($block['attrs']['data']['bereich'] as $bereich):	
				array_push($alle_bereiche_arr, $bereich);
			endforeach;
		endif;
	endforeach;
	
	// Da Touren-Berichte über eine Page geladen werden
	if(get_post_type()=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;
		
		$tour_post = get_post( $tour_id ); 
		
		$blocks = parse_blocks($tour_post->post_content);
		foreach($blocks as $block):
			if("acf/touren-kurzinfo"==$block['blockName']):
				foreach($block['attrs']['data']['bereich'] as $bereich):	
					array_push($alle_bereiche_arr, $bereich);
				endforeach;
				array_push($alle_bereiche_arr, $block['attrs']['data']['art_der_tour']);

				endif;
		endforeach;
	endif;
	
	return($alle_bereiche_arr);
}


/* =============================================================== *\ 
 	 Bereiche für Single post ausgabe
\* =============================================================== */ 
function get_bereiche_und_art_der_tour_for_single_post_tourenbericht($post_id, $my_post_type){
	$alle_bereiche_arr = array();
	// Da Touren-Berichte über eine Page geladen werden
	if($my_post_type=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;
		
		$tour_post = get_post( $tour_id ); 
		$blocks = parse_blocks($tour_post->post_content);
		foreach($blocks as $block):
			if("acf/touren-kurzinfo"==$block['blockName']):
				foreach($block['attrs']['data']['bereich'] as $bereich):	
					array_push($alle_bereiche_arr, $bereich);
				endforeach;
				array_push($alle_bereiche_arr, $block['attrs']['data']['art_der_tour']);

				endif;
		endforeach;	
	endif;
	
	$bereich_classes = array("post_" . $post_id, "grid_item","touren_bericht");
	foreach($alle_bereiche_arr as $item):
		array_push($bereich_classes, strtolower($item));
	endforeach;
	
	$bereich_plain = array();	
	foreach($alle_bereiche_arr as $item):
		array_push($bereich_plain, strtolower("." . $item));
	endforeach;
	$alle_bereiche_ass_arr = array();
	$alle_bereiche_ass_arr['classes'] = implode(" ", $bereich_classes);
	$alle_bereiche_ass_arr['plain'] = implode(" ", $bereich_plain);
	
	return($alle_bereiche_ass_arr);	
}










function get_bereiche_und_art_der_tour_for_single_post($post_id){

	$alle_bereiche_arr = array();
	$blocks = parse_blocks(get_the_content(NULL, false, $post_id));
	foreach($blocks as $block):
		//echo $block["blockName"];
		
		if("acf/verknuepfung-zur-tour" == $block["blockName"]):
			$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			//echo "<pre>";
			//var_dump($block);
			//echo "</pre>";
		endif;
		
		
		if("acf/touren-kurzinfo"==$block['blockName']):
			
			foreach($block['attrs']['data']['bereich'] as $bereich):	
				array_push($alle_bereiche_arr, $bereich);
			endforeach;
				array_push($alle_bereiche_arr, $block['attrs']['data']['art_der_tour']);
		endif;
	endforeach;
	
	// Da Touren-Berichte über eine Page geladen werden
	if(get_post_type()=="tourenbericht"):
		//echo "is_tourenbericht";
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;
		
		$tour_post = get_post( $tour_id ); 
		$blocks = parse_blocks($tour_post->post_content);
		foreach($blocks as $block):
			if("acf/touren-kurzinfo"==$block['blockName']):
				foreach($block['attrs']['data']['bereich'] as $bereich):	
					array_push($alle_bereiche_arr, $bereich);
				endforeach;
				array_push($alle_bereiche_arr, $block['attrs']['data']['art_der_tour']);

				endif;
		endforeach;	
	endif;
	
	$bereich_classes = array("grid_item");
	foreach($alle_bereiche_arr as $item):
		array_push($bereich_classes, strtolower($item));
	endforeach;
	
	$bereich_plain = array();	
	foreach($alle_bereiche_arr as $item):
		array_push($bereich_plain, strtolower("." . $item));
	endforeach;
	$alle_bereiche_ass_arr = array();
	$alle_bereiche_ass_arr['classes'] = implode(" ", $bereich_classes);
	$alle_bereiche_ass_arr['plain'] = implode(" ", $bereich_plain);
	
	return($alle_bereiche_ass_arr);
}




/* =============================================================== *\ 
 	 Kurzinfo 
\* =============================================================== */ 
function get_current_kurzinfo($post_id){
	$kurzinfo = "";
	$post = get_post( $post_id ); 
	if ( has_blocks( $post->post_content ) ) {
    	$blocks = parse_blocks( $post->post_content );
		foreach ( $blocks as $block ): 
			if('acf/touren-kurzinfo' === $block['blockName']):
				$kurzinfo = render_block($block);
			endif;
		endforeach;
	}

	/* Tourenbericht */
	if(get_post_type()=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id) );
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$post_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;
		
		$blocks = parse_blocks(get_the_content(NULL, false, $post_id));
		foreach($blocks as $block):
			if("acf/touren-kurzinfo"==$block['blockName']):
				$kurzinfo = render_block($block);
			endif;
		endforeach;
	endif;
	
	return($kurzinfo);
}

function get_current_kurzinfo_tourenbericht($post_id, $my_post_type){
	$kurzinfo = "";
	$post = get_post( $post_id ); 
	if ( has_blocks( $post->post_content ) ) {
    	$blocks = parse_blocks( $post->post_content );
		foreach ( $blocks as $block ): 
			if('acf/touren-kurzinfo' === $block['blockName']):
				$kurzinfo = render_block($block);
			endif;
		endforeach;
	}

	/* Tourenbericht */
	if($my_post_type=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id) );
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$post_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;
		
		$blocks = parse_blocks(get_the_content(NULL, false, $post_id));
		foreach($blocks as $block):
			if("acf/touren-kurzinfo"==$block['blockName']):
				$kurzinfo = render_block($block);
			endif;
		endforeach;
	endif;
	
	return($kurzinfo);
}



function get_direct_or_linked_post_id($post_id){
	$post_id = get_the_ID(); 
	if(get_post_type()=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $post_id) );
		foreach ( $blocks as $block ):
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$post_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;				
	endif;
	
	return($post_id);
}

/* =============================================================== *\ 
   Tourdatum
   - aktuelle Touren: Block 
   - Archiv-Touren: Block
   - Tourenbricht:  meta_key
\* =============================================================== */ 
function get_current_tourdatum($post_id){
	global $is_touren_archiv;	
	$tour_id = get_the_ID(); 
	$tour_id = $post_id;
	$tourdatum = "";

	$blocks = parse_blocks( get_the_content(NULL, false, $tour_id) );
	foreach ( $blocks as $block ): 
		if('acf/tourdatum' === $block['blockName']):					
			$tourdatum = render_block($block);
		endif;
	endforeach;
	
	if(get_post_type()=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $tour_id) );
		foreach ( $blocks as $block ): 
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;	
		$tourdatum = make_short_date(get_post_meta($tour_id, 'current_tour_date')[0]);
			
	endif;
	if((is_front_page()) || ($is_touren_archiv==true)):
		$tourdatum = make_short_date(get_post_meta($tour_id, 'current_tour_date')[0]);
	endif;
	
	return($tourdatum);
}

function get_current_tourdatum_tourenbericht($post_id, $my_post_type){
	global $is_touren_archiv;	
	$tour_id = get_the_ID(); 
	$tour_id = $post_id;
	$tourdatum = "";

	$blocks = parse_blocks( get_the_content(NULL, false, $tour_id) );
	foreach ( $blocks as $block ): 
		if('acf/tourdatum' === $block['blockName']):					
			$tourdatum = render_block($block);
		endif;
	endforeach;
	
	if($my_post_type=="tourenbericht"):
		$blocks = parse_blocks( get_the_content(NULL, false, $tour_id) );
		foreach ( $blocks as $block ): 
			if("acf/verknuepfung-zur-tour"==$block['blockName']):
				$tour_id = $block['attrs']['data']['verknuepfung-zur-tour'];
			endif;
		endforeach;	
		$tourdatum = make_short_date(get_post_meta($tour_id, 'current_tour_date')[0]);
			
	endif;
	if((is_front_page()) || ($is_touren_archiv==true)):
		$tourdatum = make_short_date(get_post_meta($tour_id, 'current_tour_date')[0]);
	endif;
	
	return($tourdatum);
}

/* =============================================================== *\ 
 	 Autor 
\* =============================================================== */ 
 function get_current_autor($post_id){
	 $autorname = "";
	 $blocks = parse_blocks( get_the_content(NULL, false, $post_id));
	 
	 foreach ( $blocks as $block ): 
		 if('acf/verknuepfung-zur-tour' === $block['blockName']):		
			 $autorname = $block['attrs']['data']['autorin'];
	 	endif;
	 endforeach;

	 return($autorname);
 }


 /* =============================================================== *\ 
     Aktuelles 
	 Datum
     Da der Block über ein Pseudo-Archiv ausgegeben wird,
     muss das datum folgendermassen herausgefunden werden:
     - Über die Block id herausfinden, in welchem Post diese vorhanden ist,
     - von diesem Post das Datum nehmen

 \* =============================================================== */ 
 function aktuell_beitraege_datum($my_block_id){
     $args = array(
         'post_type' => 'aktuell',
         'posts_per_page' => -1
     );
     $query = new WP_Query($args);
     if ($query->have_posts() ) : 
         while ( $query->have_posts() ) : 
             $query->the_post();
             $blocks = parse_blocks(get_the_content());
             foreach($blocks as $block):
                 //var_dump($block);
                 if($block['attrs']['id']== $my_block_id):
                     //echo get_the_ID();
                     $my_post_id = get_the_ID();
                     $datum = get_the_date('j. F Y', $my_post_id);                     
                 endif;
             endforeach;
         endwhile;
     
         wp_reset_postdata();
     endif;
     return($datum);
 }

 /* =============================================================== *\ 
  	AJAX Search-Funktion
 	https://wpmudev.com/blog/load-posts-ajax/
 	 
 	ajax-js			functions.php
 	button 			archive.php
 	button action 	ulrich.js > ruft my_ajax_search_function auf
 	search-results	search-results.php
 \* =============================================================== */ 
 /* Frontend */
 add_action( 'wp_enqueue_scripts', 'add_touren_archiv_javascripts' );
 function add_touren_archiv_javascripts() {
 
 	global $wp_query;
 	//wp_localize_script(handler, variable in js,)
 	wp_localize_script( 'ajax-call', 'ajaxcall', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
 		'query_vars' => json_encode( $wp_query->query ),
 		'post_type' => json_encode( get_post_type()),
 		'post_status' => json_encode( get_post_status()),
 		'post_classes' => json_encode( get_body_class()),
 		'post_id' => json_encode(get_the_ID()),
     	)
 	);
 }
 
 
   
 add_action('wp_ajax_nopriv_my_ajax_search_function', 'my_ajax_search_function'); // for not logged in users
 add_action('wp_ajax_my_ajax_search_function', 'my_ajax_search_function');
 function my_ajax_search_function(){
 	$search_values = json_decode(stripslashes($_POST["search_string"]),true); //$_POST["search_string"] ist Variable aus $.ajax
	$myqueryvars = json_decode(stripslashes($_POST["myqueryvars"]),true);
	$post_type = json_decode(stripslashes($_POST["post_type"]),true);
	$post_status = json_decode(stripslashes($_POST["post_status"]),true);
	$post_classes = json_decode(stripslashes($_POST["post_classes"]),true);
	$post_id = json_decode(stripslashes($_POST["post_id"]),true);
 	get_template_part( 'search-results', NULL, array($search_values, $myqueryvars, $post_type, $post_status, $post_classes, $post_id)); 
	wp_die();
 }

 /* =============================================================== *\ 
    Ajax-Menu
	- auf Page vergangene Touren
	- auf Page Tourenberichte
 \* =============================================================== */ 
 function get_ajax_menu(){
 	$my_query_args = array( 
 		'post_type' => 'touren',
 		'post_status' => 'archiv',
 		'meta_key' => 'current_tour_date',
 		'orderby' => 'meta_value',
 		'order' => 'DESC',    
 	);
 	
 	$custom_query = new WP_Query($my_query_args); 
 	$alle_bereiche_arr = array();
 	$alle_art_der_touren_arr = array();
 	$alle_tourdaten_arr = array();
 	if ( $custom_query -> have_posts() ) :
 		while ( $custom_query -> have_posts() ) : 
 			$custom_query -> the_post(); 
 			$post_id = get_the_ID();
 			
 			// Alle Tourdaten
 			$my_tour_date = get_post_meta($post_id, 'current_tour_date');
 			$my_tour_date = mb_substr($my_tour_date[0], 0, 4);
 			array_push($alle_tourdaten_arr, $my_tour_date);
 		endwhile; 
 	endif;
 	wp_reset_postdata();
 	  
 	//HTML-Ausgabe
 	$my_html_ausgabe = "";
 	
 	// Tourdaten
 	$alle_tourdaten_arr = array_unique($alle_tourdaten_arr);
 	$tourdaten_iterator = 0;
 	if(count($alle_tourdaten_arr)>0):
 		$my_html_ausgabe .= '<div class="container ajax menu">';
 	endif;
 	foreach($alle_tourdaten_arr as $datum):
 		$my_html_ausgabe .= '<button class="chip bordered ' . $datum . '" value="' . $datum . '" data-search-category="jahr" data-temp-value="' . $datum . '">' . $datum . '</button>';
 		$tourdaten_iterator++;
 		if(count($alle_tourdaten_arr)==$tourdaten_iterator):
 			$my_html_ausgabe .= "<br/>";
 		endif;
 	endforeach;
 	if(count($alle_tourdaten_arr)>0):
 		$my_html_ausgabe .= '</div>';
 	endif;
 	
 	return($my_html_ausgabe);
 }


/* =============================================================== *\ 
 	 Isotope-Menu 
	 Erstellt ein dynamisches Menü für die Archiv-Seiten
	 - Tourenberichte
	 - Vergangene Touren
	 
\* =============================================================== */ 
 
function get_isotope_menu($query){ 
	
	$isotope_query = $query;
	$sektions_gruppe = "";
	$art_der_tour_gruppe = "";
	
	if($isotope_query->have_posts()):
		$post_id = get_the_ID();
		$sektions_gruppe_menu_array = array();
		$art_der_tour_gruppe_menu_array = array();


		$is_tourenbericht = false;
		if(is_archive() && (get_post_type()=="tourenbericht")):
			$is_tourenbericht = true;
		endif;
		
		while($isotope_query->have_posts()):
			$isotope_query->the_post();
			if($is_tourenbericht == true):
				$blocks = parse_blocks(get_the_content(NULL, false, get_direct_or_linked_post_id($post_id)));
			else:
				$blocks = parse_blocks(get_the_content());
			endif;
			foreach ( $blocks as $block ): 
				if('acf/touren-kurzinfo' === $block['blockName']):
					if((isset($block['attrs']['data']['bereich'])) && ($block['attrs']['data']['bereich']!="")): // Sektion usw.
						foreach($block['attrs']['data']['bereich'] as $my_sektions_gruppe):
							array_push($sektions_gruppe_menu_array, $my_sektions_gruppe);
						endforeach;
						array_push($art_der_tour_gruppe_menu_array, $block['attrs']['data']['art_der_tour']);
					endif;
				endif; 
			endforeach;
		endwhile;

		/* =============================================================== *\ 
 	 	   Bereiche 
	 	\* =============================================================== */ 
		$sektions_gruppe_menu_array = array_unique( $sektions_gruppe_menu_array);		
		$art_der_tour_gruppe_menu_array = array_unique( $art_der_tour_gruppe_menu_array);		

		$sektions_gruppe .= "<div class='isotope menu chips_container my_isotope_filters button-group' data-filter='.bereich' data-filter-group='group1'>";
		foreach($sektions_gruppe_menu_array as $menu_eintrag):
			$sektions_gruppe_class = strtolower($menu_eintrag);
			$sektions_gruppe .= "<button class='show_all chip bordered " . $sektions_gruppe_class . "' data-filter='." . $sektions_gruppe_class . "' data-search-category='bereich'>" . $menu_eintrag . "</button>";
		endforeach;
		$sektions_gruppe .= "</div>";
		
		/* =============================================================== *\ 
 	 	   Art der Touren 
	 	\* =============================================================== */  		
		$art_der_tour_gruppe .= "<div class='isotope menu chips_container my_isotope_filters button-group' data-filter='.art_der_tour' data-filter-group='group2'>";
		foreach($art_der_tour_gruppe_menu_array as $menu_eintrag):
			$art_der_tour_gruppe_class = strtolower($menu_eintrag);
			$art_der_tour_gruppe .= "<button class='show_all chip bordered " . $art_der_tour_gruppe_class . "' data-filter='." . $art_der_tour_gruppe_class . "' data-search-category='art_der_tour'>" . ucfirst($menu_eintrag) . "</button>";
		endforeach;
		$art_der_tour_gruppe .= "</div>";

		$output = $sektions_gruppe . $art_der_tour_gruppe;		
	endif;
	wp_reset_postdata();
	return($output);
}



/* =============================================================== *\ 
 	
	 Tourenbericht-Formular
	 
	 https://wordpress.stackexchange.com/questions/310756/how-to-submit-data-from-html-form
	 Radio: https://moderncss.dev/pure-css-custom-styled-radio-buttons/
\* =============================================================== */ 

// Paswort-Formular anpassen
add_filter( 'the_password_form', 'custom_password_form' );
function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<form class="post-password-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">';
	$o .= '<h3>Hallo!</h3>';
	$o .= '<p>Um einen Tourenbericht zu erfassen, brauchst du ein Passwort.</p>';
	$o .= '<p>Dein Touren-Leiter kann Dir dieses mitteilen.</p>';
	$o .= '<div class="password_line">';
	$o .= '<label class="pass_label" for="' . $label . '">Passwort: ';
	$o .= '<input name="post_password" class="bordered rounded" style="padding:5px 10px" id="' . $label . '" type="password"  size="20" />';
	$o .= '</label> ';
	$o .= '<button id="my_name" class="more_button animated_button grey go_green" name="my_name" type="submit">Anmelden</button>';
	$o .= '<input type="hidden" name="Submit" class="more_button animated_button grey go_green" value="Absenden" />';
//	$o .= '<input type="submit" name="Submit" class="more_button animated_button grey go_green" value="Absenden" />';
	$o .= '</div>';
	return $o;
}




// DEV wahrscheinlich löschen
//add_filter('acf/fields/post_object/query', 'my_acf_fields_post_object_query', 10, 3);
function my_acf_fields_post_object_query( $args, $field, $post_id ) {
    $args['post_status'] = "archiv";
    return $args;
}


/* =============================================================== *\ 
 	 Arrays
	 - Touren im Archiv
	 - Touren mit Bericht
	 - Touren ohne Bericht 
\* =============================================================== */ 

/* =============================================================== *\ 
   Array aller vergangener Touren 
\* =============================================================== */ 
function alle_touren_mit_post_status_archiv(){
	$touren_archiv_array = array();
	$touren_archiv_array_args = array(
		'post_type'             => array( 'touren' ),
		'post_status'           => array( 'archiv' ),
		'posts_per_page'   		=> -1,
		'meta_key' => 'current_tour_date',
		'orderby' => 'meta_value',
		'order' => 'ASC',    
	);
	$alle_archiv_touren = new WP_Query( $touren_archiv_array_args );
	if ( $alle_archiv_touren->have_posts()):
		while ( $alle_archiv_touren->have_posts()):
			$alle_archiv_touren->the_post();
			$archiv_touren_id = get_the_ID();
			array_push($touren_archiv_array, $archiv_touren_id);
		endwhile;
	endif;
	wp_reset_postdata();
	return($touren_archiv_array);
}

/* =============================================================== *\ 
   Array mit allen Touren mit Bericht
\* =============================================================== */ 
function alle_touren_mit_bericht(){
	$touren_mit_bericht_array = array();
	$alle_berichte_array_args = array(
		'post_type'             => array( 'tourenbericht' ),
		'post_status'             => array( 'publish', 'draft' ),
		'posts_per_page'   		=> -1,
		'orderby'				=> 'ID',
		'order'					=> 'asc',
	);
	$alle_berichte_array = new WP_Query( $alle_berichte_array_args );
	if ( $alle_berichte_array->have_posts()):
		while ( $alle_berichte_array->have_posts()):
			$alle_berichte_array->the_post();
			
			$blocks = parse_blocks( get_the_content() );
			foreach($blocks as $block):
				if("acf/verknuepfung-zur-tour" == $block["blockName"]):
					if($block['attrs']['data']['verknuepfung-zur-tour']!=""){
						$post_id = $block['attrs']['data']['verknuepfung-zur-tour'];
						array_push($touren_mit_bericht_array, $post_id);
					}
				endif;
			endforeach;
		
		endwhile;
	endif;
	wp_reset_postdata();
	return($touren_mit_bericht_array);
}


/* =============================================================== *\ 
   Array mit allen Touren ohne Bericht
\* =============================================================== */ 
function alle_touren_ohne_bericht(){
	$touren_ohne_bericht_array = array();
	$touren_ohne_bericht_array = array_diff( alle_touren_mit_post_status_archiv(), alle_touren_mit_bericht());
	return($touren_ohne_bericht_array);
}

/* =============================================================== *\ 
   ID des verlinkten Berichtes 
\* =============================================================== */ 
function bericht_id_der_verknuepften_tour($current_post_id){
	$vergleichs_array = array();
	$alle_berichte_array_args = array(
		'post_type'             => array( 'tourenbericht' ),
		'post_status'             => array( 'publish', 'draft' ),
		'posts_per_page'   		=> -1,
		'orderby'				=> 'ID',
		'order'					=> 'asc',
	);
	$alle_berichte_array = new WP_Query( $alle_berichte_array_args );
	if ( $alle_berichte_array->have_posts()):
		while ( $alle_berichte_array->have_posts()):
			$alle_berichte_array->the_post();
			$blocks = parse_blocks( get_the_content() );
			foreach($blocks as $block):
				if("acf/verknuepfung-zur-tour" == $block["blockName"]):
					if($block['attrs']['data']['verknuepfung-zur-tour']!=""){
						$post_id = $block['attrs']['data']['verknuepfung-zur-tour'];
						$vergleichs_array[get_the_ID()] = $post_id;

					}
				endif;
			endforeach;
		
		endwhile;
	endif;
	wp_reset_postdata();
	// ist die ID des Posts im Array vorhanden, dann gibt Berichts-ID aus 
	$bericht_ID = array_search($current_post_id, $vergleichs_array);
	return($bericht_ID);
}

/* =============================================================== *\ 
   Prüfen, ob Tour-Datum in der Vergangenheit liegt
\* =============================================================== */ 
function is_archive_tour(){
	$is_archive_tour = false;
	$heute = date("Ymd");
	$my_tour_date = get_post_meta(get_the_ID(), 'current_tour_date');

	if($heute > $my_tour_date[0]){
		$is_archive_tour = true;
	}
	return($is_archive_tour);
}

/* =============================================================== *\ 
   Prüfen, ob Bericht vorhanden ist
\* =============================================================== */ 
function ist_bericht_vorhanden(){
	$bericht_vorhanden = false;
	if(in_array(get_the_ID(), alle_touren_mit_bericht())==true){
		$bericht_vorhanden = true;
	}
	return($bericht_vorhanden);
}

/* =============================================================== *\ 
   Ausgabe: Alle Touren ohne Bericht 
\* =============================================================== */ 
function alle_touren_ohne_bericht_ausgeben(){	
	$touren_ohne_bericht_array = alle_touren_ohne_bericht();
	if(count($touren_ohne_bericht_array)>0):
		$alle_touren_ohne_bericht_html_output = "<fieldset class='fieldset_radio container rounded white box_shadow'>";

		foreach($touren_ohne_bericht_array as $tour_ohne_bericht):
			$title = get_the_title($tour_ohne_bericht);
			$id = $tour_ohne_bericht;
			$tourenleiter = get_current_tourenleiter_name($id);
			$datum = make_short_date(get_post_meta($id, 'current_tour_date')[0]);
			$alle_touren_ohne_bericht_html_output .= '<label class="radio">
				<span class="radio__input">
					<input type="radio" id="' . $id . '" value="' . $id . '" name="tour_id" required/>
					<span class="radio__control bordered"></span>
				</span>
				<span class="radio__label">' . $title . ' | ' .  $datum . ' | ' .  $tourenleiter . '</span>
			</label>';		
		endforeach;
		$alle_touren_ohne_bericht_html_output .= "</fieldset>";
		return($alle_touren_ohne_bericht_html_output);
	endif;
}
 
  
/* =============================================================== *\ 
   Formular der Touren-Bericht-Eingabe-Seite 
   - Formular-Verarbeitung
\* =============================================================== */  

  
/* =============================================================== *\ 

 	 Tourenbericht erfassen neu 

\* =============================================================== */ 


/* =============================================================== *\ 
   Tourenbericht erfassen: 
   - Image handling (upload/delete) with Ajax
   - create new post 
\* =============================================================== */ 
function aw_scripts() {	
	wp_register_script( 'aw-custom', get_stylesheet_directory_uri(). '/js/image_uploader.js', array('jquery'), '1.1', true );
	$script_data_array = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'thank_you_url' => get_site_url() . "/form-tourenbericht-thank-you",
	);
	wp_localize_script( 'aw-custom', 'aw', $script_data_array );
	wp_enqueue_script( 'aw-custom' );
}

add_action( 'wp_enqueue_scripts', 'aw_scripts' );
add_action( 'wp_ajax_file_upload', 'file_upload_callback' );
add_action( 'wp_ajax_nopriv_file_upload', 'file_upload_callback' );

function file_upload_callback() {
	require_once( ABSPATH . 'wp-admin/includes/image.php' );     
	require_once( ABSPATH . 'wp-admin/includes/file.php' );     
	require_once( ABSPATH . 'wp-admin/includes/media.php' ); 
	//$my_image = $_FILES["image"];
	$attach_id = media_handle_upload("image", 0);
	$response_html = "";
	$response_html .= wp_get_attachment_image( $attach_id, 'thumbnail');
	$response_html .= '<div class="data_id" data-id="' . $attach_id . '"></div>';
	//echo wp_get_attachment_image( $attach_id, 'thumbnail');
	//echo $attach_id;
	echo $response_html;
	wp_die();
}

/* =============================================================== *\ 
 	 Delete image 
\* =============================================================== */ 
add_action( 'wp_ajax_delete_image', 'delete_image_callback' );
add_action( 'wp_ajax_nopriv_delete_image', 'delete_image_callback' );

function delete_image_callback(){
	wp_delete_attachment( $_POST['id'], true);
	wp_die();

}
 
 
/* =============================================================== *\ 
 	 Add Image-Fieldset
\* =============================================================== */ 
add_action( 'wp_ajax_add_image_fieldset', 'add_image_fieldset_callback' );
add_action( 'wp_ajax_nopriv_add_image_fieldset', 'add_image_fieldset_callback' );

function add_image_fieldset_callback(){
	$new_image_fieldset = '<fieldset class="image_upload_fieldset box_shadow">
		
		<form class="file_upload_form" name="image_form" enctype="multipart/form-data">
			<div class="delete_me" data-id=""><i class="fa-solid fa-circle-xmark"></i></div>
			<div class="form-group dragndrop_galerie">

				<input class="input_file" type="file" name="file" accept="image/*" />
				
				<div class="message_container">
					<div class="choose_an_image_container">
						<svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
						<div class="message"><span class="choose">Bild auswählen</span><br /><span>oder hierher ziehen</span></div>
					</div>
					<div class="response_container"></div>
				</div>
						
			</div>
		</form>
					
		<textarea type="text" name="caption_text" placeholder="Beschreibe hier dein Bild" class="caption_text rounded bordered" required=""></textarea>
	</fieldset>';
	echo $new_image_fieldset;
//	wp_die();

}

  
/* =============================================================== *\ 
 	 Tourenbericht erfassen:
	 Formular einsenden 
\* =============================================================== */ 
add_action( 'wp_ajax_create_new_tourenbericht', 'create_new_tourenbericht_callback' );
add_action( 'wp_ajax_nopriv_create_new_tourenbericht', 'create_new_tourenbericht_callback' );

function create_new_tourenbericht_callback(){
	
	// Timestamp für Block-IDS
	$date = new DateTime();
	$timestamp = $date->getTimestamp();
	
	//Galerie
	$my_gallery_content = "";
	$my_gallery_obj = $_POST['myGalleryObj'];

	// Gallerie array ausmisten (wenn leere Kacheln ohne Bilder abgesendet werden > diese aus Array entfernen)
	$temp_undef_array = array();
	foreach($my_gallery_obj as $key => $value):
		if($key=="undefined"):
			$temp_undef_array[$key] = $value;
		endif;		
	endforeach;

	$my_gallery_obj = array_diff_key($my_gallery_obj, $temp_undef_array);

	
	// Galerie-Head
	if(count($my_gallery_obj)>0):
		$my_gallery_content .= '<!-- wp:acf/slick-slider {
    		"id": "block_6163eea2769022021'. $timestamp . '",
    		"name": "acf\/slick-slider",
    		"data": {';
	
		$img_iterator = 0;
		foreach($my_gallery_obj as $key => $value):
			$my_gallery_content .= '"bilder_slider_' . $img_iterator . '_bild": ' . $key . ',';
	        $my_gallery_content .= '"_bilder_slider_' . $img_iterator . '_bild": "field_615d6febe46fa",';
	        $my_gallery_content .= '"bilder_slider_' . $img_iterator . '_legende_titel": "' . $value . '",';
	        $my_gallery_content .= '"_bilder_slider_' . $img_iterator . '_legende_titel": "field_615d701fe46fb",';
	        $my_gallery_content .= '"bilder_slider_' . $img_iterator . '_legende_autor": " ",';
	        $my_gallery_content .= '"_bilder_slider_' . $img_iterator . '_legnede_autor": "field_615d7044e46fc",';
			$img_iterator++;
		endforeach;
	
		// Galerie-Footer
		$my_gallery_content .= '"bilder_slider": ' . count($my_gallery_obj) . ',
	        "_bilder_slider": "field_615d6fc2e46f9"},
	    	"align": "", "mode": "edit", "wpClassName": "wp-block-acf-slick-slider"} /-->';
	endif;
		
	
	//Tourenbericht
	$my_tourenbericht_content = $_POST["myTourenbericht"];

	
	// Verknüpfung zur Tour
	$my_tour_id = $_POST['myTourID'];
	$my_vorname = $_POST['myVorname'];
	$my_nachname = $_POST['myNachname'];
	$my_telefon = $_POST['myTelefonNr'];
	$my_email = $_POST['myEmail'];
	
	$my_verknuepfung_zur_tour = '<!-- wp:acf/verknuepfung-zur-tour {
    	"id": "block_614db947d5c1778'. $timestamp . '",
    	"name": "acf\/verknuepfung-zur-tour",
    	"data": {';
	
	$my_verknuepfung_zur_tour .= '"verknuepfung-zur-tour": ' . $my_tour_id . ',';
	$my_verknuepfung_zur_tour .= '"_verknuepfung-zur-tour": "field_614db88d2dece",';
	$my_verknuepfung_zur_tour .= '"autorin": "' . $my_vorname . ' ' . $my_nachname . '",';
	$my_verknuepfung_zur_tour .= '"_autorin": "field_614db88d2df0a",';
	$my_verknuepfung_zur_tour .= '"telefon": "' . $my_telefon . '",';
	$my_verknuepfung_zur_tour .= '"_telefon": "field_614db88d2df43",';
	$my_verknuepfung_zur_tour .= '"email": "' . $my_email . '",';
	$my_verknuepfung_zur_tour .= '"_email": "field_614db88d2df7b"';
	
	$my_verknuepfung_zur_tour .= '}, "align": "", "mode": "edit","wpClassName": "wp-block-acf-verknuepfung-zur-tour"} /-->';
	
	//Post-Content zusamamenfügen
	$my_post_content .= $my_gallery_content;
	$my_post_content .= $my_tourenbericht_content;	
	$my_post_content .= $my_verknuepfung_zur_tour;	
	
	$tour_title = get_the_title($my_tour_id);
	$new_post = array(
		'post_title'    => $tour_title,
		'post_content'  => $my_post_content,
		'post_status'   => 'draft',           // Choose: publish, preview, future, draft, etc.
		'post_type' => 'tourenbericht'  // Use a custom post type if you want to
		);
	
	$pid = wp_insert_post($new_post); 
	
	die();
}
/* =============================================================== *\ 
 	 Ende: Tourenbericht erfassen 
\* =============================================================== */ 
  
  
 

  






add_action('init', function() {
  $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
  //echo $url_path;
  if ( $url_path === 'wp-admin/admin-post.php' ) {
     // load the file if exists
     $load = locate_template('page_tourenbericht_erfassen_thank_you.php', true);
     if ($load) {
        exit(); // just exit if template was found and loaded
     }
 }
});



/* =============================================================== *\ 
 	 Aktuelles-Tourdatum vorbereiten für Meta
\* =============================================================== */ 
function get_current_tourdatum_for_meta($post_id){			
	$aktuelles_tourdatum = ""; // wird nachher mit Datum überschrieben
	$block_data = "";
	$blocks = parse_blocks( get_the_content(null, false, $post_id) );
	foreach($blocks as $block){
		if('acf/tourdatum' === $block['blockName']){ //alle Tourdatum-Blöcke durchgehen			
			
			$block_data = $block['attrs']['data']; 
			$aktuelles_tourdatum = $block_data['tourdatum']; // tourdatum, wenn tour_findet_statt oder tour_abgesagt 
			
			$durchfuhrungs_status = $block_data['durchfuhrung']; //tour_findet_statt // tour_verschieben // tour_abgesagt	
			if($durchfuhrungs_status == "tour_verschieben"):
				/*
				["verschiebe_daten_(0)_tour_verschoben_auf"] > wenn array enthält, ist die Checkbox (auf dieses Datum verschieben) gewählt worden
				["verschiebe_daten_(0)_verschiebe_datum"]=> das dazugehörige verschiebe datum
				*/
				foreach($block_data as $key => $value):
					if( ("verschiebe_daten" == substr($key,0,16)) && ("tour_verschoben_auf" == substr($key,-19)) ):
						if($value>0): // wenn ja, dann ist checkbox (auf dieses Datum verschieben) gewählt worden
						
							//iterator herausfinden
							$my_iterator = $key;
							$my_iterator = str_replace("verschiebe_daten_", "", $my_iterator);
							$my_iterator = str_replace("_tour_verschoben_auf", "", $my_iterator);
							
							//das verschiebe datum ist abgelegt unter: verschiebe_daten_0_verschiebe_datum
							$current_verschiebe_datum = "verschiebe_daten_" . $my_iterator . "_verschiebe_datum";
							if(array_key_exists($current_verschiebe_datum,  $block_data)==true):
								$aktuelles_tourdatum = $block_data[$current_verschiebe_datum];
							endif;
							
						endif;
					endif;
				endforeach;
			endif;
		}//block tourdatum
	}//foreach blocks as block

	return $aktuelles_tourdatum;	
}


/* =============================================================== *\ 
 	 Aktueller Tourenleiter-Name 
\* =============================================================== */ 
function get_current_tourenleiter_name($post_id){
	$post_author_id = get_post_field( 'post_author', $post_id );
	$auth_first_name = get_the_author_meta( 'first_name', $post_author_id );
	$auth_last_name = get_the_author_meta( 'last_name', $post_author_id);
	$name_tourenleiter = "$auth_first_name $auth_last_name";
	if(($auth_first_name == "") && ($auth_last_name == "")){
		$name_tourenleiter = get_the_author_meta( 'user_email', $post_author_id);
	}	
	
	// Tourenleiter-Name ggf. überschreiben 
	$blocks = parse_blocks( get_the_content(NULL, false, $post_id) );
	foreach ( $blocks as $block ) {
		if('acf/touren-details' === $block['blockName']):
			if(array_key_exists('anmeldung_und_auskunft_name', $block['attrs']['data'])==true): 
				$name_tourenleiter = $block['attrs']['data']['anmeldung_und_auskunft_name'];
			endif;
		endif;
	}	
	return $name_tourenleiter;
}

/* =============================================================== *\ 
 	Aktuelle Bereiche, wie Sektion, Vetereanen, JO usw. 
\* =============================================================== */   
function get_current_bereiche($post_id){
//	$blocks = parse_blocks( get_the_content($post_id) );
	$blocks = parse_blocks( get_the_content(null, false, $post_id) );

	$my_bereiche= "";
	$my_bereiche_as_array = array();
	foreach ( $blocks as $block ) { 
		if('acf/touren-kurzinfo' === $block['blockName']){
			if((isset($block['attrs']['data']['bereich'])) && ($block['attrs']['data']['bereich']!="")): // Sektion usw.
				$anzahl_bereiche = count($block['attrs']['data']['bereich']);
				$my_iterator = 0;
				foreach($block['attrs']['data']['bereich'] as $my_bereich):
					$my_iterator++;
					if($anzahl_bereiche>$my_iterator):
						$my_bereiche .= $my_bereich . ", ";
					else:
						$my_bereiche .= $my_bereich;
					endif;
					array_push($my_bereiche_as_array, $my_bereich);
				endforeach;
			endif;
		} 
	} 
	return($my_bereiche);
};


/* =============================================================== *\ 
 	 Human-Date
	 Funktion, welche aus einem YYYYMMDD ein d. M. YYYY macht
	 @acf-tourdatum/block.php
\* =============================================================== */ 
function make_human_date($date){
	$format = '%A, %e. %B %Y'; 	// Montag, 02. Januar 1970
	if(is_archive()){
		$format = '%d.%m.%Y'; 	// 01.01.2021
	} 
	
	$timestamp = strtotime($date);
	$human_date = strftime ( $format , $timestamp);		
	return $human_date;
}

function make_short_date($date){
	$format = '%d.%m.%Y'; 	// 01.01.2021
	$timestamp = strtotime($date);
	$short_date = strftime ( $format , $timestamp);		
	return $short_date;
}
function make_year_date($date){
	$format = '%Y'; 	// 2021
	$timestamp = strtotime($date);
	$short_date = strftime ( $format , $timestamp);		
	return $short_date;
}


/* =============================================================== *\ 
 	 Autodraft 
	 @header.php
\* =============================================================== */ 
function set_to_archiv() {
	$the_query = get_posts( array('post_type'=>'touren','post_status'=>'publish,draft,archiv', 'numberposts' => -1));	
	foreach($the_query as $single_post) {
		$id=$single_post->ID;
		$current_tour_date=get_post_meta($id, 'current_tour_date', true );
		$today = date("Ymd");
		if($current_tour_date<$today){
			$update_post = array(
				'ID' 			=> $id,
				'post_status'	=>	'archiv',
				'post_type'	=>	'touren',
				'meta_key' => $current_tour_date,
			);
			wp_update_post($update_post);
		}
	}
}

/* =============================================================== *\ 
 	 Aktuelles: 
	 Seiten mit entsprechendem Seiten-Template zurückgeben 
\* =============================================================== */ 
  

function get_page_by_template($template) {
    $args = array(
        'meta_key' => '_wp_page_template',
        'meta_value' => $template
);
return get_pages($args); 
}




/* =============================================================== *\ 
	 Breadcrumb - Menu
	 @template archive.php
\* =============================================================== */ 
//https://kulturbanause.de/blog/wordpress-breadcrumb-navigation-ohne-plugin/

function nav_breadcrumb() {
	global $post;
	$delimiter = ' <i class="fa-solid fa-chevron-right" style="font-size:66%; vertical-align: middle; padding: 0 5px"></i>';
	$delimiter_two = ' | ';
	$home = 'Home'; 
	$before = '<span class="current-page">'; 
	$after = '</span>'; 
	$my_chip = "";
	$chip_start = "<div class='breadcrumb_item_container'>";
	$chip_end = "</div>";
	$homeLink = get_bloginfo('url');

	$blocks = parse_blocks( get_the_content() );
	foreach ( $blocks as $block ) {              
		if('acf/touren-kurzinfo' === $block['blockName']){
			// Sektion usw.
			if((isset($block['attrs']['data']['bereich'])) && ($block['attrs']['data']['bereich']!="")): // Sektion usw.
				$anzahl_bereiche = count($block['attrs']['data']['bereich']);
				$my_iterator = 0;
				foreach($block['attrs']['data']['bereich'] as $my_bereich):
					$my_iterator++;
					$my_bereich_class=strtolower($my_bereich);
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					$my_url = $homeLink . '/' . $slug['slug'] . '/#filter=.' . $my_bereich_class;
					if($anzahl_bereiche>$my_iterator):
						$my_chip .= '<a href="' . $my_url . '" class="' . $my_bereich_class . '">' . $my_bereich . '</a> <span> | </span> ';
					else:
						$my_chip .= '<a href="' . $my_url . '" class="' . $my_bereich_class . '">' . $my_bereich . '</a>';
					endif;
				endforeach;
			endif;
			// Bergtour T2
			$kurzinfo = render_block($block);
		}		
	}

	if ( !is_home() && !is_front_page() || is_paged() ) {

		echo '<nav class="breadcrumb">';

		if ( is_category()) {
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
			echo $before . single_cat_title('', false) . $after;

		} elseif ( is_day() ) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;

		} elseif ( is_month() ) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;

		} elseif ( is_year() ) {
			echo $before . get_the_time('Y') . $after;

		} elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				//echo "hier";
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				//echo $before . get_the_title() . $after;
				echo $chip_start . $my_chip . $chip_end;
			} else {
				$cat = get_the_category(); $cat = $cat[0];
				echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $before . get_the_title() . $after;
			}

		// archiv		
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			$post_type = get_post_type_object(get_post_type());

		} /*elseif ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
			echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;

		} elseif ( is_page() && !$post->post_parent ) {
			echo $before . get_the_title() . $after;

		} elseif ( is_page() && $post->post_parent ) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;

		} elseif ( is_search() ) {
			echo $before . 'Ergebnisse für Ihre Suche nach "' . get_search_query() . '"' . $after;

		} elseif ( is_tag() ) {
			echo $before . 'Beiträge mit dem Schlagwort "' . single_tag_title('', false) . '"' . $after;

		} elseif ( is_404() ) {
			echo $before . 'Fehler 404' . $after;
		}

		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
			echo ': ' . __('Seite') . ' ' . get_query_var('paged');
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}*/
		echo '</nav>';
	} 
} 
  


/* =============================================================== *\ 
 	 Single-Tour-Details
	 erzeugt HTML-Output mit Icon und Content:
	 div.detail_item (+class)
 	  - div.icon
 	  - div.content 
	  	 
	 @acf-touren-details/block.php
\* =============================================================== */ 

// Icons verfügbar machen 
require_once( get_template_directory()  . '/template_parts/icons_art_der_tour.php'); 

// HTML-Otuput einfache Textfelder 
function single_tour_textfield($label, $content){
	global ${'icon_' . $label}; // $icon_programm	
	$single_tour_textfield_html = "<div class='detail_item bordered " . $label . "'>";
	$single_tour_textfield_html .= "<div class='icon'>" . ${'icon_' . $label} . "</div>";
	$single_tour_textfield_html .= "<div class='content'>" . $content . "</div>";
	$single_tour_textfield_html .= "</div>";
	return $single_tour_textfield_html;
}

// HTML-Otuput Karten
function single_tour_array($label, $content){
	global ${'icon_' . $label}; // $icon_programm
	$single_tour_textfield_html = "<div class='detail_item bordered " . $label . "'>";
	$single_tour_textfield_html .= "<div class='icon'>" . ${'icon_' . $label} . "</div>";
	$single_tour_textfield_html .= "<div class='content'>" . $content . "</div>";
	$single_tour_textfield_html .= "</div>";
	return $single_tour_textfield_html;
}

// HTML-Output Links
function single_tour_linkfield($label, $link_target){
	global ${'icon_' . $label}; // $icon_programm
	
	if(strpos($link_target, "https://www.sac-cas.ch/") !== false):
		$single_tour_textfield_html = "<div class='detail_item bordered " . $label . "'>";
		$single_tour_textfield_html .= "<div class='icon'>" . ${'icon_' . $label} . "</div>";
		$single_tour_textfield_html .= "<div class='content'><a href='" . $link_target . "' target='_blank'>Tour im SAC-Tourenportal</a></div>";
		$single_tour_textfield_html .= "</div>";
	else:
		$single_tour_textfield_html = "";
	endif;
	return $single_tour_textfield_html;
	
}
 // HTML-Output Button (hier Anmelden-Feld)
function single_tour_buttton_field($label, $link_text, $link_url){
	global ${'icon_' . $label}; // $icon_programm
	$single_tour_textfield_html = "<div class='detail_item bordered " . $label . "'>";
	$single_tour_textfield_html .= "<div class='icon'>" . ${'icon_' . $label} . "</div>";
	$single_tour_textfield_html .= "<a href='" . $link_url . "' class='content'>" . $link_text . "</a>";
	$single_tour_textfield_html .= "</div>";
	return $single_tour_textfield_html;
}


/* =============================================================== *\ 
 	 Mails, wenn Tour eingereicht wird
	 - Mails an alle Redakteure
\* =============================================================== */ 
function notify_editors_for_pending( $post ) {

	if ( 'touren' == get_post_type($post) ):
		//Autor des Posts
		$user_info = get_userdata ($post->post_author);
		$user_email = $user_info->user_email;
		$strSubject = $user_info->user_nicename . ' hat die Tour «' . $post->post_title . '» eingereicht';

		//Empfänger
		$touren_admins = get_users( array( 'role__in' => array( 'administrator', 'editor' ) ) );
		$strMessage = "";
		$redakteur = array();
		$headers = array('Content-Type: text/html; charset=UTF-8');

		foreach ( $touren_admins as $touren_admin ) {
			$redakteur['email'] = $touren_admin->user_email;
			$redakteur['nicename'] = $touren_admin->user_nicename;
			$strMessage  = "Hallo <strong>" . $redakteur['nicename'] . "</strong><br /><br />";
			$strMessage .= $user_info->user_nicename . ' hat die Tour <strong>&laquo;' . $post->post_title . '&raquo;</strong> eingereicht. <br/>';
			$strMessage .= "<a href=" . get_edit_post_link( $post->ID, $context ) . ">Hier</a> geht es zur Tour: " . get_edit_post_link( $post->ID, $context ) . "<br/><br/>";
			$strMessage .= "Bei Fragen oder Unklarheiten erreichst Du den/die Verfasser/in über folgende Mail-Adresse: <a href='mailto:'" . $user_email . "'> " . $user_email . "</a><br/><br/>";
			$strMessage .= "Wenn alles in Ordnung ist, kannst Du die Tour veröffentlichen, der/die Verfasser/in wird darüber automatisch informiert. <br/><br/>";
			$strMessage .= "Vielen Dank für deine wertvolle Mitarbeit!<br/><br/>";
			$strMessage .= "SAC Sektion-Mythen <br/>";
			$strMessage .= "<i>Gesendet vom Webseiten-Käfer</i> <br/>";
		
			wp_mail($redakteur['email'], $strSubject, $strMessage, $headers);
		}
	endif;
}
/*
add_action( 'new_to_pending', 'notify_editors_for_pending');
add_action( 'draft_to_pending', 'notify_editors_for_pending' );
add_action( 'auto-draft_to_pending', 'notify_editors_for_pending' );
add_action( 'pending_to_pending', 'notify_editors_for_pending' );
*/

// Mail an Verfasser
function notify_author_for_pending($post){
	$user_info = get_userdata ($post->post_author);
	$user_email = $user_info->user_email;
	$user_name = esc_html($user_info->user_nicename);
	$strSubject = 'Die Tour «' . $post->post_title . '» wurde eingereicht';
	$headers = array('Content-Type: text/html; charset=UTF-8');

	$strMessage = 'Hallo <strong>' . $user_name . '</strong><br /><br />';
	$strMessage .= 'Die Touren-Chefs haben Deine Tour <strong>«' . $post->post_title . '»</strong> erhalten.<br /><br />';
	$strMessage .= 'Sollte der zuständige Touren-Chef noch Fragen an Dich haben, wird er direkt mit Dir Kontakt aufnehmen.<br /><br />';
	$strMessage .= 'Wenn Deine Ausschreibung in Ordnung ist, wird diese veröffentlicht.<br /> ';
	$strMessage .= 'Selbstverständlich wirst Du darüber ebenfalls per Mail benachrichtigt.<br /><br />';
	$strMessage .= "Wir danken Dir herzlich für Deine wertvolle Mitarbeit!<br/><br/>";
	$strMessage .= "SAC Sektion-Mythen <br/>";
	$strMessage .= "<i>diese Nachricht wurde automatisch generiert</i> <br/>";
	wp_mail($user_email, $strSubject, $strMessage, $headers);
}
/*
add_action( 'new_to_pending', 'notify_author_for_pending');
add_action( 'draft_to_pending', 'notify_author_for_pending' );
add_action( 'auto-draft_to_pending', 'notify_author_for_pending' );
add_action( 'pending_to_pending', 'notify_author_for_pending' );
*/
//Mail beim Veröffentlichen


/* =============================================================== *\ 
 	 Tourenportal: Platzhalter für Titel anpassen
\* =============================================================== */ 
function wpb_change_title_text( $title ){
	$screen = get_current_screen();
	
	if( 'touren' == $screen->post_type ) {
		$title = 'Hier Touren-Titel eintragen';
	}
	
	return $title;
}

add_filter( 'enter_title_here', 'wpb_change_title_text' );


/* =============================================================== *\ 
   
   Button-Line
   Erzeugt Archiv- und Download Buttons
   
\* =============================================================== */ 
function make_button_aktuelle_touren(){
	$link_target = get_post_type_archive_link("touren");
	$my_button_html = '<a class="more_button animated_button white go_green extreme_rounded centered section_button margin_10" href="' . $link_target  . '">' . LABEL_AKTUELLE_TOUREN .'</a>';
	return($my_button_html); 
}

function make_button_touren_bericht_archiv(){
	$link_target = get_post_type_archive_link("tourenbericht");
	$my_button_html = '<a class="more_button animated_button white go_green extreme_rounded centered section_button margin_10" href="' . $link_target . '">' . LABEL_TOUREN_BERICHTE . '</a>';
	return($my_button_html);	
}

function make_button_touren_archiv(){
	$page_ID_archive_touren_archive = ""; 
	$page_template_archive_touren_archive = 'archive-touren-archive.php';
	//Page-ID über Page-Template ermitteln
	$page_id = get_page_id_from_page_template($page_template_archive_touren_archive);
	$link_target = get_permalink($page_id);
	$my_button_html = '<a class="more_button animated_button white go_green extreme_rounded centered section_button margin_10" href="' . $link_target . '">' . LABEL_TOUREN_ARCHIV .'</a>';
	return($my_button_html);	
}

function make_button_jahresprogramme(){
	$download_list = "";
	$downloads_arr = array();	
	$page_template_touren_downloads = "touren-downloads.php";
	//Page-ID über Page-Template ermitteln
	$page_id = get_page_id_from_page_template($page_template_touren_downloads);
	
	
	//Alle Downloads von dieser Seite holen
	$blocks = parse_blocks(get_the_content(NULL, false, $page_id));
	foreach($blocks as $block):
		if("acf/downloads" == $block['blockName']):
			$media_id = $block['attrs']['data']['downloads'];
			array_push($downloads_arr, $media_id);
		endif;
	endforeach;
	
	// HTML für Downloads-Liste erstellen
	foreach($downloads_arr as $item):
		$link_target = wp_get_attachment_url($item);
		$link_title = get_the_title($item);
		$download_list .= '<li><a href="' . $link_target . '">' . $link_title . '</a></li>';
	endforeach;
	
	// HTML zusammensetzen
	$my_button_html = '<div class="drop_down_button white go_green extreme_rounded section_button centered">';
	$my_button_html .= '<div class="more_button margin_10">' . LABEL_JAHRESPROGRAMME . ' <i style="margin-left:5px" class="fas fa-chevron-right"></i> </div>';
	$my_button_html .= '<ul>' . $download_list . '</ul>';
	$my_button_html .= '</div>';
	return($my_button_html);
}

function get_page_id_from_page_template($template_name){
	$all_pages = get_pages();
	foreach($all_pages as $page):
		if($template_name == get_post_meta($page->ID,'_wp_page_template',true)):
			$page_ID = $page->ID;
		endif;
	endforeach;	
	return($page_ID);
}

/* =============================================================== *\ 


 	 Customize Backend for Customer 


\* =============================================================== */ 

/* =============================================================== *\ 
   Gewisse Seiten im Wordpress-Admin-Bereich ausblenden 
   - per Kategorie
   - per Page-Template 
   - per URL-Titelform (was bei Permalink angegeben werden kann)
\* =============================================================== */ 

$page_template_array = array('archive-touren-archive.php', 'page-aktuell-archive.php', 'page_tourenbericht_erfassen.php');
$url_title_array = array('form-tourenbericht-thank-you');
$cat_slug_array = array('only-for-admin');
$cat_id_array = array(); //kann hier befüllt werden, die cat_slugs werden automatisch hinzugefügt.

foreach($cat_slug_array as $cat):	
  $idObj = get_category_by_slug( $cat );
  if ( $idObj instanceof WP_Term ) {
	  $id = $idObj->term_id;
	  array_push($cat_id_array, $id);
  }
endforeach;

if(is_my_super_admin()== false):
  add_filter( 'parse_query', 'exclude_pages_from_admin' );
endif;
function exclude_pages_from_admin($query) {
  global $pagenow, $post_type, $page_template_array, $url_title_array, $cat_slug_array;
  
  $page_IDs_array = array(); //In diesem Array werden dann alle ID's gesammelt
		  
	  if (is_admin() && $pagenow=='edit.php' && $post_type =='page') {
	  $all_pages = get_pages();
	  foreach($all_pages as $page):
		  
		  // Hide Page per Template 
		  foreach($page_template_array as $page_template):
			  if($page_template == get_post_meta($page->ID,'_wp_page_template',true) ):
				  array_push($page_IDs_array, $page->ID);
			  endif;
		  endforeach;

		  // Hide Page per URL-Titelform
		  foreach($url_title_array as $my_url):
			  if($my_url == $page->post_name):
				  array_push($page_IDs_array, $page->ID);
			  endif;
		  endforeach;

		  // Hide Page per Category
		  foreach($cat_slug_array as $cat):
			  if(has_category($cat, $page)):
				  array_push($page_IDs_array, $page->ID);
			  endif;				
		  endforeach;
		  
	  endforeach;
		  
	  $page_IDs_array = array_unique($page_IDs_array);
	  //Seiten ausblenden
	  $query->query_vars['post__not_in'] = $page_IDs_array;
	  }
}

/* =============================================================== *\ 
   Category hide 
   //https://wordpress.org/support/topic/hide-some-categories-in-post-editor/
\* =============================================================== */ 
if(is_my_super_admin()== false):
  add_filter( 'list_terms_exclusions', 'hide_categories_for_specific_user', 10, 2 );
endif;

function hide_categories_for_specific_user( $exclusions, $args ){
  global $cat_id_array;
  $exterms = wp_parse_id_list( $cat_id_array );
	  foreach ( $exterms as $exterm ):
	  if ( empty($exclusions) ):
		  $exclusions = ' AND ( t.term_id <> ' . intval($exterm) . ' ';
	  else:
		  $exclusions .= ' AND t.term_id <> ' . intval($exterm) . ' ';
	  endif;
   endforeach;
   if ( !empty($exclusions) )
	   $exclusions .= ')';
   return $exclusions;  
}

// aus Kategorien-Auswahl entfernen
if(is_my_super_admin()== false):
  add_action( 'admin_head-post.php', 'hide_categories_by_css' );
  add_action( 'admin_head-post-new.php', 'hide_categories_by_css' );
endif;

function hide_categories_by_css() { 
  global $cat_id_array;
  $hide_style = "";
  foreach ($cat_id_array as $my_cat_id):
	  $hide_style .= "#editor-post-taxonomies-hierarchical-term-" . $my_cat_id . ",";
	  $hide_style .= "label[for='editor-post-taxonomies-hierarchical-term-" . $my_cat_id . "']{display:none}";
  endforeach; ?>
  
  <style type="text/css">
	  <?php echo $hide_style; ?>
  </style>
  <?php
}


/* =============================================================== *\ 
 	 Den Autoren nur die eigenen Beiträge im Backend anzeigen 
\* =============================================================== */ 
add_action('pre_get_posts', 'query_set_only_author' );
function query_set_only_author( $wp_query ) {
	global $current_user;
	if( is_admin() && !current_user_can('edit_others_posts') ) {
		$wp_query->set( 'author', $current_user->ID );
		add_filter('views_edit-post', 'fix_post_counts');
		add_filter('views_upload', 'fix_media_counts');
	}
}

/* =============================================================== *\ 
 	 Restrict Blocks by user roles
\* =============================================================== */ 
//https://www.role-editor.com/forums/topic/restrict-hide-gutenberg-blocks-by-role/
add_filter( 'allowed_block_types_all', 'misha_allowed_block_types', 10, 2 );
 
function misha_allowed_block_types( $allowed_blocks, $post ) {
    $user = wp_get_current_user();
    
    if ( in_array('administrator', $user->roles ) ) { // Do not change the list of default blocks for user with administrator role
        return $allowed_blocks;
    }

    if ( in_array('author', $user->roles ) ) {
        $allowed_blocks = array(
			'acf/tourdatum',
			'acf/touren-details',
			'acf/touren-kurzinfo',
            'core/image',
            'core/paragraph',
            'core/heading',
            'core/list'
        );    
        return $allowed_blocks;
    }
	  
	return $allowed_blocks;
} 

/* =============================================================== *\ 
 	 Admin-Columns anpassen 
	 + Touren-Leiter
	 + Tour-Datum
	 + Bereich
	 //https://www.smashingmagazine.com/2017/12/customizing-admin-columns-wordpress/
\* =============================================================== */ 
add_filter( 'manage_touren_posts_columns', 'touren_columns' );
function touren_columns( $columns ) {
	$columns = array(
		'cb'          => 'cb',
		'title' => 'Title',
		'tourenleiter' => 'Tourenleiter',
		'current_tour_date' => 'Tour-Datum',
		'bereiche' => 'Bereich',
	);
	return $columns;
}

// Inhalte aus den Meta-Keys in die Kolonnen hinzufügen
add_action( 'manage_touren_posts_custom_column', 'touren_custom_column', 10, 2);
function touren_custom_column( $column, $post_id ) {
	if('tourenleiter' === $column){
		echo get_current_tourenleiter_name($post_id);
	}
	if('current_tour_date' === $column){
		echo make_human_date(get_post_meta($post_id, 'current_tour_date', true));
	}
	if('bereiche' === $column){
		echo get_post_meta($post_id, 'bereiche', true);
	}
}

// Kolonnen sortierbar machen
add_filter( 'manage_edit-touren_sortable_columns', 'touren_sortable_columns');
function touren_sortable_columns( $columns ) {
	$columns['current_tour_date'] = 'current_tour_date';
	$columns['tourenleiter'] = 'tourenleiter_name';
	$columns['bereiche'] = 'bereiche';
  	return $columns;
}

add_action( 'pre_get_posts', 'touren_posts_orderby' );
function touren_posts_orderby( $query ) {
	if( ! is_admin() || ! $query->is_main_query() ) { return; }
	
	$orderby = $query->get( 'orderby');
   	if( 'current_tour_date' == $orderby ) {
		$query->set('meta_key','current_tour_date');
		$query->set('orderby','meta_value');
	}elseif('tourenleiter_name'==$orderby){
		$query->set('meta_key','tourenleiter_name');
		$query->set('orderby','meta_value');
	}elseif('bereiche'==$orderby){
		$query->set('meta_key','bereiche');
		$query->set('orderby','meta_value');
	}
}

/* =============================================================== *\ 
 	 Autodraft 
	 @header.php
\* =============================================================== */ 
function set_to_draft() {
	//if(!is_admin()):
		$the_query = get_posts( array('post_type'=>'touren','post_status'=>'publish,draft', 'numberposts' => -1));	
		foreach($the_query as $single_post) {
			$id=$single_post->ID;
			$current_tour_date=get_post_meta($id, 'current_tour_date', true );
			//echo $ad_close_date . "<br />";
			$today = date("Ymd");
			//echo $today . "<br />";
			if($current_tour_date!=''){
				if($current_tour_date<$today){
					$update_post = array(
						'ID' 			=> $id,
						'post_status'	=>	'draft',
						'post_type'	=>	'touren',
						'meta_key' => $current_tour_date,
					);
					wp_update_post($update_post);
				}else{
					//echo "id: " . $id . "tour-date: " .  $current_tour_date . "<br />";
				}	
			}
		}
	//endif;
}

/* =============================================================== *\ 

 	 Touren-Eingabe 

\* =============================================================== */ 
  require_once('functions_tour_erfassen.php');
