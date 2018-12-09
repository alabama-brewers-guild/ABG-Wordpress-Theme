<?php

add_filter( 'widget_meta_poweredby', '__return_empty_string' );

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

function custom_my_account_menu_items( $items ) {
    return array();
}
add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items' );


// Remove admin bar for non-admins
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
	  show_admin_bar(false);
	}
}

// Stop redirecting subscribers to wooCommerce account from profile
add_filter( 'woocommerce_prevent_admin_access', '__return_false' );
function my_account_permalink($permalink) {

    return admin_url();
}
add_filter( 'woocommerce_get_myaccount_page_permalink', 'my_account_permalink', 1);

// Remove Dashboard link for all but subscribers
add_action( 'admin_menu', 'remove_dashboard' );
function remove_dashboard(){
	if ( !current_user_can('delete_others_posts') ) {
    	remove_menu_page( 'index.php' ); //dashboard
    }
}


// Add display name to menu
add_filter('wp_nav_menu', 'do_menu_shortcodes'); 
function do_menu_shortcodes( $menu ){ 
	return do_shortcode( $menu ); 
} 	
add_shortcode( 'current-username' , 'username_on_menu' );
function username_on_menu(){
	$user = wp_get_current_user();
    return '<span style="font-style:italic; font-weight: normal; display: block; font-size: 10px;">' . $user->display_name . "</span><span>Profile</span>";
}

// Custom link on login page
add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
    return 'https://alabamabrewers.org';
}

// Login redirect
function my_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for subscribers
        if (in_array('subscriber', $user->roles)) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to =  home_url();
        }
    }

    return $redirect_to;
}
add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );

// Lostpassword page
add_filter( 'lostpassword_url', 'my_lost_password_page', 100, 2 );
function my_lost_password_page( $lostpassword_url, $redirect ) {
    return 'https://alabamabrewers.org/wp-login.php?action=lostpassword';
}