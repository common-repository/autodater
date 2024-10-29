<?php

// Prevent direct file access
  if ( ! defined( 'ABSPATH' ) ) {
	
    exit();
  
  }

 function my_autodater()
 {
	 if( is_admin() ) {
		 
          return; 
		  
      }

// getting plugin customization optons.
   $option_name = 'autodater_stamp';
    $option_cat = get_option( 'autodater_category' );
     $option_date = get_option( 'autodater_date_format' );

// checking option exist or not.
    if ( get_option( $option_name ) !== false ) { 
	
        $option_name='autodater_stamp';
		
     } else { 
	 
        add_option( $option_name,'','', 'yes' );
		
     }

   $status_date=get_option( $option_name );

// comparing the stamp date with current date string.
     if( $status_date == date( 'j M Y' ) ) {
		 
    return; 
	
    } else {
     
   update_option( $option_name, date( 'j M Y' ) );

// getting all posts related to daily updation categroy.
   $args = array( 'category_name' => $option_cat );
    $post_list = get_posts( $args );
      $posts = array();

	  // copying all post id's in a array.
    foreach($post_list as $post) { 
	
        $posts[] = $post->ID; 
   
    }
// a loop for changing all posts meta by calling every time autodater function with related post id.
    for( $x=0; $x<count($posts); $x++ ) {
		
       autodater( $posts[$x], 'autodater', $option_date ); 
     
	 } 

   }
}

// function for checking and adding date or time string on post title.
  function autodater( $id , $key_name , $key_value ) 
 {	
      $title = get_the_title( $id );
       $key_value = date( $key_value );
	   
    if( strpos( $title , $key_value ) === false ) {
		
       if( get_post_meta( $id , $key_name , true ) == '' ) {
		   
          delete_post_meta( $id, $key_name );
           add_post_meta( $id, $key_name, $key_value, false );
		   
		   // getting the position option from settings.
            $pos = get_option( 'autodater_position' );

// check date or time string positon on title.
      if( $pos == 'beginning' ) {
		  
         $title = $key_value.' '.$title;
		 
	  }
	  
      if( $pos == 'ending' ) {
		  
        $title= $title.' '.$key_value;
		
	  }
	  
      if( $pos == 'middle' ) {
		     
	    $title=str_replace( 'autodater', $key_value, $title );
		
	  }
	  
	  // generating a GTM date & time for updating post publish and modification meta.
       $pos_dat = date( 'Y-m-d H:i:s' );
       $post_args = array(
	                      'ID' => $id,
	                      'post_title' => $title,
	                      'post_date' => $pos_dat,
	                      'post_date_gmt' => $pos_dat
	                     );

      wp_update_post( $post_args ); 
  
    } else { 
	
      $old_value = get_post_meta( $id, $key_name, true );
	  
     if( $key_value != $old_value ) {
		 
      // search and replace the old date or time in post title.	 
      $title = str_replace( $old_value, $key_value, $title );
        update_post_meta( $id, $key_name, $key_value, $old_value );
         $pos_dat = date('Y-m-d H:i:s');
          $post_args = array(
		                     'ID'=>$id,
		                     'post_title' => $title,
		                     'post_date' => $pos_dat,
		                     'post_date_gmt' => $pos_dat
		                    );
        wp_update_post($post_args); 
      } 
    }
  }
}
?>