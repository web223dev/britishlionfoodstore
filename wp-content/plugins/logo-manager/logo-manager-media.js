jQuery(document).ready(function ($) {
	var custom_uploader;
	$('#upload_logo_button').click(function (e) {
		e.preventDefault();
		if (custom_uploader) {custom_uploader.open(); return; }
		custom_uploader = wp.media.frames.file_frame = wp.media({
		title: 'Select Logo Image', button: {text: 'Insert Logo'}, multiple: false});
		custom_uploader.on('select', function () {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#upload_logo').val(attachment.url);
			});
		custom_uploader.open();
		});
	});