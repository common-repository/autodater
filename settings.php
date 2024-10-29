<?php

  if ( ! defined( 'ABSPATH' ) ) {
	  
   exit();
   
  }

  add_action( 'admin_menu', 'my_autodater_menu' );

  function my_autodater_menu()
 { 
  add_menu_page ( 
		          'AutoDater Settings',
		          'AutoDater',
		          'manage_options',
		          'autodater',
                  'my_autodater_settings_page',
                  'dashicons-calendar-alt',
                  99
                 );

   add_action( 'admin_init', 'register_my_autodater_settings_setup' );

  add_action('wp_dashboard_setup', 'autodater_add_dashboard_widget');

 }

  function autodater_add_dashboard_widget() 
 {
     wp_add_dashboard_widget( 
	                         'autodater_dashboard_widget', 
	                         'Today AutoDater Status', 
	                         'autodater_dashboard_widget' 
	                         ); 
  global $wp_meta_boxes; 
  
         $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core']; 
           $example_widget_backup = array( 'autodater_dashboard_widget' => $normal_dashboard['autodater_dashboard_widget'] );
             unset( $normal_dashboard['example_dashboard_widget'] ); 
               $sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );
    			   $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
 
 }

   function autodater_dashboard_widget()
 { 
   echo '<table><tr><td>';
   
     echo '<img src="'.plugins_url('images/autodater-icon.jpg', __FILE__ ).'" width="64" height="64" alt="autodater-icon" /></td><td style="padding-left:10px;">';

     if( get_option( 'autodater_stamp' ) == date( 'j M Y' )) {
	   
         echo '<p style="color:green;font-weight:bolder;"><span class="dashicons dashicons-yes"></span> Completed </p><p style="color:#333333;"> Today autodater is already changed all titles and post dates successfully.</p>';
	
      } else {
	   
        echo '<p style="color:red;font-weight:bolder;"><span class="dashicons dashicons-no"></span> Uncompleted </p><p style="color:333333;"> Today autodater not done anything or pending. please load your website main-page once without admin login mode.</p>';
		
	}

       echo '</td></tr></table>';

}




  function register_my_autodater_settings_setup()
 {
	register_setting( 'my-autodater-settings-group', 'autodater_category' );
	  register_setting( 'my-autodater-settings-group', 'autodater_status' );
       register_setting( 'my-autodater-settings-group', 'autodater_position' );
	    register_setting( 'my-autodater-settings-group', 'autodater_date_format' );
 }

  function  my_autodater_settings_page()
 {
?>
<div class="wrap">

    <h1> AutoDater Settings </h1>

 <form method="post" action="options.php"  name="autodater">
<?php settings_fields( 'my-autodater-settings-group' ); ?>
<?php do_settings_sections( 'my-autodater-settings-group' ); ?>
 <table class="form-table autodater">
 <tr valign="top">
 <th scope="row">Plugin Mode :</th>
 <td>
<input type="checkbox" name="autodater_status" value="1" <?php checked(get_option('autodater_status'),1); ?> > On/Off
<p><span>Description:  </span> You can trun on and off the plugin by this option check or uncheck.</p>
 </tr>
 <tr valign="top">
 <th scope="row">Category Name : </th>
  <td><input type="text" name="autodater_category" value="<?php echo esc_attr( get_option('autodater_category') ) ?>" placeholder="ie. Promocodes" />
<p><span> Description: </span> Enter the name of your category for daily date update.</p></td>
 </tr>
 <tr valign="top">
 <th scope="row"> Date format : </th>
  <td><input type="text" name="autodater_date_format" value="<?php echo esc_attr( get_option('autodater_date_format') ) ?>" placeholder="ie. Y-m-d h:i:s" />
<p> <span>Description: </span> Enter the date format like <i>Y-m-d</i> means 2017-05-13. Further reference of date format go to <a href="http://php.net/manual/en/function.date.php" target="_blank">
http://php.net/manual/en/function.date.php</a>
</p>
</td>
 </tr>
<tr valign="top">
 <th scope="row">Position :</th>
 <td>
<input type="radio" name="autodater_position" value="beginning" <?php if( get_option('autodater_position') == 'beginning') echo 'checked="true"'; ?> > Beginning <br>
<input type="radio" name="autodater_position" value="ending" <?php if( get_option('autodater_position') == 'ending') echo 'checked="true"'; ?> > Ending <br>
<input type="radio" name="autodater_position" value="middle" <?php if( get_option('autodater_position') == 'middle') echo 'checked="true"'; ?> >  Custom <small>(recommended)</small><br>
<p><span>Description:  </span> If you choose Custom position, you can set date or time position anywhere on post title not ony starting and ending. Just add the <code>autodater</code> word in your post title where you want to show date or time string.</p>
<p> Example Post Title : <i> Amazon shopping promocodes on <mark>autodater</mark> : 50% discount today => Amazon shopping promocodes on <mark>2017 Mar 13</mark> : 50% discount today.</i>
</p>
 </tr>
    </table>
<?php submit_button(); ?>
</form>
</div>
<?php } ?>