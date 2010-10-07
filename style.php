<?php 
	global $tech;
	$tech = get_option('techozoic_options');
	if(!headers_sent()) {
		header('Content-type: text/css');   
		header("Cache-Control: must-revalidate"); 
		$offset = 72000 ; 
		$ExpStr = 'Expires: ' . gmdate("D, d M Y H:i:s", time() + $offset) . ' GMT'; 
		header($ExpStr);
	}
	// Setup Custom Function
	function sanitize_text($text) {
		$text = stripslashes($text);
		$text = preg_replace('/<(.|\n)*?>/i', '', $text);
		return $text;
	}
	function sanitize_num($text) {
		$text = stripslashes($text);
		$text = preg_replace('/[^0-9]/', '', $text);
		return $text;
	}
	function tech_font($font) {
		switch ($font){
			case "Trebuchet MS":
				$font = '"Trebuchet MS", Helvetica';
			break;
			case "Tahoma":
				$font = $font . ', Geneva';
			break;
			case "Times New Roman":
				$font = '"Times New Roman", Times';
			break;
			case "Lucida Sans Unicode":
				$font = '"Lucida Sans Unicode" , "Lucida Grande"';
			break;
			case "Impact":
				$font = 'Impact, Charcoal';
			break;
		}
		return $font;
	}
	
	function tech_color_verify($color){
		if ($color){
			if ($color[0] != '#')
				$color = '#'.$color;
			return $color;
		}
	}
	
	$tech['blog_title_display'] = '';
	$tech['blog_title_cursor'] = '';
	$tech['header_align'] = strtolower($tech['header_align']);
	$tech['header_v_align'] = strtolower($tech['header_v_align']);
	$bg_image_repeat = explode(',', $tech['bg_image_repeat']);
	$tech_bg_repeat = "";
	if (in_array("X" , $bg_image_repeat)) {
		$tech_bg_repeat = "-x";
	}
	if (in_array("Y" , $bg_image_repeat)) {
		if ($tech_bg_repeat == "-x"){
			$tech_bg_repeat = "";
		} else {
			$tech_bg_repeat = "-y";
		}
	}
	$content_bg_image_repeat = explode(',', $tech['content_bg_image_repeat']);
	$tech_content_bg_repeat = "";
	if (in_array("X" , $content_bg_image_repeat)) {
		$tech_content_bg_repeat = "-x";
	}
	if (in_array("Y" , $content_bg_image_repeat)) {
		if ($tech_content_bg_repeat == "-x"){
			$tech_content_bg_repeat = "";
		} else {
			$tech_content_bg_repeat = "-y";
		}
	}
	if ($tech['test'] != 'set'){
		$tech['column'] = 3;
		$tech['sidebar_pos'] = 'Sidebar - Content - Sidebar';
		$tech['header'] = 'Grunge';
		$tech['header_font'] = 'Verdana';
		$tech['body_font'] = 'Lucida Sans Unicode';
		$tech['default_font'] = 'Lucida Sans Unicode';
		$tech['color_scheme'] = 'Blue';
		$tech['main_column_width'] = 0;
		$tech['sidebar_width'] = 0;
		$tech['page_width'] = 0;
		$tech['body_font_size'] = 10;
		$tech['blog_title_align'] = 'float:left;margin-left:10px';
	}
	
	if ($tech['page_type'] == 'Fixed Width'){$tech['sign'] = 'px';} else {$tech['sign'] = '%';}
	if ($tech['page_width'] == 0 ) { $tech['page_width'] = '95'; $tech['sign'] = '%';}
	if ($tech['page_type'] == 'Fluid Width' && $tech['page_width'] > 101)  $tech['page_width'] = '100';
	if ($tech['blog_title'] == 'No')  $tech['blog_title_display'] = 'display:none';
	if ($tech['blog_title_text'] == 'Single Post Title') $tech['blog_title_cursor'] = 'cursor:default;';
	$tech_blog_title_align_check ="";
	$tech_nav_align_check ="";
	switch ($tech['blog_title_align']){
		case "Left":
			$tech['blog_title_align'] = 'float:left;margin-left:10px';
		break;
		case "Right":
			$tech['blog_title_align'] = 'float:right;margin-right:10px';
		break;
		case "Center":
			$tech['blog_title_align'] = 'float:left;position:relative;left:-50%';
			$tech_blog_title_align_check = "Center";
		break;
	}
	switch ($tech['nav_align']){
		case "Center":
			$tech['nav_align'] = 'float:left;position:relative;left:-50%';
			$tech_nav_align_check = "Center";
		break;
		case "Left":
			$tech['nav_align'] = '';
		break;
	}
	$tech['default_font'] = tech_font($tech['default_font']);
	$tech['body_font'] = tech_font($tech['body_font']);
	$tech['header_font'] = tech_font($tech['header_font']);
	$tech['nav_font'] = tech_font($tech['nav_font']);
	$cufon_header_size = $tech['main_heading_font_size'] ;
	$cufon_sidebar_size = "1.6";
	$tech_color_scheme = $tech['color_scheme'];
	$tech_default_color = array(
		"Blue" => 	array ('#A0B3C2','#A0B3C2','#597EAA','#114477','#2C4353','#2C4353','#E3E3E3','#E3E3E3','#F7F7F7'),
		"Khaki" => 	array ('#c7c69a','#c7c69a','#6E0405','#B53839','#2C4353','#2C4353','#E3E3E3','#E3E3E3','#F7F7F7'),
		"Red" => 	array ('#AB2222','#AB2222','#D33535','#B53839','#2C4353','#2C4353','#E3E3E3','#E3E3E3','#F7F7F7'),
		"Grunge" => 	array ('#534E3E','#534E3E','#78BFBF','#78BFBF','#2C4353','#2C4353','#E3E3E3','#E3E3E3','#F7F7F7')
	);
	$tech_color_names = array('Blue','Khaki','Red','Grunge');
	if (in_array($tech['color_scheme'], $tech_color_names)){
		$tech_bg_color = 	$tech_default_color[$tech_color_scheme][0];
		$tech_acc_color = 	$tech_default_color[$tech_color_scheme][1];
		$tech_link_color = 	$tech_default_color[$tech_color_scheme][2];
		$tech_link_hov_color = 	$tech_default_color[$tech_color_scheme][3];
		$tech_visit_link_color = 	$tech_default_color[$tech_color_scheme][4];
		$tech_text_color = 	$tech_default_color[$tech_color_scheme][5];
		$tech_nav_bg_color = 	$tech_default_color[$tech_color_scheme][6];
		$tech_post_bg_color = 	$tech_default_color[$tech_color_scheme][7];
		$tech_content_bg_color =$tech_default_color[$tech_color_scheme][8];
	} elseif ($tech['color_scheme'] == 'Custom 1'){
		$tech_bg_color = 	tech_color_verify($tech['cust_bg_color1']);
		$tech_acc_color =	tech_color_verify($tech['cust_acc_color1']);
		$tech_link_color = 	tech_color_verify($tech['cust_link_color1']);
		$tech_link_hov_color = 	tech_color_verify($tech['cust_link_hov_color1']);
		$tech_visit_link_color = 	tech_color_verify($tech['cust_link_visit_color1']);
		$tech_text_color =	tech_color_verify($tech['cust_text_color1']);
		$tech_nav_bg_color =	tech_color_verify($tech['cust_nav_bg_color1']);
		$tech_post_bg_color = 	tech_color_verify($tech['cust_post_bg_color1']);
		$tech_content_bg_color =tech_color_verify($tech['cust_content_bg_color1']);
	} else {
		$tech_bg_color = 	tech_color_verify($tech['cust_bg_color2']);
		$tech_acc_color =	tech_color_verify($tech['cust_acc_color2']);
		$tech_link_color = 	tech_color_verify($tech['cust_link_color2']);
		$tech_link_hov_color = 	tech_color_verify($tech['cust_link_hov_color2']);
		$tech_visit_link_color = 	tech_color_verify($tech['cust_link_visit_color2']);		
		$tech_text_color = 	tech_color_verify($tech['cust_text_color2']);
		$tech_nav_bg_color =	tech_color_verify($tech['cust_nav_bg_color2']);
		$tech_post_bg_color = 	tech_color_verify($tech['cust_post_bg_color2']);
		$tech_content_bg_color =tech_color_verify($tech['cust_content_bg_color2']);
	}
	$tech_sidebar_h3_font_size = $tech['side_heading_font_size'] - .4;
	$tech_wp_content = WP_CONTENT_URL;
	$header_folder = TEMPLATEPATH. "/uploads/images/headers";
	if (!file_exists($header_folder)){
		$home = get_bloginfo('template_directory');
	} else {
		$home = get_bloginfo('template_directory') ."/uploads";
	}
	$tech_drop_shadow_classes = ".noclass";
	if ($tech['drop_shadow']){
		$tech_drop_shadow = explode( ',' , $tech['drop_shadow']);
		$tech_drop_shadow_class_map = array(
		"Header Text" => "#headerimg",
		"Post Boxes" => ".home .narrowcolumn .entry, .home .widecolumn .entry, .top",
		"Images" => ".entry img, .entrytext img"
		);
		foreach ($tech_drop_shadow as $tds){
			$tech_drop_shadow_classes .= ",". $tech_drop_shadow_class_map[$tds];
		}
	}

