<?php
/**
 * Theme Functions
 *
 * Holds functions used in various areas of theme.
 *
 * @package      Techozoic Fluid
 * @author       Jeremy Clark <jeremy@clark-technet.com>
 * @copyright    Copyright (c) 2011, Jeremy Clark
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 *
 */


/**************************************
	Loads dynamic styles if get variable is set to css
	Rebuilds styles stored in database if set to build
	Since 1.9.1
***************************************/
	if (isset($_GET['techozoic_css'])){
		if ($_GET['techozoic_css'] == 'css'){
			include_once (TEMPLATEPATH . '/style.php');
			exit;
		} elseif ($_GET['techozoic_css'] == 'build') {
			include_once(TEMPLATEPATH . '/options/css-build.php');
		}
	}
	//Continue Normal Functions
	load_theme_textdomain( 'techozoic', TEMPLATEPATH.'/languages');
	$locale = get_locale();
	$locale_file = TEMPLATEPATH."/languages/$locale.php";
	if ( is_readable($locale_file) )
		require_once($locale_file);
	$upload_path = get_option('upload_path');
	if ( ! defined( 'WP_CONTENT_URL' ) )
    		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
    		define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	define ('WP_UPLOAD_PATH', ABSPATH . $upload_path );
	
	if(is_admin()) {
            include_once (TEMPLATEPATH . "/options/option-array.php");
            include_once(TEMPLATEPATH . '/options/main.php');
	}
	// Include other custom functions files
	//A custom.php file can be added and included without being overwritten by theme updates
	include(TEMPLATEPATH . '/functions/tech-widget.php');
	include(TEMPLATEPATH . '/functions/tech-comments-functions.php');
	if(file_exists(TEMPLATEPATH . '/functions/custom.php')){
		include(TEMPLATEPATH . '/functions/custom.php');
	}
	global $tech;
	if ($tech = get_option('techozoic_options') ){
		$tech = get_option('techozoic_options');
	} else {
		include_once(TEMPLATEPATH . '/options/tech-init.php');
		$tech = tech_temp_options();
	}
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
	$version = $theme_data['Version'];

	if (!isset($content_width)) {
		$content_width = tech_content_width();
	}
	
        
/**
 * Techozoic All Image Size Links
 *
 * Used to output links to all available images sizes of wp_attachment
 * Code adapted from Justin Tadlock 
 * http://justintadlock.com/archives/2011/01/28/linking-to-all-image-sizes-in-wordpress
 * 
 * @return    string    Joined array of all image size links.
 *
 * @access    public
 * @since     1.9.3
 */
	
function tech_image_links() {        
	if ( !wp_attachment_is_image( get_the_ID() ) ){
		return;
	}
	$links = array();
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';
	foreach ( $sizes as $size ) {
		$image = wp_get_attachment_image_src( get_the_ID(), $size );
		if ( !empty( $image ) && ( true == $image[3] || 'full' == $size ) ) {
			$links[] = "<a class='image-size-link' href='{$image[0]}'>{$image[1]} &times; {$image[2]}</a>";
		}
	}
	return join( ' <span class="sep">|</span> ', $links );
}

/**
 * Techozoic excerpt location
 *
 * Used to check whether current page type is in excerpt locations set in options
 * 
 * @param       string  Current page type
 * @return      bool    Return if current page is in excerpt_location array    
 *
 * @access    public
 * @since     1.9.3
 */

function tech_excerpt($where){
	global $tech;
	$locs = explode(',' , $tech['excerpt_location']);
	if (in_array($where, $locs)){ 
		return true;
	} else {
		return false;
	}
}

/**
 * Techozoic social icons location
 *
 * Used to check whether current page type is in post social media locations set in options
 * 
 * @param       string  Current page type
 * @return      bool    Return if current page is in post_social_media_location array    
 *
 * @access    public
 * @since     1.9.3
 */

function tech_icons($where){
	global $tech;
	$locs = explode(',' , $tech['post_social_media_location']);
	if (in_array($where, $locs)){ 
		return true;
	} else {
		return false;
	}
}

/**
 * Techozoic excerpt filter
 *
 * Filter that replaces ellipses with proper html ententity and link to single post page
 * 
 * @param       string  exceprt text
 * @return      string    string replaced excerpt text    
 *
 * @access    private
 * @since     1.9.3
 */

