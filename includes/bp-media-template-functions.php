<?php

/**
 * 
 */
function bp_media_show_upload_form() {
	global $bp;
	?>
	<form method="post" enctype="multipart/form-data" class="standard-form" id="bp-media-upload-form">
		<label for="bp-media-upload-input-title"><?php _e('Media Title', 'bp-media'); ?></label><input id="bp-media-upload-input-title" type="text" name="bp_media_title" class="settings-input" />
		<label for="bp-media-upload-input-description"><?php _e('Media Description', 'bp-media'); ?></label><input id="bp-media-upload-input-description" type="text" name="bp_media_description" class="settings-input" />
		<label for="bp-media-upload-file"><?php _e('Select Media File', 'bp-media') ?></label><input type="file" name="bp_media_file" id="bp-media-upload-file" />
		<input type="hidden" name="action" value="wp_handle_upload" />
		<div class="submit"><input type="submit" class="auto" value="Upload" /></div>
	</form>
	<?php
}

/**
 * 
 */
function bp_media_show_pagination() {
	global $bp;
	switch ($bp->current_action) {
		case BP_MEDIA_IMAGES_SLUG :
			$current = BP_MEDIA_IMAGES_LABEL;
			$current_single = BP_MEDIA_IMAGES_LABEL_SINGULAR;
			break;
		case BP_MEDIA_VIDEOS_SLUG :
			$current = BP_MEDIA_VIDEOS_LABEL;
			$current_single = BP_MEDIA_VIDEOS_LABEL_SINGULAR;
			break;
		case BP_MEDIA_AUDIO_SLUG :
			$current = BP_MEDIA_AUDIO_LABEL;
			$current_single = BP_MEDIA_AUDIO_LABEL_SINGULAR;
			break;
		default :
			$current = BP_MEDIA_LABEL;
			$current_single = BP_MEDIA_LABEL_SINGULAR;
	}
	?>
	<div id="pag-top" class="pagination">

		<div class="pag-count" id="group-dir-count-top">
			Viewing <?php echo $current_single ?> 1 to 20 (of 72 <?php echo $current ?>)
		</div>
		<div class="pagination-links" id="group-dir-pag-top">
			<span class="page-numbers current">1</span>
			<a class="page-numbers" href="#">2</a>
			<span class="page-numbers dots">…</span>
			<a class="page-numbers" href="#">4</a>
			<a class="next page-numbers" href="#">→</a>
		</div>

	</div>
	<?php
}

function bp_media_get_permalink($id = 0) {
	if (is_object($id))
		$media = $id;
	else
		$media = &get_post($id);
	if (empty($media->ID))
		return false;
	if (!$media->post_type == 'bp_media')
		return false;
	switch (get_post_meta($media->ID, 'bp_media_type', true)) {
		case 'video' :
			return trailingslashit(bp_displayed_user_domain() . BP_MEDIA_VIDEOS_SLUG . '/watch/' . $media->ID);
			break;
		case 'audio' :
			return trailingslashit(bp_displayed_user_domain() . BP_MEDIA_AUDIO_SLUG . '/listen/' . $media->ID);
			break;
		case 'image' :
			return trailingslashit(bp_displayed_user_domain() . BP_MEDIA_IMAGES_SLUG . '/view/' . $media->ID);
			break;
		default :
			return false;
	}
}

function bp_media_the_permalink() {
	echo apply_filters('bp_media_the_permalink', bp_media_get_permalink());
}

function bp_media_the_content($id = 0) {
	if (is_object($id))
		$media = $id;
	else
		$media = &get_post($id);
	if (empty($media->ID))
		return false;
	if (!$media->post_type == 'bp_media')
		return false;
	if (!get_post_meta($media->ID, 'bp_media_hosting', true) == 'wordpress')
		return false;
	$attachment = get_post_meta($media->ID, 'bp_media_child_attachment', true);
	switch (get_post_meta($media->ID, 'bp_media_type', true)) {
		case 'video' :

			break;
		case 'audio' :

			break;
		case 'image' :
			$medium_array = image_downsize($attachment, 'thumbnail');
			$medium_path = $medium_array[0];
			?>
			<li>
				<a href="<?php bp_media_the_permalink() ?>" title="<?php echo $media->post_content ?>">
					<img src="<?php echo $medium_path ?>" />
					<h3><?php echo $media->post_title ?></h3>
				</a>
			</li>

			<?php
			break;
		default :
			return false;
	}
}
?>