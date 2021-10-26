<?php
/*
Plugin Name: Duplicate PP
Description: <strong>Duplicate PP</strong> is a simple plugin which allows you to duplicate any POST,PAGE and CPT Easily. The duplicated POST or PAGE CPT acts as draft.
Author: Zakaria Binsaifullah
Author URI: https://about.me/zakaria_binsaifullah
Version: 3.3.5
Text Domain: duplicate-pp
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:  /languages
*/

if (!defined('ABSPATH')) {
	echo "Go Back!!";
	exit();
}

// Duplicate Function

function dpp_duplicate_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'dpp_duplicate_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post for duplicating!');
	}

	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
		return;

	/*
	 * get the original post id
	 */
	$dpp_post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
	 * and all the original post data then
	 */
	$dpp_post = get_post( $dpp_post_id );

	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$dpp_current_user = wp_get_current_user();
	$dpp_new_post_author = $dpp_current_user->ID;

	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $dpp_post ) && $dpp_post != null) {

		/*
		 * new post data array
		 */
		$dpp_args = array(
			'comment_status' => $dpp_post->comment_status,
			'ping_status'    => $dpp_post->ping_status,
			'post_author'    => $dpp_new_post_author,
			'post_content'   => $dpp_post->post_content,
			'post_excerpt'   => $dpp_post->post_excerpt,
			'post_name'      => $dpp_post->post_name,
			'post_parent'    => $dpp_post->post_parent,
			'post_password'  => $dpp_post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $dpp_post->post_title,
			'post_type'      => $dpp_post->post_type,
			'to_ping'        => $dpp_post->to_ping,
			'menu_order'     => $dpp_post->menu_order
		);

		/*
		 * insert the post by wp_insert_post() function
		 */
		$dpp_new_post_id = wp_insert_post( $dpp_args );

		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$dpp_taxonomies = get_object_taxonomies($dpp_post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($dpp_taxonomies as $ddp_taxonomy) {
			$dpp_post_terms = wp_get_object_terms($dpp_post_id, $ddp_taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($dpp_new_post_id, $dpp_post_terms, $ddp_taxonomy, false);
		}

		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$dpp_post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$dpp_post_id");
		if (count($dpp_post_meta_infos)!=0) {
			$dpp_sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($dpp_post_meta_infos as $dpp_meta_info) {
				$dpp_meta_key = $dpp_meta_info->meta_key;
				if( $dpp_meta_key == '_wp_old_slug' ) continue;
				$dpp_meta_value = addslashes($dpp_meta_info->meta_value);
				$dpp_sql_query_sel[]= "SELECT $dpp_new_post_id, '$dpp_meta_key', '$dpp_meta_value'";
			}
			$dpp_sql_query.= implode(" UNION ALL ", $dpp_sql_query_sel);
			$wpdb->query($dpp_sql_query);
		}


		/*
		 * finally, redirect to the edit post screen for the new draft
		 */

		$dpp_all_post_types = get_post_types([],'names');

		foreach ($dpp_all_post_types as $dpp_key=>$dpp_value) {
			$dpp_names[] = $dpp_key;
		}

		$dpp_current_post_type=  get_post_type($dpp_post_id);

		if (is_array($dpp_names) && in_array($dpp_current_post_type, $dpp_names)) {
			wp_redirect( admin_url( 'edit.php?post_type='.$dpp_current_post_type) );
		}

		exit;
	} else {
		wp_die('Failed. Not Found Post: ' . $dpp_post_id);
	}
}
add_action( 'admin_action_dpp_duplicate_as_draft', 'dpp_duplicate_as_draft' );

/*
 * Add the duplicate link to action list for post_row_actions
 */
function dpp_duplicate_link( $actions, $post ) {
	if (current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=dpp_duplicate_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate it" rel="permalink">Duplicate</a>';
	}
	return $actions;
}

add_filter( 'post_row_actions', 'dpp_duplicate_link', 10, 2 );

add_filter('page_row_actions', 'dpp_duplicate_link', 10, 2);

/**
 * Admin Bar Duplicate Link
 */
function dpp_admin_bar_duplicate_link($wp_admin_bar) {

	$post_id = get_the_ID();
	if (is_singular()) {
		$dpp_url = wp_nonce_url(site_url().'/wp-admin/admin.php?action=dpp_duplicate_as_draft&post=' . $post_id, basename(__FILE__), 'duplicate_nonce' );
	    $args = array(
			'id'    => 'ddp_duplicate_link',
			'title' => __( 'Duplicate It','duplicate-pp' ), 
			'href'  => $dpp_url,
	    );
	    $wp_admin_bar->add_node($args);
	}
}
add_action( 'admin_bar_menu', 'dpp_admin_bar_duplicate_link',999 );

/**	 
 * Admin CSS
*/
function dpp_admin_css_load($screen) {
	if( 'tools_page_duplicate-pp' == $screen ) {
		wp_enqueue_style( 'dpp-admin', plugins_url('admin-css/dpp-admin.css', __FILE__ ) );
	}
}
add_action( 'admin_enqueue_scripts', 'dpp_admin_css_load' );

/**
 * Plugin Support Link
 */

function dpp_settings_link( $links ) {
	$gts_settings = array(
		'<a href="'. esc_url( 'https://makegutenblock.com/contact/' ) .'" target="_blank" style="color: green; font-weight: bold">Get Support</a>',
		'<a href="'. esc_url( 'https://wordpress.org/plugins/duplicate-pp/#reviews' ) .'" target="_blank" style="color: green; font-weight: bold">Rate Plugin</a>',
	);
	return array_merge( $gts_settings, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'dpp_settings_link' );


/**	 
 * Admin Support Page
*/
function dpp_admin_support_page() {
	add_submenu_page('tools.php', __('Duplicate PP','duplicate-pp'), __('Duplicate PP','duplicate-pp'), 'manage_options', 'duplicate-pp', 'dpp_admin_page_callback');
}
add_action( 'admin_menu', 'dpp_admin_support_page' );

function dpp_admin_page_callback() {
	?>
		<div class="dpp_admin_page">
			<div class="ddp_head">
				<h2> <?php echo __( 'Thanks for using Duplicate PP','duplicate-pp' ); ?> </h2>
			</div>
			<div class="dpp_body">
				<div class="dpp_left_body">
					<p><b>Duplicate PP</b> is a simple and light-weight plugin which allows you to duplicate any <b>Post</b>,<b>Page</b> and Any <b>Custom Post Type</b> Easily. The duplicated Post or Page or CPT acts as draft. You can either duplicate the post, page or any custom post type using dashboard at the backend or from the single post view at the frontend. </p>
					<h3><?php echo __( 'Included Features','duplicate-pp' ); ?></h3>
					<ul>
						<li>Duplicate POST</li>
						<li>Duplicate PAGE</li>
						<li>Duplicate Any Custom Post Type</li>
						<li>Duplicate from Backend</li>
						<li>Duplicate from Frontend (Single Post View)</li>
					</ul>
					<h3><?php echo __( 'Duplicate PP in Action','duplicate-pp' ); ?></h3>
					<div class="dpp_action_img">
						<img src=<?php echo plugin_dir_url(__FILE__); ?>/img/dpp.jpg>
						<img src=<?php echo plugin_dir_url(__FILE__); ?>/img/dpp-editor.jpg>
					</div>
				</div>
				<div class="dpp_right_sidebar">
					<div class="dpp_support_blocks">
						<div class="single-block">
							<div class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
							</div>
							<div class="help_link">
							<span><?php echo __( 'Hire Me On', 'duplicate-pp' ); ?>
								
							</span><?php echo '<a href="https://www.fiverr.com/devs_zak" target="_blank">'.__('Fiverr','duplicate-pp').'</a>'; ?>
							</span><?php echo '<a href="https://www.upwork.com/freelancers/~010af183b3205dc627" target="_blank">'.__('UpWork','duplicate-pp').'</a>'; ?>
							</div>
						</div>
						<div class="single-block">
							<div class="icon">
								<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/></svg>
							</div>
							<div class="help_link">
							<span><?php echo __( 'Have a Project for Me?', 'duplicate-pp' ); ?></span>
							<?php echo '<a href="https://makegutenblock.com/contact/" target="_blank">'.__('Contact Me','duplicate-pp').'</a>'; ?>
							</div>
						</div>
						<div class="single-block">
							<div class="icon">
								<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/></svg>
							</div>
							<div class="help_link">
							<span><?php echo __( 'Like this plugin?', 'duplicate-pp' ); ?></span>
							<?php echo '<a href="https://wordpress.org/plugins/duplicate-pp/#reviews" target="_blank">'.__('Leave a Positive Review','duplicate-pp').'</a>'; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php 
}

/*
* Redirecting
*/
function dpp_user_redirecting( $plugin ) {
	if( plugin_basename(__FILE__) == $plugin ){
		wp_redirect( admin_url( 'tools.php?page=duplicate-pp' ) );
		die();
	}
}
add_action( 'activated_plugin', 'dpp_user_redirecting' );