echo <<<CSS
/*Techozoic {$tech['ver']}*/

/*Variable Styles*/
#page{ 
background:{$tech_content_bg_color} url({$tech['content_bg_image']}) repeat{$tech_content_bg_repeat} top left;
}
#header{
background-color:{$tech_content_bg_color};
}
body{
font-family:{$tech['default_font']}, Sans-Serif;
font-size: {$tech['body_font_size']}px;
background:{$tech_bg_color} url({$tech['bg_image']}) repeat{$tech_bg_repeat} top left;
}
.techozoic_font_size{
font-size: {$tech['body_font_size']}px;
}
.narrowcolumn .entry,.widecolumn .entry,.top {
font-family:{$tech['body_font']}, Sans-Serif;
background-color:{$tech_post_bg_color};
}
h1,h2,h3,h4,h5{
font-family:{$tech['header_font']}, Sans-Serif;
}
h1{
font-size: {$tech['main_heading_font_size']}em;
}
h2 {
font-size: {$tech['post_heading_font_size']}em;
}
.sidebar h2, #footer h2, .widgettitle {
font-size: {$tech['side_heading_font_size']}em;
}
.sidebar h3 {
font-size: {$tech_sidebar_h3_font_size}em;
}
#content {
font-size: {$tech['post_text_font_size']}em;
}
acronym,abbr,span.caps,small,.trackback li,#commentform input,#commentform textarea,.sidebar {
font-size: {$tech['small_font_size']}em;
}
.description, ul#nav a, ul#admin a, #dropdown li.current_page_item a:hover, .menu li.current-menu-item a:hover, #dropdown li.current_page_item ul a, .menu li.current-menu-item ul a, ul#nav li.current_page_item a:hover,#headerimg h1 a, #nav2 a, #nav2 li.current_page_item a:hover,#subnav a, #subnav a:visited, #dropdown a, .menu li a, .menu li.current-menu-item a{
color: {$tech_acc_color};
}
.author,#searchform #s,ul#nav li.current_page_item,#nav2 li.current_page_item,#nav2 li.current_page_parent,ul#nav2 li.current_page_ancestor,#dropdown li.current_page_item, .menu li.current-menu-item, #searchsubmit:hover,#catsubmit:hover,#wp-submit:hover,.postform,#TB_ajaxContent {
background-color: {$tech_acc_color} ;
}
ul#nav li,ul#admin li, #nav2 li, ul#dropdown li, .menu li{
background-color: {$tech_nav_bg_color};
}
ul#nav li,ul#admin li, #nav2 li, ul#dropdown li a, .menu li a{
font-family:{$tech['nav_font']}, Sans-Serif;
font-size:{$tech['nav_text_font_size']}em;
}
.post_date {
background-color:{$tech_acc_color};
}
.narrowcolumn .entry,.widecolumn .entry,.tags {
border-top:1px {$tech_acc_color} solid;
}
.tags {
border-bottom:1px {$tech_acc_color} solid;
}
#content,h2,h2 a,h2 a:visited,h3,h3 a,h3 a:visited,h4,h5{
color:{$tech_text_color};
}
a,h2 a:hover,h3 a:hover,.commentdiv a, .commentdiv a:visited,#user_login,#user_pass,.postform,.commentdiv span, #sidenav a:visited {
color:{$tech_link_color}; 
text-decoration:none;
}
.date_post,#searchform #s {
color:{$tech_post_bg_color}; 
text-decoration:none;
}
a:hover,#headerimg h1 a:hover {
color:{$tech_link_hov_color}; 
text-decoration:underline;
}
a:visited{
color:{$tech_visit_link_color};
}
ul#nav li.current_page_item a:hover, ul#nav2 li.current_page_item a:hover, ul#nav2 li.current_page_parent a:hover {
color:{$tech_acc_color};
}
#headerimg {
{$tech['blog_title_display']};
{$tech['blog_title_align']};
}
#headerr, #headerl{
height: {$tech['header_height']}px;
}
.single #headerimg h1 a:hover {
{$tech['blog_title_cursor']}
text-decoration:none;
}
{$tech_drop_shadow_classes}{
-moz-box-shadow:none !important;
-webkit-box-shadow: none !important;
opacity:1 !important;
}
CSS;
	if ($tech_blog_title_align_check == "Center") {
echo <<<CSS
#headerimgwrap {
float:left;
position:relative;
left:50%;
}
CSS;
	}
	if ($tech['column'] == 1) {
		if ($tech['main_column_width'] == 0) 
			$tech['main_column_width'] = 100; 
		$tech['main_column_width'] = $tech['main_column_width'] - 6;
echo <<<CSS
#page, #header {
width: {$tech['page_width']}{$tech['sign']};
}
.narrowcolumn {
float:left;
margin:0;
padding:0 2% 20px 3%;
width:{$tech['main_column_width']}%;
}
CSS;
	} else if ($tech['column'] == 2) {
		if ($tech['main_column_width'] == 0 && $tech['sidebar_width'] != 0) {
			$tech['main_column_width'] = 97 - $tech['sidebar_width'];
		} elseif ($tech['main_column_width'] == 0){
			$tech['main_column_width'] = 70;
		}
		if ($tech['sidebar_width'] == 0 && $tech['main_column_width'] != 70) {  
			$tech['sidebar_width'] = 96 - $tech['main_column_width'];
		} elseif ($tech['sidebar_width'] == 0){
			$tech['sidebar_width'] = 23;
		}
		$tech['main_column_width'] = $tech['main_column_width'] - 5;
		$tech['sidebar_width'] = $tech['sidebar_width'] - 3;
		if ($tech['sidebar_pos'] =='Content - Sidebar') {
echo <<<CSS
#page, #header {
width: {$tech['page_width']}{$tech['sign']};
}
.narrowcolumn {
float:left;
margin:0;
padding:0 2% 20px 3%;
width:{$tech['main_column_width']}%;
}
#r_sidebar {
float:right;
padding:10px 2% 0 1%;
width:{$tech['sidebar_width']}%
}
CSS;
			} else { 
echo <<<CSS
#page, #header {
width: {$tech['page_width']}{$tech['sign']};
}
.narrowcolumn {
float:left;
margin:0;
padding:0 3% 20px 2%;
width:{$tech['main_column_width']}%;
}
#l_sidebar {
float:left;
padding:10px 1% 0 2%;
width:{$tech['sidebar_width']}%
}
CSS;
		}
	} else {
		if ($tech['main_column_width'] == 0 && $tech['sidebar_width'] != 0) {
			$tech['main_column_width'] = 96 - ($tech['sidebar_width'] * 2);
		} elseif ($tech['main_column_width'] == 0) {
			$tech['main_column_width'] = 55;
		}
		if ($tech['sidebar_width'] == 0 && $tech['main_column_width'] != 55) {  
			$tech['sidebar_width'] = (98 - $tech['main_column_width']) / 2;
		} elseif ($tech['sidebar_width'] == 0) {
			$tech['sidebar_width'] = 22;
		} 
		$tech['main_column_width'] = $tech['main_column_width'] - 2;
		$tech['sidebar_width'] = $tech['sidebar_width'] - 2;
		if ($tech['sidebar_pos'] =='Content - Sidebar - Sidebar') {
echo <<<CSS
#page, #header {
width: {$tech['page_width']}{$tech['sign']}
}
.narrowcolumn {
float:left;
margin:0 0 0 2%;
padding:0 0 20px 0;
width:{$tech['main_column_width']}%;
}
#l_sidebar {
float:right;
padding:10px 0 0 2%;
width:{$tech['sidebar_width']}%
}
#r_sidebar {
float:right;
clear:right;
padding:10px 2% 0 0;
width:{$tech['sidebar_width']}%
}
CSS;
		} elseif ($tech['sidebar_pos'] =='Sidebar - Content - Sidebar') { 
echo <<<CSS
#page, #header {
width: {$tech['page_width']}{$tech['sign']}
}
.narrowcolumn {
float:left;
margin:0 1%;
padding:0 0 20px 0;
width:{$tech['main_column_width']}%;
}
#r_sidebar {
float:right;
padding:10px 2% 0 0;
width:{$tech['sidebar_width']}%
}
CSS;
$tech['sidebar_width'] = $tech['sidebar_width'] - 2;
echo <<<CSS
#l_sidebar {
float:left;
padding:10px 0 0 2%;
width:{$tech['sidebar_width']}%
}
CSS;
		} else {
echo <<<CSS
#page, #header {
width: {$tech['page_width']}{$tech['sign']}
}
.narrowcolumn {
float:left;
margin:0 1%;
padding:0 0 20px 0;
width:{$tech['main_column_width']}%;
}
#r_sidebar {
float:left;
padding:10px 2% 0 0;
width:{$tech['sidebar_width']}%
}
CSS;
$tech['sidebar_width'] = $tech['sidebar_width'] - 2;
echo <<<CSS
#l_sidebar {
float:left;
padding:10px 0 0 2%;
width:{$tech['sidebar_width']}%
}
CSS;
		}
	}
