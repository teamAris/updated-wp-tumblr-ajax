<?php
/*  Copyright 2014  Humphrey Aaron  (email : humphreyaaron7@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * @package	Tumblr AJAX
 * @author	Humphrey Aaron
 * @version	1.0
 */
 
/*
Plugin Name: Tumblr AJAX Widget
Plugin URI: http://wordpress.org/extend/plugins/tumblr-ajax
Description: Get/display Tumblr posts via AJAX / Javascript / Client-side HTML requests. This plugin is great where the WordPress hosting server does not allow external HTTP requests, or where external HTTP requests on the server are preferred to be minimal.
Author: Humphrey Aaron
Version: 1.0
Author URI: http://humphreyaaron.uhostall.com
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Prevent direct access to the plugin 
  if (!defined('ABSPATH')) {
    exit(__( "Sorry, you are not allowed to access this page directly." ));
  }

define( 'TUMBLR_AJAXWEB_PATH', plugin_dir_url(__FILE__));

//get default stylesheet ready
wp_enqueue_style( 'TumblrAJAX', TUMBLR_AJAXWEB_PATH . '/default.css' ); 

// widget code
function tumblr_ajax($args) {

    extract($args);
    $options = get_option('tumblr_ajax');

    
    //if (!is_single() && !is_home())
    //	return;

    $title = empty($options['title'])  ? '' : $options['title'];
    $link_title = empty($options['title'])  ? 'false' : $options['link_title'];
    $tumblr_domain = empty($options['domainURL']) 		? 'demo.tumblr.com'	: rtrim($options['domainURL'], "/ \t\n\r");
    $posts_count = empty($options['count'])  		? 5	: $options['count'];
    $post_link = empty($options['post_link'] )	? 'false'	: $options['post_link'];
    $errors = empty($options['report_errors'] )	? 'false'	: $options['report_errors'];
    $showfirstimage = empty($options['show_first_image'] )	? 'false'	: $options['show_first_image'];
    $first_photo_size = empty($options['first_photo_size'] )	? '100%'	: $options['first_photo_size'];
    $load_regular = empty($options['load_regular'] )	? ''	: $options['load_regular'];
    $load_photo = empty($options['load_photo'] )	? ''	: $options['load_photo'];
	  $load_photo_size = empty($options['load_photo_size'] )	? '100px'	: $options['load_photo_size'];
	  $load_quote = empty($options['load_quote'] )	? ''	: $options['load_quote'];
	  $load_link = empty( $options['load_link'] )	? ''	: $options['load_link'];
    $load_conversation = empty( $options['load_conversation'] )	? ''	: $options['load_conversation'];
    $load_audio = empty( $options['load_audio'] )	? ''	: $options['load_audio'];
    $load_video = empty( $options['load_video'] )	? ''	: $options['load_video'];
    $load_video_size = empty( $options['load_video_size'] )	? '250'	: $options['load_video_size'];    
    $load_answer = empty( $options['load_answer'] )	? ''	: $options['load_answer'];
    
    $create_excerpt = empty( $options['create_excerpt'] )	? 'false'	: $options['create_excerpt'];
    $excerpt_count = empty( $options['excerpt_count'] )	? '0'	: $options['excerpt_count'];
    $strip_htmlALL = empty( $options['strip_htmlALL'] )	? 'false'	: $options['strip_htmlALL'];
    $allow_HTML = empty( $options['allow_HTML'] )	? ''	: $options['allow_HTML'];
   
      if ($link_title) {
        $html_title = '<a href="http://' . $tumblr_domain . '" target="_blank">' . $title . '</a>';
      }
      else {
        $html_title = $title;
      }
      
      //deal with post types to request from Tumblr
      $post_types = $load_regular . ' ' . $load_photo . ' ' . $load_quote . ' ' . $load_link . ' ' . $load_conversation . ' ' . $load_audio . ' ' . $load_video . ' ' . $load_answer;
      $post_types_count = str_word_count ($post_types, 0);
      $selected_post_type = $post_types;
     
      
      if ($post_types_count == 1 ) {
			  $AJAX_url = "http://".$tumblr_domain."/api/read/json?num=".$posts_count."&amp;type=".trim($selected_post_type)."&amp;callback=tumblr_ajax_load";
			 }
      elseif ($post_types_count == 8) 
      {
			  $AJAX_url = "http://".$tumblr_domain."/api/read/json?num=".$posts_count."&amp;callback=tumblr_ajax_load";
			}
      else {
        $AJAX_url = "http://".$tumblr_domain."/api/read/json?num=50&amp;callback=tumblr_ajax_load";
      }
      
      
      $before_title = '<h2>';
      $after_title = '</h2>';
      $carousel_open = '<div class="tumblr-ajax-container">';
      $carousel_close = '</div>';
    ?>
    <?php echo $before_widget . $carousel_open; ?>
        <?php echo $before_title . $html_title . $after_title; 
       
              ?>
              <input type="hidden" id="tumblr_ajax_sel_posts_count" name="tumblr_ajax_sel_posts_count" value="<?php echo $posts_count ?>" />
              <input type="hidden" id="tumblr_ajax_sel_post_link" name="tumblr_ajax_sel_post_link" value="<?php echo $post_link ?>" />
              <input type="hidden" id="tumblr_ajax_sel_errors" name="tumblr_ajax_sel_errors" value="<?php echo $errors ?>" />
              <input type="hidden" id="tumblr_ajax_sel_showfirstimage" name="tumblr_ajax_sel_showfirstimage" value="<?php echo $showfirstimage ?>" />
              <input type="hidden" id="tumblr_ajax_sel_first_photo_size" name="tumblr_ajax_sel_first_photo_size" value="<?php echo $first_photo_size ?>" />
              <input type="hidden" id="tumblr_ajax_sel_load_photo_size" name="tumblr_ajax_sel_load_photo_size" value="<?php echo $load_photo_size ?>" />
              <input type="hidden" id="tumblr_ajax_sel_create_excerpt" name="tumblr_ajax_sel_create_excerpt" value="<?php echo $create_excerpt ?>" />
              <input type="hidden" id="tumblr_ajax_sel_excerpt_count" name="tumblr_ajax_sel_excerpt_count" value="<?php echo $excerpt_count ?>" />
              <input type="hidden" id="tumblr_ajax_sel_strip_htmlALL" name="tumblr_ajax_sel_strip_htmlALL" value="<?php echo $strip_htmlALL ?>" />
              <input type="hidden" id="tumblr_ajax_sel_allow_HTML" name="tumblr_ajax_sel_allow_HTML" value="<?php echo $allow_HTML ?>" />
              <input type="hidden" id="tumblr_ajax_sel_types" name="tumblr_ajax_sel_types" value="<?php echo $selected_post_type ?>" />
              <input type="hidden" id="tumblr_ajax_sel_posttypes" name="tumblr_ajax_sel_posttypes" value="<?php echo $post_types_count ?>" />
              <input type="hidden" id="tumblr_ajax_sel_videosize" name="tumblr_ajax_sel_videosize" value="<?php echo $load_video_size ?>" />
              
              <div id="tumblr_ajax_data" class="running_ajax"></div>
              <script type="text/javascript" src="<?php echo TUMBLR_AJAXWEB_PATH ?>/tumblr-ajax.js"></script>
              <script type="text/javascript" src="<?php echo $AJAX_url ?>"></script>
             

          <?php  
              echo $carousel_close .$after_widget; ?>
        <?php
      $post = $original_post;
}

// widget controller
function tumblr_ajax_control() {
    
	$options = $newoptions = get_option('tumblr_ajax');
	if ( $_POST['tumblr_ajax-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['tumblr-ajax-title'] ) );
    $newoptions['link_title'] = strip_tags( stripslashes( $_POST['tumblr-ajax-link_title'] ) );
		$newoptions['domainURL'] = strip_tags( stripslashes( $_POST['tumblr-ajax-domain'] ) );
		$newoptions['count'] = strip_tags( stripslashes( $_POST['tumblr-ajax-count'] ) );
		$newoptions['post_link'] = strip_tags( stripslashes( $_POST['tumblr-ajax-post-link'] ) );
    $newoptions['report_errors'] = strip_tags( stripslashes( $_POST['tumblr-ajax-errors'] ) );
    $newoptions['show_first_image'] = strip_tags( stripslashes( $_POST['tumblr-ajax-showfirstimage'] ) );
    $newoptions['first_photo_size'] = strip_tags( stripslashes( $_POST['tumblr-ajax-firstphotosize'] ) );
    
    $newoptions['load_regular'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadregular'] ) );
    $newoptions['load_photo'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadphoto'] ) );
    $newoptions['load_photo_size'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadphotosize'] ) );
    $newoptions['load_quote'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadquote'] ) );
    $newoptions['load_link'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadlink'] ) );
    $newoptions['load_conversation'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadconversation'] ) );
    $newoptions['load_audio'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadaudio'] ) );
    $newoptions['load_video'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadvideo'] ) );
    $newoptions['load_video_size'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadvideosize'] ) );
    $newoptions['load_answer'] = strip_tags( stripslashes( $_POST['tumblr-ajax-loadanswer'] ) );
    $newoptions['create_excerpt'] = strip_tags( stripslashes( $_POST['tumblr-ajax-create-excerpt'] ) );
    $newoptions['excerpt_count'] = strip_tags( stripslashes( $_POST['tumblr-ajax-excerpt-count'] ) );
    $newoptions['strip_htmlALL'] = strip_tags( stripslashes( $_POST['tumblr-ajax-strip-HTML'] ) );
    $newoptions['allow_HTML'] = $_POST['tumblr-ajax-allow-html'];
	}

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'tumblr_ajax', $options );
	}
	$title		 = attribute_escape( $options['title'] );
  $link_title = attribute_escape( $options['link_title'] );
	$domainURL		 = attribute_escape( $options['domainURL'] );
	$count		 = attribute_escape( $options['count'] );
	$post_link = attribute_escape( $options['post_link'] );
  $errors = attribute_escape( $options['report_errors'] );
  $showfirstimage = attribute_escape( $options['show_first_image'] );
  $first_photo_size = attribute_escape( $options['first_photo_size'] );
  
  $load_regular = attribute_escape( $options['load_regular'] );
  $load_photo = attribute_escape( $options['load_photo'] );
	$load_photo_size = attribute_escape( $options['load_photo_size'] );
	$load_quote = attribute_escape( $options['load_quote'] );
	$load_link = attribute_escape( $options['load_link'] );
  $load_conversation = attribute_escape( $options['load_conversation'] );
  $load_audio = attribute_escape( $options['load_audio'] );
  $load_video = attribute_escape( $options['load_video'] );
  $load_video_size = attribute_escape( $options['load_video_size'] );
  $load_answer = attribute_escape( $options['load_answer'] );
  $create_excerpt = attribute_escape( $options['create_excerpt'] );
  $excerpt_count = attribute_escape( $options['excerpt_count'] );
  $strip_htmlALL = attribute_escape( $options['strip_htmlALL'] );
  $allow_HTML  = attribute_escape( $options['allow_HTML'] );
?>
<p>
  <label for="tumblr-ajax-title">Title: <input class="widefat" id="tumblr-ajax-title" name="tumblr-ajax-title" type="text" value="<?php echo $title; ?>" />
  </label>
</p>
<p>
  <input class="checkbox" type="checkbox" id="tumblr-ajax-link_title" value="true" name="tumblr-ajax-link_title" <?php if ($link_title) echo 'checked'; ?> />
  <label for="tumblr-ajax-link_title">Make main Title a link to Tumblr Blog</label>
</p>
<p>
  <label for="tumblr-ajax-domain">
    <?php _e( 'Tumblr URL:', 'tumblr_ajax' ) ?> <em>e.g. demo.tumblr.com</em>  <input class="widefat" id="tumblr-ajax-domain" name="tumblr-ajax-domain" type="text" value="<?php echo $domainURL; ?>" />
  </label>
</p>
<p>
  <label for="tumblr-ajax-count">
    <?php _e( 'No. of Posts (numeric):', 'tumblr_ajax' ) ?> <input class="widefat" id="tumblr-ajax-count" name="tumblr-ajax-count" style="width:50px;" type="text" value="<?php echo $count; ?>" />
    <em>Note that Tumblr limits this to 50</em>
      </label>
</p>
<p>
  <input class="checkbox" type="checkbox" id="tumblr-ajax-errors" value="true" name="tumblr-ajax-errors" <?php if ($errors) echo 'checked'; ?> />
  <label for="tumblr-ajax-errors">Report back errors</label>
</p>
<p>
  <input class="checkbox" type="checkbox" id="tumblr-ajax-post-link" value="true" name="tumblr-ajax-post-link" <?php if ($post_link) echo 'checked'; ?> />
  <label for="tumblr-ajax-post-link">Make each post Title a link to corresponding Tumblr Post</label>
</p>
<p>
  <input class="checkbox" type="checkbox" id="tumblr-ajax-create-excerpt" value="true" name="tumblr-ajax-create-excerpt" <?php if ($create_excerpt) echo 'checked'; ?> />
  <label for="tumblr-ajax-create-excerpt">Show excerpt instead of entire post</label>
</p>
<p>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Excerpt character length </em><input class="widefat" id="tumblr-ajax-excerpt-count" name="tumblr-ajax-excerpt-count" style="width:50px;" type="text" value="<?php echo $excerpt_count; ?>" />
</p>
<p>
  <input class="checkbox" type="checkbox" id="tumblr-ajax-strip-HTML" value="true" name="tumblr-ajax-strip-HTML" <?php if ($strip_htmlALL) echo 'checked'; ?> />
  <label for="tumblr-ajax-strip-HTML">Strip all HTML Tags from Post</label>
</p>
<p>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Allow only these HTML Tags e.g.&lt;b&gt;&lt;p&gt;<br/>(Note: &lt;div&gt;&lt;blockquote&gt;&lt;strong&gt; are always allowed)</em>
</p>
<p>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="widefat" id="tumblr-ajax-allow-html" name="tumblr-ajax-allow-html" style="width:250px;" type="text" value="<?php echo $allow_HTML; ?>" />
</p>
<p>
  <input class="checkbox" type="checkbox" value="true" id="tumblr-ajax-showfirstimage" name="tumblr-ajax-showfirstimage" <?php if ($showfirstimage) echo 'checked'; ?> />
  <label for="tumblr-ajax-showfirstimage">Show the first image (if any) in post as a feature image</label>
  </p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Select preferred image size:</em>&nbsp;&nbsp;<select id="tumblr-ajax-firstphotosize" name="tumblr-ajax-firstphotosize" value="<?php echo $first_photo_size; ?>">
    <option value="10%"
      <?php if ($first_photo_size=='10%') echo 'selected="selected"'; ?>>10%
    </option>
    <option value="20%"
      <?php if ($first_photo_size=='20%') echo 'selected="selected"'; ?>>20%
    </option>
    <option value="30%"
      <?php if ($first_photo_size=='30%') echo 'selected="selected"'; ?>>30%
    </option>
    <option value="40%"
      <?php if ($first_photo_size=='40%') echo 'selected="selected"'; ?>>40%
    </option>
    <option value="50%"
      <?php if ($first_photo_size=='50%') echo 'selected="selected"'; ?>>50%
    </option>
    <option value="60%"
      <?php if ($first_photo_size=='60%') echo 'selected="selected"'; ?>>60%
    </option>
    <option value="70%"
      <?php if ($first_photo_size=='70%') echo 'selected="selected"'; ?>>70%
    </option>
    <option value="80%"
      <?php if ($first_photo_size=='80%') echo 'selected="selected"'; ?>>80%
    </option>
    <option value="90%"
      <?php if ($first_photo_size=='90%') echo 'selected="selected"'; ?>>90%
    </option>
    <option value="100%"
      <?php if ($first_photo_size=='100%') echo 'selected="selected"'; ?>>100%
    </option>
  </select>  
</p>

<p>
  <strong>Load the following type of posts:</strong>
</p>

<p>
  <input class="checkbox" value="regular" type="checkbox" <?php if ($load_regular) echo 'checked'; ?> id="tumblr-ajax-loadregular" name="tumblr-ajax-loadregular" />
    <label for="tumblr-ajax-loadregular">Regular posts</label>
</p>

<p>
  <input class="checkbox"  value="photo" type="checkbox" <?php if ($load_photo) echo 'checked'; ?> id="tumblr-ajax-loadphoto" name="tumblr-ajax-loadphoto" />
    <label for="tumblr-ajax-loadphoto">Photo posts</label>
  &nbsp;&nbsp;&nbsp;&nbsp;<label for="tumblr-ajax-loadphotosize"><strong><em>Photo size:</em></strong></label>&nbsp;&nbsp;
  <select id="tumblr-ajax-loadphotosize" name="tumblr-ajax-loadphotosize" value="<?php echo $load_photo_size; ?>">
    <option value="75"
      <?php if ($load_photo_size==75) echo 'selected="selected"'; ?>>75px
    </option><option value="100"
      <?php if ($load_photo_size==100) echo 'selected="selected"'; ?>>100px
    </option><option value="250"
      <?php if ($load_photo_size==250) echo 'selected="selected"'; ?>>250px
    </option><option value="400"
      <?php if ($load_photo_size==400) echo 'selected="selected"'; ?>>400px
    </option><option value="500"
      <?php if ($load_photo_size==500) echo 'selected="selected"'; ?>>500px
    </option>
  </select>
</p>

<p>
  <input class="checkbox"  value="quote"  type="checkbox" <?php if ($load_quote) echo 'checked'; ?> id="tumblr-ajax-loadquote" name="tumblr-ajax-loadquote" />
    <label for="tumblr-ajax-loadquote">Quotation posts </label>
</p>
<p>
  <input class="checkbox"  value="link"  type="checkbox" <?php if ($load_link) echo 'checked'; ?> id="tumblr-ajax-loadlink" name="tumblr-ajax-loadlink" />
    <label for="tumblr-ajax-loadlink">Link posts </label>
</p>
<p>
  <input class="checkbox"  value="conversation"  type="checkbox" <?php if ($load_conversation) echo 'checked'; ?> id="tumblr-ajax-loadconversation" name="tumblr-ajax-loadconversation" />
    <label for="tumblr-ajax-loadconversation">Conversation posts</label>
</p>

<p>
  <input class="checkbox"  value="audio"  type="checkbox" <?php if ($load_audio) echo 'checked'; ?> id="tumblr-ajax-loadaudio" name="tumblr-ajax-loadaudio" />
    <label for="tumblr-ajax-loadaudio">Audio posts</label>
</p>

<p>
  <input class="checkbox"  value="video"  type="checkbox" <?php if ($load_video) echo 'checked'; ?> id="tumblr-ajax-loadvideo" name="tumblr-ajax-loadvideo" />
    <label for="tumblr-ajax-loadvideo">Video posts
  </label>
    &nbsp;&nbsp;&nbsp;&nbsp;<label for="tumblr-ajax-loadphotosize">
      <strong>
        <em>Video size:</em>
      </strong>
    </label>&nbsp;&nbsp;
    <select id="tumblr-ajax-loadvideosize" name="tumblr-ajax-loadvideosize" value="<?php echo $load_video_size; ?>">
      <option value="75"
        <?php if ($load_video_size==400) echo 'selected="selected"'; ?>>400px
      </option><option value="100"
        <?php if ($load_video_size==500) echo 'selected="selected"'; ?>>500px
      </option><option value="250"
        <?php if ($load_video_size==250) echo 'selected="selected"'; ?>>250px
      </option>
    </select>
</p>
<p>
  <input class="checkbox"  value="answer"  type="checkbox" <?php if ($load_answer) echo 'checked'; ?> id="tumblr-ajax-loadanswer" name="tumblr-ajax-loadanswer" />
  <label for="tumblr-ajax-loadanswer">
    Answer posts
  </label>
</p>
<hr style="width:100%"/>
<input type="hidden" id="tumblr_ajax-submit" name="tumblr_ajax-submit" value="1" />
  <div style="border:1px solid #ccc;background-color:#778899;margin-bottom:3px;padding:3px;text-align:center">
    <h4 style="color:#fff;font-weight:normal">Thank you for using this Plugin. Like what you see? Let us know by Donating below.</h4>&nbsp;&nbsp;
      <img style="margin:3px;cursor:hand;cursor:pointer" src="<?php echo TUMBLR_AJAXWEB_PATH . '/don.jpg' ?>" name="tumblr-ajax-donate" onclick="javascript:tumblr_ajax_donate()" />
    <script type="text/javascript">
      function tumblr_ajax_donate()
      {
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "https://www.paypal.com/cgi-bin/webscr");
        form.setAttribute("target", "_blank");
        form.setAttribute("id", "tumblr-ajax-donate-form");
        form.setAttribute("style", "display:none");

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("name", "cmd");
        hiddenField.setAttribute("value","_s-xclick" );
        hiddenField.setAttribute("type", "hidden");
        form.appendChild(hiddenField);

        var hiddenField2 = document.createElement("input");
        hiddenField2.setAttribute("name", "encrypted");
        hiddenField2.setAttribute("value","-----BEGIN PKCS7-----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-----END PKCS7-----" );
        hiddenField2.setAttribute("type", "hidden");
        form.appendChild(hiddenField2);

        document.body.appendChild(form);

        form.submit();

        document.body.removeChild(document.getElementById('tumblr-ajax-donate-form'));
      }
    </script>
</div>

<?php

}
// widget initializer
function tumblr_ajax_init()
{
	register_sidebar_widget( 'Tumblr AJAX', 'tumblr_ajax', 'Display Tumblr posts on your site' );
	register_widget_control( 'Tumblr AJAX', 'tumblr_ajax_control', 300, 470);
}

add_action('widgets_init', 'tumblr_ajax_init' );


function plugin_meta_links($links, $file) {  
    $plugin = plugin_basename(__FILE__);
    if ($file == $plugin)
    {
      $don_link =  '<a href="http://humphreyaaron.uhostall.com/#custom_part" target="_blank">' . __('Donate') . '</a>';
      array_push($links, $don_link);
    }
    return $links;  
  }  
  add_filter( 'plugin_row_meta', 'plugin_meta_links', 10, 2 );  


?>
