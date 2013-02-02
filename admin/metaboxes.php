<?php
// Stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
				die('You are not allowed to call this page directly.');
}

//Metabox For Pages
add_action('admin_init','aa_meta_init');

function aa_meta_init()
{
		add_meta_box('all_aa_meta',  __('Show Auto Attachments?','autoa'), 'aa_meta_setup', 'page', 'side', 'high');
	
	add_action('save_post','aa_meta_save');
}

function aa_meta_setup()
{
	global $post;
 
	// using an underscore, prevents the meta variable
	// from showing up in the custom fields section
	$meta = get_post_meta($post->ID,'aa_page_meta',TRUE);
 
	?>
	<p><?php _e('If you want to show Auto Attachments on this page Check This Check Box','autoa');?> </p>
	<input type="checkbox" id="aa_page_meta" name="aa_page_meta[show]" value="yes" <?php if ($meta['show'] == "yes") { _e('checked="checked"'); } ?> />  
        <label for="aa_page_meta"><?php _e('I Want to Show','autoa'); ?></label>	
	<?php
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}
 
function aa_meta_save($post_id) 
{
	// authentication checks

	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['my_meta_noncename'],__FILE__)) return $post_id;

	// check user permissions
	if ($_POST['post_type'] == 'page') 
	{
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}
	// authentication passed, save data

	// var types
	// single: aa_page_meta[var]
	// array: aa_page_meta[var][]
	// grouped array: aa_page_meta[var_group][0][var_1], aa_page_meta[var_group][0][var_2]

	$current_data = get_post_meta($post_id, 'aa_page_meta', TRUE);	
 
	$new_data = $_POST['aa_page_meta'];

	my_meta_clean($new_data);
	
	if ($current_data) 
	{
		if (is_null($new_data)) delete_post_meta($post_id,'aa_page_meta');
		else update_post_meta($post_id,'aa_page_meta',$new_data);
	}
	elseif (!is_null($new_data))
	{
		add_post_meta($post_id,'aa_page_meta',$new_data,TRUE);
	}

	return $post_id;
}
function my_meta_clean(&$arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $i => $v)
		{
			if (is_array($arr[$i])) 
			{
				my_meta_clean($arr[$i]);

				if (!count($arr[$i])) 
				{
					unset($arr[$i]);
				}
			}
			else 
			{
				if (trim($arr[$i]) == '') 
				{
					unset($arr[$i]);
				}
			}
		}
		if (!count($arr)) 
		{
			$arr = NULL;
		}
	}
}

?>