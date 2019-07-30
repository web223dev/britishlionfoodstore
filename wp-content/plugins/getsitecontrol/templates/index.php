<?php
/**
 * @var $url_exist bool
 * @var $options array
 * @var $data array
 * @var $add_site_link string
 */
?>


<div class="wrap get-site-control get-site-control-view-loading" data-manage="">
	<div class="heading">
		<h2 class="manage__title">Create and manage widgets</h2>
		<p class="manage__text">Open your GetSiteControl dashboard to create and edit widgets for your website.</p>
	</div>

	<div class="block block-sign-up-form block-sign-up-form_tint-action odd">
		<section class="sign-up-form selected-toggled-block">
			<div class="form-contents tint-action">
				<div class="field-row select-website-block">
					<span class="f-icon f-icon-site"></span>
					<svg id="i-chevron-bottom" viewBox="0 0 32 32" width="32" height="32" fill="none"
						 stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
						<path d="M30 12 L16 24 2 12"/>
					</svg>
					<select id="widget" name="gsc_widget" class="select-widget" disabled="" required></select>
				</div>

				<a href="<?php echo esc_url( $add_site_link ); ?>" target="_blank"
				   class="button button-fill-default add-site disabled">
					Add new site
				</a>
			</div>
		</section>

		<div class="bottom-manage-block">
			<div class="gotodashboard-block">
				<a href="javascript:void(0);"
				   class="button button-fill-active manage-widget-link disabled manage__button-text" target="_blank">
					Go to Dashboard
				</a>
			</div>
		</div>
	</div>
</div>

<script>
	var GSC_OPTIONS = <?php echo wp_json_encode( $options ); ?>;
</script>
