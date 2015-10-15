<?php     namespace ng_navgoco;

/*
Plugin Name: Navgoco Vertical Multilevel Slide Menu
Plugin URI: http://wpbeaches.com/
Description: Using Navgoco Vertical Multilevel Slide Menu in WordPress
Author: Neil Gee
Version: 1.0.0
Author URI: http://wpbeaches.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: navgoco-menu
Domain Path: /languages/
*/


  // If called direct, refuse
  if ( ! defined( 'ABSPATH' ) ) {
          die;
  }

/* Assign global variables */

$plugin_url = WP_PLUGIN_URL . '/navgoco';
$options = array();

/**
 * Register our text domain.
 *
 * @since 1.0.0
 */


function load_textdomain() {
  load_plugin_textdomain( 'navgoco-menu', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_textdomain' );

/**
 * Register and Enqueue Scripts and Styles
 *
 * @since 1.0.0
 */

//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued
function scripts_styles() {

$options = get_option( 'navgoco_settings' );

  wp_register_script ( 'navgocojs' , plugins_url( '/js/jquery.navgoco.js',  __FILE__ ), array( 'jquery' ), '0.2.1', false );
  wp_register_style ( 'navgococss' , plugins_url( '/css/navgoco.css',  __FILE__ ), '' , '0.2.1', 'all' );
  wp_register_script ( 'navgoco-init' , plugins_url( '/js/navgoco-init.js',  __FILE__ ), array( 'navgocojs' ), '1.0.0', false );
  wp_register_style ( 'fontawesome' , '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', '' , '4.4.0', 'all' );


  wp_enqueue_script( 'navgocojs' );
  wp_enqueue_style( 'navgococss' );
  wp_enqueue_style( 'fontawesome' );

     $data = array (

      'ng_navgo' => array(
          
          'ng_menu_selection'  => esc_html($options['ng_menu_selection']),
          'ng_menu_accordion'  => (bool)$options['ng_menu_accordion'],
          'ng_menu_html_carat'  => esc_html($options['ng_menu_html_carat']),
          

      ),
  );

    // Pass PHP variables to jQuery script
    wp_localize_script( 'navgoco-init', 'navgocoVars', $data );

    wp_enqueue_script( 'navgoco-init' );
  
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts_styles' );

/**
 * Register our option fields
 *
 * @since 1.0.0
 */

function plugin_settings(){
  register_Setting(
        'ng_settings_group', //option name
        'navgoco_settings',// option group setting name and option name
        __NAMESPACE__ . '\\navgoco_validate_input' //sanitize the inputs
  );

  add_settings_section(
        'ng_navgoco_section', //declare the section id
        'Navgoco Settings', //page title
         __NAMESPACE__ . '\\ng_navgoco_section_callback', //callback function below
        'navgoco' //page that it appears on

    );

  add_settings_field(
        'ng_menu_selection', //unique id of field
        'Add Menu ID', //title
         __NAMESPACE__ . '\\ng_menu_id_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_menu_accordion', //unique id of field
        'Accordion Effect', //title
         __NAMESPACE__ . '\\ng_menu_accordion_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );

   add_settings_field(
        'ng_menu_html_carat', //unique id of field
        'HTML Carat', //title
         __NAMESPACE__ . '\\ng_menu_html_carat_callback', //callback function below
        'navgoco', //page that it appears on
        'ng_navgoco_section' //settings section declared in add_settings_section
    );
}
add_action('admin_init', __NAMESPACE__ . '\\plugin_settings');

/**
 * Sanitize our inputs
 *
 * @since 1.0.0
 */

function navgoco_validate_input( $input ) {
  // Create our array for storing the validated options
    $output = array();
     
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
         
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
         
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
             
        } // end if
         
    } // end foreach
     
    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'navgoco_validate_input' , $output, $input );
}

function ng_navgoco_section_callback() {

}

/**
 * Register Menu ID to use as Navgoco Menu
 *
 * @since 1.0.0
 */

function ng_menu_id_callback() {
$options = get_option( 'navgoco_settings' ); 

if( !isset( $options['ng_menu_selection'] ) ) $options['ng_menu_selection'] = '';


echo '<input type="text" id="ng_menu_selection" name="navgoco_settings[ng_menu_selection]" value="' . sanitize_text_field($options['ng_menu_selection']) . '" placeholder="Add Menu ID to use as Navgoco Vertical Menu">';
echo '<label for="ng_menu_selection">' . esc_attr_e( 'Add Menu ID to use as Navgoco Vertical Menu','navgoco') . '</label>';
}

/**
 *  Menu Accordion
 *
 * @since 1.0.0
 */

function ng_menu_accordion_callback() {
$options = get_option( 'navgoco_settings' ); 

//if( !isset( $options['ng_menu_accordion'] ) ) $options['ng_menu_accordion'] = 0;


  echo'<input type="checkbox" id="ng_menu_accordion" name="navgoco_settings[ng_menu_accordion]" value="1"' . checked( 1, $options['ng_menu_accordion'], false ) . '/>';
  echo'<label for="ng_menu_accordion">' . esc_attr_e( 'Check to enable Accordion effect on menu, (closes menu item when you open a new one)','navgoco') . '</label>';

}

/**
 * Insert HTML for carat
 *
 * @since 1.0.0
 */

function ng_menu_html_carat_callback() {
$options = get_option( 'navgoco_settings' ); 

if( !isset( $options['ng_menu_html_carat'] ) ) $options['ng_menu_html_carat'] = '';

echo '<input type="text" id="ng_menu_html_carat" name="navgoco_settings[ng_menu_html_carat]" value="' . $options['ng_menu_html_carat'] . '" placeholder="Add custom HTML mark up">';
echo '<label for="ng_menu_html_carat">' . esc_attr_e( 'Insert additional HTML for the dropdown Carat','navgoco') . '</label>';
}


/**
 * Create the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_page() {

    /*
     * Use the add options_page function
     * add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
     */

     add_options_page(
        __( 'Navgoco Options Plugin','navgoco' ), //$page_title
        __( 'Navgoco Menu', 'navgoco' ), //$menu_title
        'manage_options', //$capability
        'navgoco', //$menu-slug
        __NAMESPACE__ . '\\plugin_options_page' //$function
      );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\plugin_page' );

/**
 * Include the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_options_page() {

    if( !current_user_can( 'manage_options' ) ) {

      wp_die( "Hall and Oates 'Say No Go'" );
    }

   require( 'inc/options-page-wrapper.php' );
}