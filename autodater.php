<?php
/*
Plugin Name: AutoDater
Plugin URI:  http://krnmsaikrishna.wordpress.com/plugins/autodater
Description: AutoDater is simple and flexible plugin for you updating your post titles daily with specified date or time sting.If you are waiting for dynamic date on post title, this plugin is the best option to our need.
Version:     2.0
Author:      Krnm Saikrishna
Author URI:  https:/krnmsaikrishna.wordpress.com
License:     GPL2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: autodater
Domain Path: /languages/

AUTODATER is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
AUTODATER is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with AUTODATER. If not, see http://www.gnu.org/licenses/gpl-2.0.html .
*/

// Prevent direct access
  if ( ! defined( 'ABSPATH' ) ) {
	  
     exit();
	 
   }

define ( 'AUTODATER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
  include( AUTODATER_PLUGIN_PATH . 'settings.php' );
    include( AUTODATER_PLUGIN_PATH . 'functions.php' );

   function autodater_styles_scripts ( $hook ) 
  {
	  
     if( $hook != 'toplevel_page_autodater' ) {
		 
		 return;
		 
      } 
	  
  wp_enqueue_style( 'autodater_styles', plugins_url('css/styles.css', __FILE__) );  
  }
  
  
 add_action( 'admin_enqueue_scripts', 'autodater_styles_scripts' ); 



// addding a plugin action links to plugin list block.
add_filter( 'plugin_action_links', 'autodater_action_links',10,5);
  
   function autodater_action_links( $actions, $plugin_file ) 
 {
	static $plugin;
	
	if ( ! isset($plugin) ) {
		
	   $plugin = plugin_basename(__FILE__);

	  }
	if ( $plugin == $plugin_file ) {
		
	     $settings = array( 'settings' => '<a href="options-general.php?page=autodater">Settings</a>' );
	      $site_link = array( 'support' => '<a href="https://krnmsaikrishna.wordpress.com/plugins/autodater" target="_blank">Support</a>' );
		
    	   $actions = array_merge($settings, $actions);
			$actions = array_merge($site_link, $actions);
			
		}
	      return $actions;
  }


 add_action( 'init' , 'autodater_init' );

  function autodater_init()
 {
	 // checking autodater active or not and category & date format options not empty.
	 $pos = get_option('autodater_position');
	   $cat = get_option('autodater_category');
	    $dat = get_option('autodater_date_format');
	     $stam = get_option('autodater_stamp');
             $stat = get_option('autodater_status');
	 
   if( $pos != '' && $cat != '' && $dat != '' && $stat != '' && $stat != 0 && $stam != date( 'j M Y' ) ) {
	   
        my_autodater();
		
    } 
 }
?>