function tech_excerpt_filter($text){ 
	global $post;
	return str_replace('[...]', '<a href="'. get_permalink($post->ID) . '">' . ' [&hellip; ' . __('Read More', 'techozoic') . ']' . '</a>', $text);  
}  
	
/**
 * Techozoic google font 
 *
 * Enqueues google font stylesheet based on google_fonts option
 *    
 *
 * @access    private
 * @since     1.9.3
 */

function tech_google_font() {
	global $tech;
        $tech_google_font_decoration = str_ireplace(',', '', $tech['google_font_decoration']);
	wp_enqueue_style('google_fonts' , "http://fonts.googleapis.com/css?family={$tech['google_font_family']}:{$tech_google_font_decoration}", '', '', 'screen');
	}

	
/**
 * Techozoic comment count
 *
 * Filter that displays correct comment count.
 * http://www.wpbeginner.com/wp-tutorials/display-the-most-accurate-comment-count-in-wordpress/
 * 
 * 
 * @param       string  filter variable
 * @return      string    correct comment count   
 *
 * @access    private
 * @since     1.9.3
 */

 
function tech_comment_count( $count ) {  
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']);
	} else {
	return $count;
	}
}
	
/**
 * Techozoic WP 3 menu fallback
 *
 * Callback for use in wp_nav_menu when no menu is assigned.
 *   
 *
 * @access    private
 * @since     1.9.1
 */	

	
function tech_menu_fallback(){
	$output = ' <ul id="dropdown"> ';
	$clean_page_list = wp_list_pages('sort_column=menu_order&title_li=&echo=0');
	$clean_page_list = preg_replace('/title=\"(.*?)\"/','',$clean_page_list);
	$output .= $clean_page_list;
	$output .= '</ul>';
	echo $output;
}

/**
 * Techozoic Font Resize Script
 *
 * Enqueues and register font resize script used for Techozoic font resize widget.
 *  
 *
 * @access    private
 * @since     1.9.1
 */

	
function tech_font_size_script() {
	$script_dir = get_template_directory_uri() . '/js/';
	wp_register_script('font-size', $script_dir .'font-resize.js', array('jquery'), '1.0');
	wp_enqueue_script('font-size');
}
	
/**
 * Techozoic Navigation Selection Function
 *
 * Used to determine which navigation template to use based on user options.  Used
 * with get_template_part as the name parameter to get correct template.
 * 
 * @return      string    returns text of menu type based on options   
 *
 * @access    public
 * @since     1.8.8
 */
	
	
function tech_nav_select(){
	global $tech;
	switch ($tech['nav_menu_type']){
		case "Two Tier":
			$var = "twotier";
			break;
		case "Standard":
			$var = "standard";
			break;
		case "Dropdown":
			$var = "dropdown";
			break;
		case "WP 3 Menu":
			$var = "wp3";
			break;
	}
	return $var;
}

/**
 * Techozoic $content_width Function
 *
 * Sets $content_width variable used for image sizes by WordPress based on whether the
 * options are set to fixed or fluid width.  If set to fluid width, set to 500 otherwise
 * width is calculated from fixed widths set.
 * 
 * @return      int     content width   
 *
 * @access    private
 * @since     1.8.8
 */

	
function tech_content_width(){
	global $tech;
	$p_width = $tech['page_width'];
	$c_width = $tech['main_column_width'];
	$page = $tech['page_type'];
	if ($page == "Fixed Width" && $p_width != 0 && $c_width != 0) {
		$c_width = $c_width /100;
		$output = $p_width * $c_width;
	} else {
		$output = 500;
	}
	return $output;
}	

/**
 * Techozoic footer text function
 *
 * Used to replace shortcodes used in footer_text option with correct values.
 * 
 *
 * @access    public
 * @since     1.8.8
 */

