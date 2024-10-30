<?php
/**

Plugin Name: Mingle WP-Facebook-Autoconnect Widget

Plugin URI: http://www.icomnow.com

Description: Sidebar LoginLogout widget for Mingle with Facebook Connect button

Author: Jay Schires & iComNow

Version: 0.1

Author URI: http://www.icomnow.com http://istore.icomnow.com

*/ 
class Widget_MingleFacebook extends WP_Widget
{
    //////////////////////////////////////////////////////

    //Init the Widget
    function Widget_MingleFacebook()
    {
        $this->WP_Widget( false, "Mingle-FB Connector", array( 'description' => 'A sidebar Login/Logout form with Facebook Connect button' ) );

    }
    //////////////////////////////////////////////////////

    //Output the widget's content.

    function widget( $args, $instance )
    {
        //Get args and output the title

        extract( $args );

        echo $before_widget;

        $title = apply_filters('widget_title', $instance['title']);

        if( $title ) echo $before_title . $title . $after_title;

        

        //If logged in, show "Welcome, User!"

        if( is_user_logged_in() ):

        ?>
<div>
            <div style="text-align:center;">
            
              <?php 
				global $mngl_options;
                $userdata = wp_get_current_user();

                echo __('Welcome') . ', ' . $userdata->display_name;

              ?>!<br />
                <a href="<?php echo get_permalink($mngl_options->profile_edit_page_id); ?>">Edit My Profile</a> | <a href="<?php echo wp_logout_url(get_permalink($mngl_options->activity_page_id)); ?>"><?php _e('Logout', 'mingle'); ?></a>
                
                <br /></div>         
              <?php   global $wpdb, $mngl_user, $mngl_friend, $user_id, $current_user, $mngl_message,$mngl_options;
			  get_currentuserinfo();      
    $friends = $mngl_friend->get_friend_count($user_id, "status='verified'");
              $request_count = $mngl_friend->get_friend_requests_count( $mngl_user->id );
              $request_count_str = (($request_count > 0)?" ({$request_count})":'');
              
              $unread_count = $mngl_message->get_unread_count();

              $unread_count_str = '';
              if($unread_count)
                $unread_count_str = " ({$unread_count})";
        ?>
        
        <ul style="font-size:small;list-style-type:none;text-align:left;" class="mngl-login-widget-nav">        
          <li><a href="<?php echo get_permalink($mngl_options->activity_page_id); ?>"><?php _e('Activity', 'mingle'); ?></a></li>
          <li><a href="<?php echo get_permalink($mngl_options->profile_page_id); ?>"><?php _e('Profile', 'mingle'); ?></a></li>
          <li><a href="<?php echo get_permalink($mngl_options->profile_edit_page_id); ?>"><?php _e('Settings', 'mingle'); ?></a></li>
          <li><a href="<?php 
		  $friends = $mngl_friend->get_friend_count($current_user->ID);
		  echo get_permalink($mngl_options->friends_page_id); ?>"><?php _e('Friends ', 'mingle'); ?></a>(<?php echo $friends; ?>)</li>
          <li><a href="<?php echo get_permalink($mngl_options->friend_requests_page_id); ?>"><?php _e('Friend Requests', 'mingle'); ?><?php echo $request_count_str; ?></a></li>
          <li><a href="<?php echo get_permalink($mngl_options->inbox_page_id); ?>"><?php _e('Inbox', 'mingle'); ?><?php echo $unread_count_str; ?></a></li>
          <li><a href="<?php echo get_permalink($mngl_options->directory_page_id); ?>"><?php _e('Directory', 'mingle'); ?></a></li></ul>

            </div>

        <?php

        //Otherwise, show the login form (with Facebook Connect button)

        else:

        ?>

<form name="loginform" id="loginform" action="<?php echo $login_url; ?>" method="post">
        	<p>
        		<label><strong><?php _e('Username', 'mingle'); ?></strong><br />
        		<input type="text" name="log" id="user_login" class="input" value="" tabindex="710" style="width: 100%; font-size: 12px; padding: 4px;" /></label>
        		<label><strong><?php _e('Password', 'mingle'); ?></strong><br />
        		<input type="password" name="pwd" id="user_pass" class="input" value="" tabindex="720" style="width: 100%; line-height: 12px; padding: 4px;" /></label><br/>
        	  <label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="730" style="width: 15px;" /> <?php _e('Remember Me', 'mingle'); ?></label>
        	</p>
        	<p class="submit">
        		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary mngl-share-button" value="<?php _e('Log In', 'mingle'); ?>" tabindex="740" />
        		<input type="hidden" name="redirect_to" value="<?php echo get_permalink($mngl_options->activity_page_id); ?>" />
        		<input type="hidden" name="testcookie" value="1" />
        		<input type="hidden" name="mngl_process_login_form" value="true" />
        	</p>
</form>
        <p style="font-size: 10px" class="mngl-login-actions">
        <?php if(get_option('users_can_register')) {
			global $mngl_options;
			$signup_url = get_permalink($mngl_options->signup_page_id);
          ?>
            <a href="<?php echo $signup_url; ?>"><?php _e('Register', 'mingle'); ?></a>&nbsp;|
          <?php
              }global $mngl_options;
      ?>
        <a href="<?php echo $forgot_password_url; ?>"><?php _e('Lost Password?', 'mingle'); ?></a>

            <?php

            global $opt_jfb_hide_button;

            if( !get_option($opt_jfb_hide_button) )

            {

                jfb_output_facebook_btn();

                //jfb_output_facebook_init(); This is output in wp_footer as of 1.5.4

                jfb_output_facebook_callback();

            }

        endif;

        echo $after_widget;

    }

    

    

    //////////////////////////////////////////////////////

    //Update the widget settings

    function update( $new_instance, $old_instance )

    {

        $instance = $old_instance;

        $instance['title'] = $new_instance['title'];

        return $instance;

    }



    ////////////////////////////////////////////////////

    //Display the widget settings on the widgets admin panel

    function form( $instance )

    {

        ?>

        <p>

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>

            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />

        </p>

        <?php

    }

}

function Widget_MingleFacebook_shortcode()
{
    $Widget_MingleFacebook_class = new Widget_LoginLogout;
    $Widget_MingleFacebook_class->widget(array(), 999);
}



//Register the widget


add_action('widgets_init', create_function('', 'return register_widget("Widget_MingleFacebook");'));


?>