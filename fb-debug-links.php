<?php
/*
Plugin Name: Facebook Debug Links
Plugin URI:  http://wordpress.org/extend/plugins/facebook-debug-links/
Description: Provides links in your wordpress admin to check your page/post in the Facebook Debugger 
Version:     1.0.1
Author:      birgire
Author URI:  http://profiles.wordpress.org/birgire
License:     GPLv2
*/


/** 
 * The Facebook Debug Links class
 */

if(!class_exists(FacebookDebugLinks)){

/**
 * Calls the class
 */
function call_FacebookDebugLinks() 
{
    return new FacebookDebugLinks();
}
if ( is_admin() ){
    	add_action( 'admin_init', 'call_FacebookDebugLinks' );
}

class FacebookDebugLinks
{
     // url to Facebook Debugger 
    protected $debuggerUrl = "http://developers.facebook.com/tools/debug/og/object?q=";  
	
    public function __construct()
    {
        add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );
	add_action( 'post_row_actions', array( &$this, 'post_row_actions' ) );
	add_action( 'page_row_actions', array( &$this, 'post_row_actions' ) );
    }

    /**
     * Adds the meta box container
     */
    public function add_meta_box()
    {
        add_meta_box( 
             'some_meta_box_name'
            ,__( 'Facebook Object Debug')
            ,array( &$this, 'render_meta_box_content' )
            ,'' 
            ,'side'
            ,'default'
        );
    }


    /**
     * Render Meta Box content
     */
    public function render_meta_box_content() 
    {
     	global $post;
     	if($post->post_status=="publish"){
		echo "<a href=\"".$this->debuggerUrl.get_permalink($post->ID)."\" target=\"_blank\">Open in the Facebook Debugger</a> &raquo;<br/><br/>";
		echo "This will <ul style=\"list-style:disc;margin-left:20px;\">";
		echo "<li>flush the Facebook cache </li>";
		echo "<li>show how the Facebook scraper is interpreting your Open Graph object </li>";
		echo "</ul>";
		echo "Here are some debugger <a href=\"https://developers.facebook.com/tools/debug/examples\" target=\"_blank\">examples</a>";
  	}else{
		echo "Only for published posts";
	}
    }

    /**
     * Add post row action
     */
	public function post_row_actions($actions)
	{
		global $post;
		// add fbdebug post row action only for published post/page
	     	if($post->post_status=="publish"){
    			$actions['fbdebug'] = "<a href=\"".$this->debuggerUrl.get_permalink($post->ID)."\" target=\"_blank\">FB-Debugger</a>";
		}
		return $actions;
	
	}

}

}//end if class_exists