function tech_footer_text(){
	global $tech, $version;
	$string = stripslashes($tech['footer_text']);
	$shortcode = array('/%BLOGNAME%/i','/%THEMENAME%/i','/%THEMEVER%/i','/%THEMEAUTHOR%/i','/%TOP%/i','/%COPYRIGHT%/i','/%MYSQL%/i');
	$output = array(get_bloginfo('name'),"Techozoic",$version,'<a href="http://clark-technet.com/"> Jeremy Clark</a>','<a href="#top">'. __('Top' ,'techozoic') .'</a>','&copy; '. date('Y'),sprintf(__('%1$d mySQL queries in %2$s seconds.','techozoic'), get_num_queries(),timer_stop(0)));
	echo preg_replace($shortcode, $output, $string);
}

/**
 * Techozoic Sidebar Display Function
 *
 * Determine which sidebar template should be shown based on options.
 * 
 * 
 * @param       string  location of current template function called from 
 *
 * @access    public
 * @since     1.8.8
 */

function tech_show_sidebar($loc) {
	global $tech;
	if ($tech['column'] > 1) {
		switch ($tech['sidebar_pos']) {
			case "Sidebar - Content - Sidebar":
				$left = 1;
				$right = 1;
			break;
			case "Content - Sidebar - Sidebar":
				$left = 0;
				$right = 2;
			break;
			case "Sidebar - Sidebar - Content":
				$left = 2;
				$right = 0;
			break;
			case "Content - Sidebar":
				$left = 0;
				$right = 1;
			break;
			case "Sidebar - Content":
				$left = 1;
				$right = 0;
			break;
		}
		if ($loc == "l" && $left > 0){
			if (function_exists('get_template_part')) {
				get_template_part('sidebar','left');
			} else {
				include (TEMPLATEPATH . "/sidebar-left.php"); 
			}
			if ($left > 1){
				get_sidebar();
			}
		}
		if ($loc == "r" && $right > 0){
			get_sidebar();
			if ($right > 1){
				if (function_exists('get_template_part')) {
					get_template_part('sidebar','left');
				} else {
					include (TEMPLATEPATH . "/sidebar-left.php"); 
				}
			}
		}
	}
}	
	
	
/**
 * Techozoic Social Media Icons Function
 *
 * Echos the social media icon links and images as set in options.
 * 
 * 
 * @param       bool    function called from home page or single page
 *
 * @access    public
 * @since     1.8.8
 */	

