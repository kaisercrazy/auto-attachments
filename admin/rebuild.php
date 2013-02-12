<?php

if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
				die('You are not allowed to call this page directly.');
}

class aARebuild {
	function aARebuild() {
		add_action( 'admin_menu', array($this, 'rebuildmenu') );
	}
	function rebuildmenu() {
		add_submenu_page('auto_attachments', __('Regen. Thumbnails', 'autoa'), __('Regen. Thumbnails', 'autoa'), 'manage_options', 'aa_regen_thumb', array($this, 'rebuildpage'));
	}
	function rebuildpage() {
		$opts = get_option('auto_attachments_options');
		$urlp = plugins_url('/auto-attachments');
		?>
		<style>#icon-aa {background:url('<?php echo $urlp; ?>/includes/images/32x32aa.png') no-repeat;margin-left:3px;}</style>
		<div id="icon-aa" class="icon32" ></div><h2><?php _e('Regenerate Thumbnail', 'autoa'); ?></h2>
		<div id="message" class="updated fade" style="display:none"></div>
		<script type="text/javascript">
		// <![CDATA[

		function setMessage(msg) {
			jQuery("#message").html(msg);
			jQuery("#message").show();
		}

		function regenerate() {
			jQuery("#_rebuild").attr("disabled", true);
			setMessage("<p><?php _e('Reading attachments...', 'autoa') ?></p>");

			inputs = jQuery( 'input:checked' );
			var thumbnails= '';
			if( inputs.length != jQuery( 'input[type=checkbox]' ).length ){
				inputs.each( function(){
					thumbnails += '&thumbnails[]='+jQuery(this).val();
				} );
			}

			var onlyfeatured = jQuery("#onlyfeatured").attr('checked') ? 1 : 0;

			jQuery.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>",
				type: "POST",
				data: "action=ajax_thumbnail_rebuild&do=getlist&onlyfeatured="+onlyfeatured,
				success: function(result) {
					var list = eval(result);
					var curr = 0;

					if (!list) {
						setMessage("<?php _e('No attachments found.', 'autoa')?>");
						jQuery("#_rebuild").removeAttr("disabled");
						return;
					}

					function regenItem() {
						if (curr >= list.length) {
							jQuery("#_rebuild").removeAttr("disabled");
							setMessage("<?php _e('Done.', 'autoa') ?>");
							return;
						}
						setMessage(<?php printf( __('"Rebuilding " + %s + " of " + %s + " (" + %s + ")..."', 'autoa'), "(curr+1)", "list.length", "list[curr].title"); ?>);

						jQuery.ajax({
							url: "<?php echo admin_url('admin-ajax.php'); ?>",
							type: "POST",
							data: "action=ajax_thumbnail_rebuild&do=regen&id=" + list[curr].id + thumbnails,
							success: function(result) {
								jQuery("#thumb").show();
								jQuery("#thumb-img").attr("src",result);

								curr = curr + 1;
								regenItem();
							}
						});
					}

					regenItem();
				},
				error: function(request, status, error) {
					setMessage("<?php _e('Error', 'autoa') ?>" + request.status);
				}
			});
		}

		jQuery(document).ready(function() {
			jQuery('#size-toggle').click(function() {
				jQuery("#sizeselect").find("input[type=checkbox]").each(function() {
					jQuery(this).attr("checked", !jQuery(this).attr("checked"));
				});
			});
		});

		// ]]>
		</script>

		<form method="post" action="" style="display:inline; float:left; padding-right:30px;">
		    <h4><?php _e('Select which thumbnails you want to rebuild', 'autoa'); ?>:</h4>
			<a href="javascript:void(0);" id="size-toggle"><?php _e('Toggle all', 'autoa'); ?></a>
			<div id="sizeselect">
			<input type="checkbox" name="thumbnails[]" id="sizeselect" checked="checked" value="aa_thumb" />
				<label>
					<em>aa_thumb</em>
					&nbsp;(<?php echo $opts['thw'] ?>x<?php echo $opts['thh'] ?>
					<?php _e('cropped', 'autoa'); ?>)
				</label><br />
			<input type="checkbox" name="thumbnails[]" id="sizeselect" checked="checked" value="aa_big" />
				<label>
					<em>aa_big</em>
					&nbsp;(<?php echo $opts['tbhw'] ?>x<?php echo $opts['tbhh'] ?>)
				</label>
			</div>
			<p><?php _e("Note: If you've changed the dimensions of your thumbnails, existing thumbnail images will not be deleted.",
			'autoa'); ?></p>
			<input type="button" onClick="javascript:regenerate();" class="button"
			       name="_rebuild" id="_rebuild"
			       value="<?php _e( 'Rebuild All Thumbnails', 'autoa' ) ?>" />
			<br />
		</form>
		<?php
	}


}


