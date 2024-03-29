<?php
    	global $themename, $shortname, $options, $tech_error;
    	if ( isset($_REQUEST['saved']) && $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'. sprintf(__("%s settings saved","techozoic"), $themename) . '</strong></p></div>';
    	if ( isset($_REQUEST['reset']) && $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'. sprintf(__("%s settings reset","techozoic"), $themename) . '</strong> </p></div>';
		if ( isset($_REQUEST['message']) && $_REQUEST['message'] ) {
			if (isset($_REQUEST['error']) && $_REQUEST['error'] ) {
				echo '<div id="message" class="updated fade"><p><strong>'. $tech_error[$_REQUEST['error']] .' </strong> </p></div>';
				} else { 
				echo '<div id="message" class="updated fade"><p><strong>' . __("Image Uploaded","techozoic") . '</strong> </p></div>';
				}
			}
	$tech = get_option('techozoic_options');
	if ($tech['image_location'] == 'theme') {
		$header_folder = TEMPLATEPATH . "/uploads/images/headers";
	} else {
		$header_folder = WP_CONTENT_DIR . "/techozoic/images/headers";
	}
	$header_select_nonce = wp_create_nonce  ('header-select');
	$header_delete_nonce = wp_create_nonce  ('header-delete');
?>
	<div class="tech_head tech_wrap">
	<?php techozoic_admin_tabs('header');?>
	<h2 style="border:none;"><?php printf(__("%s Header Settings","techozoic"),$themename);?></h2>
	<div style="clear:both;"></div>
		<?php techozoic_links_box();?>
	<div class="tech_form_wrap">
		<h3><?php _e('Upload Header Image','techozoic')?></h3>
		<p><?php _e('Max Dimensions: 1000 px wide X 200 px high','techozoic')?><br />
		<?php _e('Supported format: jpg, jpeg, gif, png','techozoic')?> <br />
		<em><?php _e('Recommend format:</em> jpg, jpeg, png','techozoic')?><br />
		<?php _e('After image is uploaded it will appear in the list below and can be selected in the list below to be used as the Header Image.','techozoic')?></p>
<?php
	$dir = TEMPLATEPATH. "/uploads/images/headers/";
		if (is_writable($dir)) {
?>
			<form enctype="multipart/form-data" encoding="multipart/form-data" method="post">
				<input type="file" name="file" /><br />
				<span class="tech_submit submit save">
				<input class="button-primary" type="submit" name="submit" value="<?php _e('Upload','techozoic')?>" />
				<?php wp_nonce_field('techozoic_form_upload','techozioc_nonce_field_upload'); ?>
				</span><br /><br />
			</form>
<?php 		} else { 
			echo "<div class=\"updated fade\">" . sprintf(__('Please make sure <strong>%s</strong> is writable to enable upload of headers.','techozoic'),$dir) . "</div>"; 
	}?>
		<h3><?php _e('Image Upload Location','techozoic')?></h3>
		<form method="post" name="tech_image_location">
		<table>
		<tr><td><input type="radio" <?php if ( $tech['image_location']  == "wp-content") { echo ' checked="checked"'; }?> value="wp-content" name="image_location" / > <?php _e('<code>wp-content/techozoic</code> folder' , 'techozoic'); ?> </td></tr>
		<tr><td><input type="radio" <?php if ( $tech['image_location']  == "theme") { echo ' checked="checked"'; }?> value="theme" name="image_location" / > <?php _e('<code>wp-content/themes/techozoic-fluid/uploads</code> folder' , 'techozoic'); ?> </td></tr>
		</table>
		<span class="tech_submit submit save">
		<input class="button-primary" name="tech_image_location" type="submit" value="<?php _e('Save Settings','techozoic')?>" />
		<?php wp_nonce_field('techozoic_form_submit','techozioc_nonce_field_submit'); ?>		
		</span>
		</form>
		<br />
		<br />
		<h3><?php _e('Header Image Settings','techozoic')?></h3>
		<form method="post" name="tech_header_height">
		<table>
		<tr><td><?php _e('Height of Container:','techozoic')?> </td><td><input name="header_height" id="header_height_2" type="text" value="<?php echo stripslashes($tech['header_height']);?>" size="5" />px</td></tr>
		<tr><td><?php _e('Header Image Horizontal Alignment:','techozoic')?> </td><td><select name="header_align" id="header_align_2">
                <option <?php if ( $tech['header_align']  == "Left") { echo ' selected="selected"'; }?>>Left</option>
				<option <?php if ( $tech['header_align']  == "Right") { echo ' selected="selected"'; }?>>Right</option>
				<option <?php if ( $tech['header_align']  == "Center") { echo ' selected="selected"'; }?>>Center</option>
            </select>
			</td></tr>
				<tr><td><?php _e('Header Image Vertical Alignment:','techozoic')?> </td><td><select name="header_v_align" id="header_v_align_2">
                <option <?php if ( $tech['header_v_align']  == "Top") { echo ' selected="selected"'; }?>>Top</option>
				<option <?php if ( $tech['header_v_align']  == "Center") { echo ' selected="selected"'; }?>>Center</option>
				<option <?php if ( $tech['header_v_align']  == "Bottom") { echo ' selected="selected"'; }?>>Bottom</option>
            </select>
			</td></tr>
		</table>
		<span class="tech_submit submit save">
		<input class="button-primary" name="tech_header_height" type="submit" value="<?php _e('Save Settings','techozoic')?>" />
		<?php wp_nonce_field('techozoic_form_submit','techozioc_nonce_field_submit'); ?>		
		</span>
		</form>
		<br />
		<br />
		</div>
			<div style="clear:both;"></div>
		<div id="headerimgs">
		<h3><?php _e('Header Images:','techozoic')?></h3>
		<div id="header_imgs">
			<div class="filediv small">
				<h3><?php _e('Rotate Through All Headers','techozoic')?></h3>
				<div id="rotate" class="current"><?php if(tech_check_header("Rotate.jpg","rotate")){ echo "<h3><span>" . tech_check_header("Rotate.jpg","rotate") . "</span></h3>" ;}?></div>
				<form method="post" name="tech_header_select" >
				<span class="tech_submit submit save">
				<input name="tech_header_select" type="submit" value="<?php _e('Rotate','techozoic')?>" />
				<input type="hidden" name="header_select" value="Rotate.jpg" />
				<input type="hidden" name="techozioc_nonce_field_header_select" value="<?php echo $header_select_nonce;?>" />
				</span>
				</form>
				<br />
			</div>
			<div class="filediv filealt small">
				<h3><?php _e('No Header Image','techozoic')?></h3>
				<div id="none" class="current"><?php if(tech_check_header("none.jpg","none")){ echo "<h3><span>" . tech_check_header("none.jpg","none") . "</span></h3>" ;}?></div>
				<form method="post" name="tech_header_select" >
				<span class="tech_submit submit save">
				<input name="tech_header_select" type="submit" value="<?php _e('No Header Image','techozoic')?>" />
				<input type="hidden" name="header_select" value="none.jpg" />
				<input type="hidden" name="techozioc_nonce_field_header_select" value="<?php echo $header_select_nonce;?>" />
				</span>
				</form>
				<br />
			</div>
		
<?php 
	if (file_exists($header_folder)){
		$path = $header_folder;
	} else {
		include_once(TEMPLATEPATH . '/options/tech-init.php');
		if ($tech['image_location'] == 'theme') {
			tech_create_folders(TEMPLATEPATH . '/uploads');
		} else {
			tech_create_folders(WP_CONTENT_DIR . '/techozoic');
		}
		$path = TEMPLATEPATH . "/images/headers/";
	}
	$dir_handle = @opendir($path) or die("Unable to open $path");
	$i = 1;
	$delvars = array();
	
	function tech_check_header($file , $file_path){
		global $tech;
		$default_headers = array ("Rotate.jpg" , "Random_Lines_1.jpg", "Random_Lines_2.jpg", "Landscape.jpg", "Technology.jpg", "Grunge.jpg","none.jpg");
		if (in_array($file, $default_headers) ){
			$file = substr($file, 0,strrpos($file,'.'));
			if ($tech['header'] == $file){
				return __(" - Current Selected Header","techozoic");
			}	
		}
		if ($tech['header_image_url'] == $file_path) {
			return  __(" - Current Selected Header","techozoic");
		}
	}
	
	while ($file = readdir($dir_handle)) {
		if($file == "." || $file == ".." || $file == "index.php" || $file == ".svn" )
			continue;
			$img_full_path = $path . '/' . $file;
			$img_info = getimagesize($img_full_path);
			$delvars [] = $file;
			if(($i % 2)==0)
				$alt = "filealt";
			else 
				$alt = "";
			$divid = substr($file, 0,strrpos($file,'.'));
			if (file_exists($header_folder)){
				if ($tech['image_location'] == 'wp-content') {
					$file_path = WP_CONTENT_URL. "/techozoic/images/headers/" . $file;
				} else {
					$file_path = get_template_directory_uri() . "/uploads/images/headers/" . $file;
				}
			} else {
				$file_path = get_template_directory_uri() . "/images/headers/" . $file;
			}
			
?>				<div class="filediv <?php echo $alt; ?>">
				<div id="<?php echo $divid; ?>" class="current">
					<?php if(tech_check_header($file,$file_path)){ echo "<h3><span>" . tech_check_header($file,$file_path) . "</span></h3>" ;}?>
				</div> 
				<h3> <?php echo $file; ?> </h3>
				<a href="<?php echo $file_path; ?>" class="thickbox" rel="headers" title="<?php echo $file; echo tech_check_header($file,$file_path);?>">		<img src="<?php echo $file_path;?>" alt="<?php _e('Click for full size preview of','techozoic') ?> <?php echo $file;?>" />
				</a>
				<br />
				<span class="img_meta"><?php _e('Width:','techozoic') ?> <?php echo $img_info[0];?>px &nbsp;| <?php _e('Height:','techozoic') ?> <?php echo $img_info[1];?>px</span>
				<br /><div class="header_buttons">
				<form method="post" name="tech_header_select" >
				<span class="tech_submit submit save">
				<input name="tech_header_select" type="submit" value="<?php _e('Select This Header','techozoic') ?>" />
				<input type="hidden" name="header_select" value="<?php echo $file ;?>" />
				<input type="hidden" name="techozioc_nonce_field_header_select" value="<?php echo $header_select_nonce;?>" />
				</span>
				</form>
				<form method="post" name="tech_header_delete" onsubmit="return delverify()">
				<span class="tech_submit submit reset">
				<input name="tech_header_delete" type="submit" value="<?php _e('Delete This Header','techozoic') ?>" /> 
				<input type="hidden" name="header_delete" value="<?php echo $file ;?>" />
				<input type="hidden" name="techozioc_nonce_field_header_delete" value="<?php echo $header_delete_nonce;?>" />				
				</span>
				</form>
				</div>
				</div>
<?php 	$i++;
	} //End While Loop
	closedir($dir_handle); 
?>

			</div>
			</div>
			</div>
			</div>