function tech_social_icons($home=true){
	global $tech, $post;
	$short_link = home_url()."/?p=".$post->ID;
	$home_icons = explode(',' , $tech['home_social_icons']);
	$single_icons = explode(',' , $tech['single_social_icons']);
	$image = get_template_directory_uri() . "/images/icons";
	$link = get_permalink();
	$title = $post->post_title;
	$email_title = preg_replace('/&/i', 'and',$title);
	$url_title = urlencode($post->post_title);
	$excerpt = urlencode(wp_trim_excerpt($post->post_excerpt));
	$excerpt_mail = wp_trim_excerpt($post->post_excerpt);
	$excerpt_mail = preg_replace("/&#?[a-z0-9]{2,8};/i","",$excerpt_mail);
	$home_title = urlencode(get_bloginfo( 'name' ));
	$social_links = array(
		"Delicious" => "<a href=\"http://delicious.com/post?url={$link}&amp;title={$url_title}\" title=\"" .  __('del.icio.us this!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/delicious_16.png\" alt=\"" .  __('del.icio.us this!','techozoic') . "\" /></a>",
		"Digg" => "<a href=\"http://digg.com/submit?phase=2&amp;url={$link}&amp;title={$url_title} \" title=\"" .  __('Digg this!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/digg_16.png\" alt=\"" .  __('Digg this!','techozoic') . "\"/></a>",
		"Email" => "<a href=\"mailto:?subject={$email_title}&amp;body={$excerpt_mail} {$link}\" title=\"" .  __('Share this by email.','techozoic') . "\"><img src=\"{$image}/email_16.png\" alt=\"" .  __('Share this by email.','techozoic') . "\"/></a>",
		"Facebook" => "<a href=\"http://www.facebook.com/share.php?u={$link}&amp;t={$url_title}\" title=\"" .  __('Share on Facebook!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/facebook_16.png\" alt=\"" .  __('Share on Facebook!','techozoic') . "\"/></a>",
		"LinkedIn" => "<a href =\"http://www.linkedin.com/shareArticle?mini=true&amp;url={$link}&amp;title={$url_title}&amp;summary={$excerpt}&amp;source={$home_title}\" title=\"" .  __('Share on LinkedIn!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/linkedin_16.png\" alt=\"" .  __('Share on LinkedIn!','techozoic') . "\" /></a>",
		"MySpace" => "<a href=\"http://www.myspace.com/Modules/PostTo/Pages/?u={$link}&amp;t={$url_title}\" title=\"" .  __('Share on Myspace!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/myspace_16.png\" alt=\"" .  __('Share on Myspace!','techozoic') . "\"/></a>",
		"NewsVine" => "<a href=\"http://www.newsvine.com/_tools/seed&amp;save?u={$link}\" title=\"" .  __('Share on NewsVine!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/newsvine_16.png\" alt=\"" .  __('Share on NewsVine!','techozoic') . "\"/></a>",
		"StumbleUpon" => "<a href=\"http://www.stumbleupon.com/submit?url={$link}&amp;title={$url_title}\" title=\"" .  __('Stumble Upon this!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/stumbleupon_16.png\" alt=\"" .  __('Stumble Upon this!','techozoic') . "\"/></a>",
		"Twitter" => "<a href=\"http://twitter.com/home?status=Reading%20{$url_title}%20on%20{$short_link}\" title=\"" .  __('Tweet this!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/twitter_16.png\" alt=\"" .  __('Tweet this!','techozoic') . "\"/></a>",
		"Reddit" => "<a href=\"http://reddit.com/submit?url={$link}&amp;title={$url_title}\" title=\"" .  __('Share on Reddit!','techozoic') . "\" target=\"_blank\"><img src=\"{$image}/reddit_16.png\" alt=\"" .  __('Share on Reddit!','techozoic') . "\" /></a>",
		"RSS Icon" => "<a href=\"".get_post_comments_feed_link()."\" title=\"".__('Subscribe to Feed','techozoic')."\"><img src=\"{$image}/rss_16.png\" alt=\"" . __('RSS 2.0','techozoic') . "\"/></a>");
	if ($home == true){
		foreach ($home_icons as $soc){
			echo $social_links[$soc] ."&nbsp;";
		}
	} else {
		foreach ($single_icons as $soc){
			echo $social_links[$soc] ."&nbsp;";
		}
	}
}

/**
 * Techozoic About Icons Function
 *
 * Used to display social media profile links for Techozoic About widget.
 * 
 * 
 * @param       int     if facebook profile link is checked
 * @param       int     if myspace profile link is checked
 * @param       int     if twitter profile link is checked  
 *
 * @access    public
 * @since     1.8.8
 */

function tech_about_icons($fb=0,$my=0,$twitter=0){
	global $tech;
	$fb_profile = $tech['facebook_profile'];
	$my_profile = $tech['myspace_profile'];
	$twitter_profile = $tech['twitter_profile'];
	$image = get_template_directory_uri() . "/images/icons";
	if ($fb !=0){
		echo "<li><a href=\"{$fb_profile}\" title=\"".__('Follow me on Facebook','techozoic')."\"><img src=\"{$image}/facebook_32.png\"></a></li>";
	}
	if ($my !=0){
		echo "<li><a href=\"{$my_profile}\" title=\"".__('Follow me on Myspace','techozoic')."\"><img src=\"{$image}/myspace_32.png\"></a></li>";
	}	
	if ($twitter !=0){
		echo "<li><a href=\"{$twitter_profile}\" title=\"".__('Follow me on Twitter','techozoic')."\"><img src=\"{$image}/twitter_32.png\"></a></li>";
	}
}

/**
 * Techozoic Home Page Comment Preview
 *
 * Comment preview section on home page.  Pull comment excerpt for approved comments
 * displays in an unordered list at bottom of each post. 
 * 
 * @param       string  id of current post to pull comments for
 *
 * @access    public
 * @since     1.8.7
 */

function tech_comment_preview($ID){
	global $comment, $tech;
	$output = "";
	$comment_array = get_comments(array('post_id'=>$ID,'number'=>$tech['comment_preview_num'],'type'=>'comment','status'=>'approve'));
	if ($comment_array) {
		$output .=	'<ul class="comment-preview">';
		foreach($comment_array as $comment){
			$output .= '<li class="comments-link">';
			$output .= '<div class="comment-author">';
			$output .= '<a href="'. get_comment_link() .'" title="'. $comment->comment_author . __(' posted on ','techozoic') . get_comment_date() .'">';
			$output .= $comment->comment_author . __(' posted on ','techozoic') . get_comment_date();
			$output .= '</a>';
			$output .= '</div>';
			$output .= '<div class="comment-text">';
			$output .= get_comment_excerpt($comment->comment_ID);
			$output .= '</div>';
			$output .= '</li>';
		}
		$output .= '</ul>';
	}
	print $output;
}

/**
 * Techozoic Custom Activation Message
 *
 * Used to show a custom activation message with links to theme options page and
 * change log.    
 *
 * @access    private
 * @since     1.8.6
 */

function techozoic_show_notice() { ?>
    <div id="message" class="updated fade">
		<p><?php printf( __( 'Theme activated! This theme contains <a href="%s">theme options</a> and <a href="%s">custom sidebar widgets</a>.<br />&nbsp; See <a href="%s">Change Log</a>.', 'techozoic' ), admin_url( 'themes.php?page=techozoic' ), admin_url( 'widgets.php' ) , get_template_directory_uri() . "/changelog.php\" onclick=\"return changelog('". get_template_directory_uri() ."/changelog.php')\"") ?></p>
    </div>
    <style type="text/css">#message2, #message0 { display: none; }</style>
    <?php
}

/**
 * Techozoic Cufon Font Replacement
 *
 * Registers and enqueues cufon scripts based on user options. 
 * 
 *
 * @access    private
 * @since     1.8.7
 */

/**************************************
	Techozoic Cufon Font Replacement
	Since 1.8.7
***************************************/
function tech_cufon_script() {
	global $tech;
	$script_dir = get_template_directory_uri() . '/js/';
	$tech_adv_font = $tech['cufon_font_list'];
	wp_register_script('cufon', $script_dir .'cufon-yui.js', array('jquery'), '1.0');
	wp_enqueue_script('tech_font', $script_dir .'cufon_fonts/'. $tech_adv_font.'.font.js', array('jquery','cufon'), '1.0');
	wp_enqueue_script('fontscall', $script_dir .'fontscall.js', array('jquery', 'cufon'), '1.0', true);	
}

/**
 * Techozoic cufon options
 *
 * Converts user readable options into class names and outputs the cufon replacement
 * on those classes.    
 *
 * @access    public
 * @since     1.8.7
 */

function tech_cufon_options() {
	global $tech;
	$list = "";
	$headings = explode( ',' , $tech['font_headings']);
	$class = array(
		'Main Blog Title' => '.blog_title',
		'Sidebar Titles' => '.sidebar h2, .sidebar h3, #footer h2',
		'Post Titles' => '.post_title', 
		'H1 Headings' => 'h1', 
		'H2 Headings' => 'h2', 
		'H3 Headings' => 'h3',
		'H4 Headings' => 'h4',
		'H5 Headings' => 'h5');
	foreach ($headings as $head){
		$list .= $class[$head] . ',';
	}
	$output = "<script type='text/javascript'>\n
	Cufon.replace('". $list ."',{hover:true});\n
	</script>\n";
	print $output;
}
	
$meta_box = array(
    'id' => 'tech-meta-box',
    'title' => 'Techozoic Options',
    'context' => 'side',
    'priority' => 'low',
    'fields' => array(
        array(
            'name' => 'Sidebar',
            'id' => 'Sidebar_value',
            'type' => 'checkbox',
			'title' => 'Disable Sidebar',
			'description' => 'Checking the box will disable the sidebar showing on this post/page single view.'
        ),
		array(
            'name' => 'Nav',
            'id' => 'Nav_value',
            'type' => 'checkbox',
			'title' => 'Disable Navigation Menu',
			'description' => 'Checking the box will disable the navigation menu on this post/page single view.'
        )
    )
);
	
/**
 * Techozoic meta boxes
 *
 * Used to output meta box for disabling the navigation menu and sidebars
 * on single pages/posts 
 *
 * @access    private
 * @since     1.8.6
 */

function tech_new_meta_boxes() {
    global $meta_box, $post;
    
    // Use nonce for verification
    echo '<input type="hidden" name="techozoic_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        echo '<tr>',
                '<th><label for="', $field['id'], '">', $field['title'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '
', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '
', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo	'<td>',
				'<tr><td colspan="3">',$field['description'],'</td></tr>',
				'</tr>';
    }
    
    echo '</table>';

}
 
/**
 * Techozoic create meta boxes
 *
 * Creates the meta boxes setup in tech_new_meta_boxes function
 *    
 *
 * @access    private
 * @since     1.8.6
 */

function tech_create_meta_box() {
	global $meta_box;
	add_meta_box($meta_box['id'], $meta_box['title'], 'tech_new_meta_boxes', 'post', $meta_box['context'], $meta_box['priority']);
	add_meta_box($meta_box['id'], $meta_box['title'], 'tech_new_meta_boxes', 'page', $meta_box['context'], $meta_box['priority']);
}

/**
 * Techozoic save metabox data
 *
 * Verifies the nonce of the meta box form and saves the option to the database using
 * the update_post_meta function.
 * 
 * @param       string  post id of current post being edited
 *
 * @access    private
 * @since     1.8.6
 */

function tech_save_postdata( $post_id ) {
    global $meta_box;
    
    // verify nonce
	if (isset($_POST['techozoic_meta_box_nonce'])){
		if (!wp_verify_nonce($_POST['techozoic_meta_box_nonce'], basename(__FILE__))) {
		   return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		
		foreach ($meta_box['fields'] as $field) {
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];
			
			if ($new && $new != $old) {
				update_post_meta($post_id, $field['id'], $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
			}
		}
	}
}

if(function_exists('add_theme_support')) {
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'single-post-thumbnail', $content_width, 9999 ); 
	//WP 2.9 Post Thumbnail Support
	add_theme_support('automatic-feed-links');
	//WP Auto Feed Links
}
if(function_exists('register_nav_menus')) {
	register_nav_menus( array(
		'primary' => __( 'Header Navigation', 'techozoic' ),
		'sidebar' => __( 'Sidebar Navigation', 'techozoic'),
		'footer' => __('Footer Navigation', 'techozoic'),
	) );
}

/**
 * Techozoic add dashboard widget
 *
 * Add dashboard widget defined in techozoic_dashboard_widget.
 *
 * @access    private
 */

function tech_dashboard_widgets() {
   	global $wp_meta_boxes;
   	wp_add_dashboard_widget('techozoic_dashboard_widget', 'Techozoic Theme Setup', 'techozoic_dashboard_widget');
}

/**
 * Techozoic dashboard widget content
 *
 * Adds dashboard widget with links to options pages, support, documentation, and
 * donate links.
 * 
 *  
 *
 * @access    private
 * @since     1.9.3
 */

function techozoic_dashboard_widget() { ?>
   	<div style="float:left;width: 46%;margin:1% 10px;">
	<div class="alignleft">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="10999960">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		</div>
		<p>
		<?php _e('Thank you for using the Techozoic Theme.  ' ,'techozoic'); 
		if (current_user_can('edit_theme') || current_user_can('edit_theme_options')) { 
			printf(__('Visit the %s to start customizing Techozoic.  ' ,'techozoic'),'<a href="themes.php?page=techozoic" title="' . __("options page" ,"techozoic").'">'.__("options page" ,"techozoic").'</a>'); 
			} 
		printf(__('If your having problems or would like to suggest a new feature, please visit the %s.' ,'techozoic'), '<a href="http://clark-technet.com/theme-support/techozoic/" title="' .__('Support Forum' ,'techozoic').'"> '.__('support forum' ,'techozoic').'</a>')?>
		</p>
		</div>
		<?php techozoic_links_box('tech_links_front'); ?>
		<div class="clear"> </div>
		<?php if (current_user_can('edit_theme') || current_user_can('edit_theme_options')) { ?>
		<h5 style="margin:8px 0 0;"><?php _e('Techozoic Settings Pages','techozoic'); ?></h5>
		<?php
			echo techozoic_top_menu(); 
		}
}

/**
 * Techozoic first run options
 *
 * Creates default options if none are set and creates folders for image uploads
 * 
 * @access    private
 */

function tech_first_run_options() {
	global $version;
	$header_folder = TEMPLATEPATH. "/uploads/images/headers";
	$background_folder = TEMPLATEPATH. "/uploads/images/backgrounds";
  	$check = get_option('techozoic_activation_check');
  	if ($check != $version || !file_exists($header_folder) || !file_exists($background_folder)) {
		include_once(TEMPLATEPATH . '/options/tech-init.php');
		tech_update_options();
		tech_create_folders(TEMPLATEPATH . '/uploads');
    		// Add marker so it doesn't run in future
  		add_option('techozoic_activation_check', $version);
		update_option('techozoic_activation_check', $version);
  	}
}//End first_run_options

/**
 * Techozoic dropdown javascript
 *
 * Enqueues javascript used for cross browser compatiblity with dropdown navigation
 * menus.
 * 
 * @access private 
 */

function tech_dropdown_js(){
	wp_enqueue_script('dropdown', get_template_directory_uri() . '/js/dropdown.js',array('jquery'),'3.0' );
}//End Dropdown_js

/**
 * Techozoic breadcrumb navigation
 *
 * Displays breadcrumb navigation if option is set.
 * 
 * @access    public
 */

function tech_breadcrumbs() {
	// Thanks to dimox for the code
	//http://dimox.net/wordpress-breadcrumbs-without-a-plugin/
	global $tech;
	$delimiter = '&raquo;';
	$name =  __('Home' ,'techozoic');
	$currentBefore = '<span class="current">';
	$currentAfter = '</span>';
	 
	if ( !is_home() || !is_front_page() || is_paged() ) {
 
		echo '<div id="crumbs">';
 
		global $post;
		$home = home_url();
		echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';
 
		if ( is_category() ) {
		  global $wp_query;
		  $cat_obj = $wp_query->get_queried_object();
		  $thisCat = $cat_obj->term_id;
		  $thisCat = get_category($thisCat);
		  $parentCat = get_category($thisCat->parent);
		  if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
		  echo $currentBefore . __('Archive for category &#39;' ,'techozoic');
		  single_cat_title();
		  echo '&#39;' . $currentAfter;
	 
		} elseif ( is_day() ) {
		  echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
		  echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
		  echo $currentBefore . get_the_time('d') . $currentAfter;
	 
		} elseif ( is_month() ) {
		  echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
		  echo $currentBefore . get_the_time('F') . $currentAfter;
	 
		} elseif ( is_year() ) {
		  echo $currentBefore . get_the_time('Y') . $currentAfter;
	 
		} elseif ( is_single() && !is_attachment() ) {
		  $cat = get_the_category(); 
		  $cat = $cat[0];
		  echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
		  echo $currentBefore;
		  the_title();
		  echo $currentAfter;
	 
		} elseif ( is_page() && !$post->post_parent ) {
		  echo $currentBefore;
		  the_title();
		  echo $currentAfter;
	 
		} elseif ( is_page() && $post->post_parent ) {
		  $parent_id  = $post->post_parent;
		  $breadcrumbs = array();
		  while ($parent_id) {
			$page = get_page($parent_id);
			$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
			$parent_id  = $page->post_parent;
		  }
		  $breadcrumbs = array_reverse($breadcrumbs);
		  foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
		  echo $currentBefore;
		  the_title();
		  echo $currentAfter;
	 
		} elseif ( is_search() ) {
		  echo $currentBefore . __('Search results for &#39;' ,'techozoic') . get_search_query() . '&#39;' . $currentAfter;
	 
		} elseif ( is_tag() ) {
		  echo $currentBefore . __('Posts tagged &#39;' ,'techozoic');
		  single_tag_title();
		  echo '&#39;' . $currentAfter;
	 
		} elseif ( is_author() ) {
		   global $author;
		  $userdata = get_userdata($author);
		  echo $currentBefore . __('Articles posted by ' ,'techozoic') . $userdata->display_name . $currentAfter;
	 
		} elseif ( is_404() ) {
		  echo $currentBefore . __('Error 404' ,'techozoic') . $currentAfter;
		}
	 
		if ( get_query_var('paged') ) {
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
		  echo __('Page' , 'techozoic') . ' ' . get_query_var('paged');
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}
 
		echo '</div>';
 
	}
}

/**
 * Techozoic get options
 *
 * Pull options out of database and returns them.
 * 
 * @return      string    array of set options   
 *
 * @access    public
 */

function get_tech_options() {
	$tech = get_option('techozoic_options');
	return $tech;
}

/**
 * Techozoic thickbox image paths
 *
 * Fixes paths to loading and close images used with thickbox. 
 * 
 *
 * @access    private
 */

function tech_thickbox_image_paths() {
	$thickbox_path = get_option('siteurl') . '/wp-includes/js/thickbox/';
	echo "<script type=\"text/javascript\">\n";
	echo "	var tb_pathToImage = \"${thickbox_path}loadingAnimation.gif\";\n";
	echo "	var tb_closeImage = \"${thickbox_path}tb-close.png\";\n";
	echo "</script>\n";
}

/**
 * Techozoic enqueue thickbox
 *
 * Enqueues thickbox script and stylesheet to be added to wp_head
 * 
 *
 * @access    private
 */

function tech_enque_thickbox() {
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}

/**
 * Techozoic thickbox
 *
 * Replaces img links with img links with thickbox class and rel for grouping images
 * based on post id.
 * 
 * @param       string  post content
 * @return      string  string replaced post content   
 *
 * @access    private
 * @since     1.9.3
 */

function tech_thickbox($content) {
	global $post;
	$pattern = array( '/<a([^>]*)href=[\'"]([^"\']+).(gif|jpeg|jpg|png)[\'"]([^>]*>)/i', '/<a class="thickbox" rel="%ID%" href="([^"]+)"([^>]*)class=[\'"]([^"\']+)[\'"]([^>]*>)/i' );
	$replacement = array( '<a class="thickbox" rel="%ID%" href="$2.$3"$1$4', '<a class="thickbox" rel="%ID% $3" href="$1"$2$4' );
	$content = preg_replace($pattern, $replacement, $content);
	return str_replace('%ID%', $post->ID, $content);
}

if ($tech['thickbox'] =="On"){
	add_action('wp_footer', 'tech_thickbox_image_paths');
	add_filter('the_content', 'tech_thickbox', 65 );
	add_action('wp_print_styles','tech_enque_thickbox');
} // End if thickbox check
	
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ){
        add_action( 'admin_notices', 'techozoic_show_notice' );  // Shows custom theme activation notice with links to option page and changelog
}
if ($tech['nav_menu_type'] == "Dropdown" || $tech['nav_menu_type'] == "WP 3 Menu"){	
	add_action('wp_print_styles','tech_dropdown_js');
}
if ($tech['google_font'] == "Enable") {
	add_action('wp_print_styles','tech_google_font');
}
if ($tech['cufon_font'] == "Enable") {
	add_action('template_redirect', 'tech_cufon_script');  // Calls script to add Cufon font replacement scripts See - http://cufon.shoqolate.com/
	add_action('wp_head', 'tech_cufon_options');
}
if ( is_active_widget(false ,false, 'techozoic_font_size') ) {
	add_action('template_redirect', 'tech_font_size_script');
}
add_filter('get_comments_number', 'tech_comment_count', 0);
add_filter('the_excerpt', 'tech_excerpt_filter'); // Replaces [...] at end of excerpt with link to single post page.	
add_action('tech_footer', 'tech_footer_text'); 	// Adds custom footer text defined on option page to footer.
add_action('admin_menu', 'tech_create_meta_box');  	// Creates custom meta box for disabling sidebar on page by page basis
add_action('save_post', 'tech_save_postdata');  // Saves meta box data to postmeta table
if ( !isset($_GET['preview'])){ //Doesn't run when previewing the theme before activating the theme
	add_action('wp_head', 'tech_first_run_options'); //Calls tech_init.php which sets up default options in database and creates folder to hold custom images
	add_action('admin_head', 'tech_first_run_options'); //Same as above but works for the admin side
}
add_action('wp_dashboard_setup', 'tech_dashboard_widgets'); //Add Techozoic dashboard widget with info for theme and donate button
?>