function ajax_thumbnail_rebuild_ajax() {
	global $wpdb;
	
	$action = $_POST["do"];
	$thumbnails = isset( $_POST['thumbnails'] )? $_POST['thumbnails'] : NULL;
	$onlyfeatured = isset( $_POST['onlyfeatured'] ) ? $_POST['onlyfeatured'] : 0;

	if ($action == "getlist") {
			$attachments =& get_children( array(
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'numberposts' => -1,
				'post_status' => null,
				'post_parent' => null, // any parent
				'output' => 'object',
			) );
			foreach ( $attachments as $attachment ) {
			    $res[] = array('id' => $attachment->ID, 'title' => $attachment->post_title);
			}

		die( json_encode($res) );
	} else if ($action == "regen") {
		$id = $_POST["id"];

		$fullsizepath = get_attached_file( $id );

		if ( FALSE !== $fullsizepath && @file_exists($fullsizepath) ) {
			set_time_limit( 30 );
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata_custom( $id, $fullsizepath, $thumbnails ) );
		}

		die( wp_get_attachment_thumb_url( $id ));
	}
}
add_action('wp_ajax_ajax_thumbnail_rebuild', 'ajax_thumbnail_rebuild_ajax');

add_action( 'plugins_loaded', create_function( '', 'global $aARebuild; $aARebuild = new aARebuild();' ) );

function ajax_thumbnail_rebuild_get_sizes() {
	global $_wp_additional_image_sizes;

	foreach ( get_intermediate_image_sizes() as $s ) {
		$sizes[$s] = array( 'name' => '', 'width' => '', 'height' => '', 'crop' => FALSE );

		/* Read theme added sizes or fall back to default sizes set in options... */

		$sizes[$s]['name'] = $s;

		if ( isset( $_wp_additional_image_sizes[$s]['width'] ) )
			$sizes[$s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); 
		else
			$sizes[$s]['width'] = get_option( "{$s}_size_w" );

		if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
			$sizes[$s]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] );
		else
			$sizes[$s]['height'] = get_option( "{$s}_size_h" );

		if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
			$sizes[$s]['crop'] = intval( $_wp_additional_image_sizes[$s]['crop'] );
		else
			$sizes[$s]['crop'] = get_option( "{$s}_crop" );
	}

	return $sizes;
}

function wp_generate_attachment_metadata_custom( $attachment_id, $file, $thumbnails = NULL ) {
	$attachment = get_post( $attachment_id );

	$metadata = array();
	if ( preg_match('!^image/!', get_post_mime_type( $attachment )) && file_is_displayable_image($file) ) {
		$imagesize = getimagesize( $file );
		$metadata['width'] = $imagesize[0];
		$metadata['height'] = $imagesize[1];
		list($uwidth, $uheight) = wp_constrain_dimensions($metadata['width'], $metadata['height'], 128, 96);
		$metadata['hwstring_small'] = "height='$uheight' width='$uwidth'";

		// Make the file path relative to the upload dir
		$metadata['file'] = _wp_relative_upload_path($file);

		$sizes = ajax_thumbnail_rebuild_get_sizes();
		$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

		foreach ($sizes as $size => $size_data ) {
			if( isset( $thumbnails ) && !in_array( $size, $thumbnails ))
				$intermediate_size = image_get_intermediate_size( $attachment_id, $size_data['name'] );
			else
				$intermediate_size = image_make_intermediate_size( $file, $size_data['width'], $size_data['height'], $size_data['crop'] );

			if ($intermediate_size)
				$metadata['sizes'][$size] = $intermediate_size;
		}

		// fetch additional metadata from exif/iptc
		$image_meta = wp_read_image_metadata( $file );
		if ( $image_meta )
			$metadata['image_meta'] = $image_meta;

	}

	return apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
}
?>