echo <<<CSS
ul#nav,ul#nav2,ul#dropdown,ul.menu{
{$tech['nav_align']};
}
CSS;
	if ($tech['nav_button_width'] != 0) { 
echo <<<CSS
ul#nav li, ul#admin li, #nav2 li,#dropdown li,.menu li{
width: {$tech['nav_button_width']}em;
} 
CSS;
	}
	if ($tech_nav_align_check == "Center") {
echo <<<CSS
#navwrap {
float:left;
position:relative;
left:50%;
}
ul#admin{
margin-top:30px !important;
}
ul#subnav{
position:relative;
clear:both;
left:-50%;
}
CSS;
	}
	$tech_hwidget_height = $tech['header_height'] - 40;
	switch ($tech['header']){ 
		case "Defined Here": 
echo <<<CSS
#header {
background:url({$tech['header_image_url']}) no-repeat {$tech['header_v_align']} {$tech['header_align']} {$tech_content_bg_color};
height: {$tech['header_height']}px;
}
.hleft, .hright {
height: {$tech_hwidget_height}px;
}
CSS;
		break;
		case "Rotate":
echo <<<CSS
#header {
background:url({$home}/rotate.php) no-repeat {$tech['header_v_align']} {$tech['header_align']} {$tech_content_bg_color};
height: 200px;
}
.hleft, .hright {
height: 160px;
}
CSS;
		break;
		case "Landscape":
echo <<<CSS
#header {
background:url({$home}/images/headers/{$tech['header']}.jpg) no-repeat {$tech['header_v_align']} {$tech['header_align']} {$tech_content_bg_color};
height: 170px;
}
.hleft, .hright {
height: 110px;
}
CSS;
		break;
		case "none":
echo <<<CSS
#header {
height: {$tech['header_height']}px;
}
.hleft, .hright {
height: {$tech_hwidget_height}px;
}
CSS;
		break;
		default:
echo <<<CSS
#header {
background:url({$home}/images/headers/{$tech['header']}.jpg) no-repeat {$tech['header_v_align']} {$tech['header_align']} {$tech_content_bg_color};
height: {$tech['header_height']}px;
}
.hleft, .hright {
height: {$tech_hwidget_height}px;
}
CSS;
		break;
	}
include("default-css.php");
/*Custom Styles Defined In Options*/
echo $tech['custom_styles'];
?>