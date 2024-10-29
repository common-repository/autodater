<?php
// Prevent direct file access
  if ( ! defined('WP_UNINSTALL_PLUGIN') ) 
 {

    die();
	
  }

// autodater uninstall function. 
  function autodater_uninstaller()
{

  $option_cat = get_option( 'autodater_category' );

   if( $option_cat != '' ) {
	   
   $args = array(
                 'category_name' => $option_cat
               );
			   
    $post_list = get_posts( $args );
    $posts = array();
	
    foreach( $post_list as $post )
   { 
   
      $posts[]=$post->ID; 
	  
   }
	
   for( $x=0; $x<count($posts); $x++ )
   {
	   
     delete_post_meta($posts[$x],'autodater'); 
	 
   }
}

   $arr=array(
              'stamp',
              'category',
              'status',
              'date_format'
             );
			 
   for( $x=0; $x<count($arr); $x++ )
   {
	   
   delete_option( 'autodater_'.$arr[$x] );
   
   }
}

 autodater_uninstaller();
?>
 