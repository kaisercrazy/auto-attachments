<?php
// Stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
				die('You are not allowed to call this page directly.');
}
//Shortcodes First
add_action('media_buttons', 'auto_attachments_shortcodes', 11);
add_action('admin_footer', 'aa_sh_content');
function auto_attachments_shortcodes() {
    ?>
<a id="auto_attachments_sh_button" title="<?php _e('Auto Attachments Shortcodes','autoa');?>" class="button-secondary" href="#" style="cursor:pointer;">
        <img src="<?php echo plugins_url('/auto-attachments/includes'); ?>/images/aamenu.png" alt="<?php _e('Auto Attachments Shortcodes','autoa');?>" style="margin-top:-2px;"/>
    </a>
<?php  }
 function aa_sh_content(){  
	global $post;
 ?>
<div id="auto_attachments_sh_window" title="<?php _e('Auto Attachments Shortcodes','autoa');?>" style="display: none;">   
<div style="background:url(<?php echo plugins_url('/auto-attachments/includes'); ?>/images/32x32aa.png) no-repeat;" class="icon32" ></div><h2><?php _e('Create New Auto Attachments Shortcode','autoa');?></h2>
<h4><?php _e('Files Already Loaded','autoa');?>  <img class="spinneri" src="<?php echo plugins_url('/auto-attachments/includes'); ?>/images/spinner.gif" style="display:none;" /> <small><?php _e('If you don\'t see any file name and id press label of selectbox','autoa');?></small></h4>
<label for="image"><span id="resgetir" tur="image" style="cursor:pointer;font-weight:bold;"><?php _e('Image','autoa');?></span> </label> <select id="simage"><?php
	$args = array('post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type'=> 'image','numberposts'   => -1,);$imgs = get_posts($args);$c = count($imgs);if ($c > 0) {foreach ($imgs as $img) {echo "<option id=".$img->ID.">".$img->post_name."(".$img->ID.")</option>";}} else {?><option id="none"><?php _e('No Image','autoa');?></option><?php }?></select>
<label for="audio"><span id="audgetir" tur="audio" style="cursor:pointer;font-weight:bold;"><?php _e('Audio','autoa');?></span> </label> <select id="saudio"><?php
	$args = array('post_type'=> 'attachment','post_parent'   => $post->ID,'post_mime_type'=> 'audio','numberposts'   => -1,);$imgs = get_posts($args);$c = count($imgs);if ($c > 0) {foreach ($imgs as $img) {echo "<option id=".$img->ID.">".$img->post_name."(".$img->ID.")</option>";}} else {?><option id="none"><?php _e('No Audio','autoa');?></option><?php }?></select>
<label for="video"><span id="vidgetir" tur="video" style="cursor:pointer;font-weight:bold;"> <?php _e('Video','autoa');?></span> </label> <select id="svideo"><?php
	$args = array('post_type'=> 'attachment','post_parent'   => $post->ID,'post_mime_type'=> 'video','numberposts'   => -1,);$imgs = get_posts($args);$c = count($imgs);if ($c > 0) {foreach ($imgs as $img) {echo "<option id=".$img->ID.">".$img->post_name."(".$img->ID.")</option>";}} else {?><option id="none"><?php _e('No Video','autoa');?></option><?php }?></select>
<label for="idselect"><span id="filegetir" tur="application" style="cursor:pointer;font-weight:bold;"><?php _e('File','autoa');?> </span></label> <select id="sfile"><?php
	$args = array('post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type'=> array("application/pdf","application/rar","application/msword","application/vnd.ms-powerpoint","application/vnd.ms-excel","application/zip","application/x-rar-compressed","application/x-tar","application/x-gzip","application/vnd.oasis.opendocument.spreadsheet","application/vnd.oasis.opendocument.formula","text/plain","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.openxmlformats-officedocument.presentationml.presentation","application/x-compress","application/mathcad","application/postscript") ,'numberposts'   => -1,);$imgs = get_posts($args);$c = count($imgs);if ($c > 0) {foreach ($imgs as $img) {echo "<option id=".$img->ID.">".$img->post_name."(".$img->ID.")</option>";}} else {?><option id='none'><?php _e('No File','autoa');?></option><?php }?></select>
<div class="clear"> </div>
<table class="widefat fixed">
	<thead>
		<tr>
			<th width="90"><?php _e('Shortcode For', 'autoa'); ?></th>
			<th width="170"><?php _e('Item Id(s)', 'autoa'); ?></th>
			<th width="60"> </th>
			<th width="230"><?php _e('Description', 'autoa'); ?></th>
		</tr>
	</thead>
	<tbody class="lnginpt">
		<tr>
			<td><?php _e('Image(s)','autoa');?></td>
			<td><input id="resids" type="text" value="<?php echo get_post_meta($post->ID,'ex_rsm',true);?>" name="<?php echo $post->ID; ?>" durum="resim" /></td>
			<td><a class="button-primary" id="rsmadd" href="#" title="<?php _e('Create', 'autoa'); ?>" style="color:#FFF;"><span><?php _e('Create', 'autoa'); ?></span></a></td>
			<td><small><?php _e('Enter the <strong>Ids</strong> (comma seperated) of image(s) here. <strong>Image selectbox</strong> shows which files loaded in this post.','autoa'); ?></small></td>
		</tr>
		<tr>
			<td><?php _e('File(s)','autoa');?></td>
			<td><input id="dosyids" type="text" value="<?php echo get_post_meta($post->ID,'ex_dosya',true);?>" name="<?php echo $post->ID; ?>" durum="dosya" /></td>
			<td><a class="button-primary" id="dosyadd" href="#" title="<?php _e('Create', 'autoa'); ?>" style="color:#FFF;"><span><?php _e('Create', 'autoa'); ?></span></a></td>
			<td><small><?php _e('Enter the Ids (comma seperated) of file(s) here. <strong>File selectbox</strong> shows which files loaded in this post.','autoa'); ?></small></td>
		</tr>
		<tr>
			<td><?php _e('Audio(s)','autoa');?></td>
			<td><input id="muzids" type="text" value="<?php echo get_post_meta($post->ID,'ex_muz',true);?>" name="<?php echo $post->ID; ?>" durum="muzik" /></td>
			<td><a class="button-primary" id="muzadd" href="#" title="<?php _e('Create', 'autoa'); ?>" style="color:#FFF;"><span><?php _e('Create', 'autoa'); ?></span></a></td>
			<td><small><?php _e('Enter the Ids (comma seperated) of aufido file(s) here. <strong>Audio selectbox</strong> shows which files loaded in this post.','autoa'); ?></small></td>
		</tr>
		<tr>
			<td><?php _e('Video(s)','autoa');?></td>
			<td><input id="vidids" type="text" value="<?php echo get_post_meta($post->ID,'ex_vid',true);?>" name="<?php echo $post->ID; ?>" durum="video" /></td>
			<td><a class="button-primary" id="vidadd" href="#" title="<?php _e('Create', 'autoa'); ?>" style="color:#FFF;"><span><?php _e('Create', 'autoa'); ?></span></a></td>
			<td><small><?php _e('Enter the Ids (comma seperated) of video file(s) here. <strong>Video selectbox</strong> shows which files loaded in this post.','autoa'); ?></small></td>
		</tr>
	</tbody>
</table>
<p class="dee"></p>
<h3><?php _e('Usage','autoa'); ?></h3>
<table class="widefat fixed">
	<thead>
		<tr>
			<th width="100px;"><?php _e('Situation','autoa'); ?></th><th><?php _e('Description','autoa'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php _e('Create','autoa'); ?></td><td><?php _e('For create any shortcode, please add ids with comma to text areas. When you press <strong>Create</strong> button, plugin will create a shortcode and an exclude code for show files properly.','autoa'); ?></td>
		</tr>
		<tr>
			<td><?php _e('Clear','autoa'); ?></td><td><?php _e('For clear, Wipe text area and press <strong>Create</strong> button. This will clear exclude code from your post but you will still have shortcode. You can delete shortcode from your content. Shortcode will show your items properly, but this may duplicate files (in after content area).','autoa'); ?></td>
		</tr>
	</tbody>
</table>

</div>

 <?php }
add_action('admin_head-post-new.php', 'button_js');
add_action('admin_head-post.php', 'button_js');
add_action('admin_head-edit.php', 'button_js');
function button_js() {
	global $post;
	$nonc = wp_create_nonce("ajax-nonce");
	$postid = $post->ID;
	echo "<script type='text/javascript'>
	jQuery(function ($) {
		$('.lnginpt input[type=text]').css('width','175px');
		$('#auto_attachments_sh_window select').css('width','110px','max-width','110px');
		
		$('#auto_attachments_sh_window').dialog({
			autoOpen: false,
			width: '700',
			height: '600',
			modal: true,
			draggable: false,
			resizable: false,
			closeOnEscape: true
		});
		
		$('#auto_attachments_sh_button').click(function () {
			$('#auto_attachments_sh_window').dialog('open');
		});
		
		$('#resgetir').live('click', function () {
			$('.spinneri').show();
			var data = {
				action: 'get_imgs',
				post_id: '$postid',
				postmim: $(this).attr('tur')
			};
			$.getJSON(ajaxurl, data, function (response) {
			$('.spinneri').hide();		
			cb = '';
			$.each(response, function(i,tata){
				cb+='<option value=\"'+tata.id+'\">'+tata.post_name+'('+tata.id+')</option>';
				});
				$('#simage option').remove();
				$('#simage').append(cb);
			});
		});
		$('#audgetir').live('click', function () {
			$('.spinneri').show();
			var data = {
				action: 'get_imgs',
				post_id: '$postid',
				postmim: $(this).attr('tur')
			};
			$.getJSON(ajaxurl, data, function (response) {
			$('.spinneri').hide();		
			cb = '';
			$.each(response, function(i,tata){
				cb+='<option value=\"'+tata.id+'\">'+tata.post_name+'('+tata.id+')</option>';
				});
				$('#saudio option').remove();
				$('#saudio').append(cb);
			});
		});
		$('#vidgetir').live('click', function () {
			$('.spinneri').show();
			var data = {
				action: 'get_imgs',
				post_id: '$postid',
				postmim: $(this).attr('tur')
			};
			$.getJSON(ajaxurl, data, function (response) {
			$('.spinneri').hide();		
			cb = '';
			$.each(response, function(i,tata){
				cb+='<option value=\"'+tata.id+'\">'+tata.post_name+'('+tata.id+')</option>';
				});
				$('#svideo option').remove();
				$('#svideo').append(cb);
			});
		});
		$('#filegetir').live('click', function () {
			$('.spinneri').show();
			var data = {
				action: 'get_imgs',
				post_id: '$postid',
				postmim: $(this).attr('tur')
			};
			$.getJSON(ajaxurl, data, function (response) {
			$('.spinneri').hide();		
			cb = '';
			$.each(response, function(i,tata){
				cb+='<option value=\"'+tata.id+'\">'+tata.post_name+'('+tata.id+')</option>';
				});
				$('#sfile option').remove();
				$('#sfile').append(cb);
			});
		});
		
		$('#rsmadd').live('click', function () {
			$('.spinneri').show();
			if ($('input#resids').val() !=''){
			send_to_editor('[imageaa id=' + $('input#resids').val() + ']');
			}
			var data = {
				action: 'ex_aa',
				durum: $('input#resids').attr('durum'),
				post_id: $('input#resids').attr('name'),
				post_meta: $('input#resids').val(),
				nonce: '$nonc'
			};
			$.post(ajaxurl, data, function (response) {
				$('.spinneri').hide();
				$('#auto_attachments_sh_window').dialog('close');
			});
		});
		$('#muzadd').live('click', function () {
			$('.spinneri').show();
			if ($('input#muzids').val() !=''){
			send_to_editor('[musicaa id=' + $('input#muzids').val() + ']');
			}
			var data = {
				action: 'ex_aa',
				durum: $('input#muzids').attr('durum'),
				post_id: $('input#muzids').attr('name'),
				post_meta: $('input#muzids').val(),
				nonce: '$nonc'
			};
			$.post(ajaxurl, data, function (response) {
				$('.spinneri').hide();
				$('#auto_attachments_sh_window').dialog('close');
			});
		});
		$('#vidadd').live('click', function () {
			$('.spinneri').show();
			if ($('input#vidids').val() !=''){
			send_to_editor('[videoaa id=' + $('input#vidids').val() + ']');
			}
			var data = {
				action: 'ex_aa',
				durum: $('input#vidids').attr('durum'),
				post_id: $('input#vidids').attr('name'),
				post_meta: $('input#vidids').val(),
				nonce: '$nonc'
			};
			$.post(ajaxurl, data, function (response) {
				$('.spinneri').hide();
				$('#auto_attachments_sh_window').dialog('close');
			});
		});
		$('#dosyadd').live('click', function () {
			$('.spinneri').show();
			if ($('input#dosyids').val() !=''){
			send_to_editor('[filesaa id=' + $('input#dosyids').val() + ']');
			}
			var data = {
				action: 'ex_aa',
				durum: $('input#dosyids').attr('durum'),
				post_id: $('input#dosyids').attr('name'),
				post_meta: $('input#dosyids').val(),
				nonce: '$nonc'
			};
			$.post(ajaxurl, data, function (response) {
				$('.spinneri').hide();
				$('#auto_attachments_sh_window').dialog('close');
			});
		});
	});
//--><!]]></script>";
}

add_action('wp_ajax_ex_aa', 'ex_aa_callback');

function ex_aa_callback() {
	global $wpdb; 
	$nonce = $_POST['nonce'];
	$post_id = $_POST['post_id'];
	$post_meta = $_POST['post_meta'];
	$durum = $_POST['durum'];
	if (!wp_verify_nonce($nonce,'ajax-nonce')){  
		die ( 'Busted!');} else {
			if ($durum == "resim") {
				update_post_meta($post_id,'ex_rsm',$post_meta);
			}
			if ($durum == "muzik") {
				update_post_meta($post_id,'ex_muz',$post_meta);
			} 
			if ($durum == "video") {
				update_post_meta($post_id,'ex_vid',$post_meta);
			}
			if ($durum == "dosya"){
				update_post_meta($post_id,'ex_dosya',$post_meta);
			}
	}
	die();
}

add_action('wp_ajax_get_imgs', 'ex_getimgs');

function ex_getimgs() {
	global $wpdb; 
	$post_id = $_GET['post_id'];
	$postmim = $_GET['postmim'];
	$args = array(
	'post_type'=> 'attachment',
	'post_parent'=> $post_id,
	'post_mime_type'=> $postmim,
	'numberposts'   => -1
	);
	$imgs = get_posts($args);
	$c = count($imgs);
	if ($c > 0) {
	foreach ($imgs as $img) {
			$ret[]= array('id' =>$img->ID, 'post_name' => $img->post_name);
			}
	} else {
		$ret[] = array('id' => '-', 'post_name' => 'Nope');
	}
	    $output = $ret;
		echo json_encode($output);
	die();
}


// Shortcode for Images
function getimages_aa($atts) {
	global $post;
	$opts = get_option('auto_attachments_options');
	extract(shortcode_atts(array("id" => ''), $atts));
	$dis = explode(',',$id);
	$ex_rsm = get_post_meta($post->ID,'ex_rsm',true);
	$imageaa = "";
	if ($ex_rsm != "") {
	$imageaa .= "<div class='dIW1'><div class='galeri-".$opts['galstyle']."'>";
	foreach ($dis as $di){
		$posti = get_post($di);
		$aath = wp_get_attachment_image_src($posti->ID, 'aa_thumb');
		$aabg = wp_get_attachment_image_src($posti->ID, 'aa_big');
		$imageaa .= "<a href='$aabg[0]' rel='lightbox-grp-shcode'><img src='$aath[0]'/></a>";
	}
	$imageaa .="</div></div><div style='clear:both;'> </div>";
	}
	return $imageaa;
}
add_shortcode('imageaa', 'getimages_aa');
// Shortcode for Images

// Shortcode for Files
function getfiles_aa($atts) {
	global $post;
	$opts = get_option('auto_attachments_options');
	extract(shortcode_atts(array("id" => ''), $atts));
	$dis = explode(',',$id);
	$ex_dosya = get_post_meta($post->ID,'ex_dosya',true);
	$urlp = plugins_url('/auto-attachments/includes');
	$filesaa = "";
	if ($ex_dosya != "") {
	$filesaa .= "<div class='dIW2'>";
	foreach ($dis as $di){
		$posti = get_post($di);
		$fhh = $opts['fhh'];
		$fhw = $opts['fhw'];
		if ($opts['newwindow'] == 'yes') {$target = 'target="_blank"';} else {$target = "";}
		$_link       = wp_get_attachment_url($posti->ID); //get the url for linkage
		$_name_array = explode("/", $_link);
		$_post_mime  = str_replace("/", "-", $posti->post_mime_type);
		$_name       = array_reverse($_name_array);
		$filesaa .= "<div class='dI' id='$posti->ID'><a href='$_link' $target><img src='$urlp/images/mime/" . $_post_mime . ".png' width='$fhw' height='$fhh'/></a><a class='dItitle' href='$file_link'>" . $posti->post_title . "</a></div>";
	}
	$filesaa .="</div><div style='clear:both;'> </div>";
	}
	return $filesaa;
}
add_shortcode('filesaa', 'getfiles_aa');

// Shortcode for Audio
function getmusic_aa($atts) {
	global $post;
	$opts = get_option('auto_attachments_options');
	extract(shortcode_atts(array("id" => ''), $atts));
	$dis = explode(',',$id);
	$ex_muz = get_post_meta($post->ID,'ex_muz',true);
	$urlp = plugins_url('/auto-attachments/includes');
	$skin = $opts['jwskin'];
	$jhw  = $opts['jhw'];
	$musicaa = "";
	if ($ex_muz != "") {
	$musicaa .= "<div class='dIW'><ul>";
	foreach ($dis as $di){
		$posti = get_post($di);
		$musicaa .= "<li>";
		$musicaa .= "<div id='mediaspace" . $posti->ID . "'></div>";
		$musicaa .= "<script type='text/javascript'>
		var so = new SWFObject('$urlp/jw/player.swf','ply','$jhw','24','9','#000000');
		so.addParam('allowfullscreen','false');
		so.addParam('allowscriptaccess','always');
		so.addParam('wmode','opaque');
		so.addVariable('file','" . $posti->guid . "');
		so.addVariable('skin','" . $urlp . "/jw/skins/" . $skin . ".zip');
		so.write('mediaspace" . $posti->ID . "');
		</script>";
	$musicaa .= "</li>";
	}
	$musicaa .="</ul></div><div style='clear:both;'> </div>";
	}
	return $musicaa;
}
add_shortcode('musicaa', 'getmusic_aa');

// Shortcode for Videos
function getvideo_aa($atts) {
	global $post;
	$opts = get_option('auto_attachments_options');
	extract(shortcode_atts(array("id" => ''), $atts));
	$dis = explode(',',$id);
	$ex_vid = get_post_meta($post->ID,'ex_vid',true);
	$urlp = plugins_url('/auto-attachments/includes');
	$skin = $opts['jwskin'];
	$jhw  = $opts['jhw'];
	$jhh  = $opts['jhh'];
	$videoaa = "";
	if ($ex_vid != "") {
	$videoaa .= "<div class='dIW'><ul>";
	foreach ($dis as $di){
		$posti = get_post($di);
		$videoaa .= "<li>";
		$videoaa .= "<div id='mediaspace" . $posti->ID . "'></div>";
		$videoaa .= "<script type='text/javascript'>
		var so = new SWFObject('$urlp/jw/player.swf','ply','$jhw','$jhh','9','#000000');
		so.addParam('allowfullscreen','true');
		so.addParam('allowscriptaccess','always');
		so.addParam('wmode','opaque');
		so.addVariable('file','" . $posti->guid . "');
		so.addVariable('skin','" . $urlp . "/jw/skins/" . $skin . ".zip');
		so.write('mediaspace" . $posti->ID . "');
		</script>";
		$videoaa .= "</li>";
	}
	$videoaa .="</ul></div><div style='clear:both;'> </div>";
	}
	return $videoaa;
}
add_shortcode('videoaa', 'getvideo_aa');

// End Of shortcodes.php
?>