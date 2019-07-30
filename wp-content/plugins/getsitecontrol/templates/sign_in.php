<?php
/**
 * @var $sign_up_link string
 * @var $data array
 * @var $options array
 */
?>

<div class="wrap get-site-control" data-auth="">
	<div class="heading">
		<h2>Sign In</h2>
	</div>

	<div class="block block-sign-up-form block-sign-up-form_tint-action odd">
		<section class="sign-up-form">
			<div class="form-contents tint-action">
				<div class="page-notification-container">
					<div class="gsc-page-notification page-notification page-notification_error">
						<button type="button" class="close close_btn" data-close="gsc-page-notification">×</button>
						<div class="form-validation-message"></div>
					</div>
				</div>

				<form novalidate
					  method="post"
					  action="<?php echo esc_url( $options['api_url'] ); ?>"
					  data-form-validate="">
					<fieldset class="form-group">
						<div class="form-wrapper">
							<div class="field-row">
								<span class="f-icon f-icon-email"></span>
								<input tabindex="1" class="form-control" title="Enter your email" required=""
									   maxlength="200" placeholder="Email Address" name="email" type="email"
									   value="<?php echo ! empty( $data['email'] ) ? esc_attr( $data['email'] ) : ''; ?>">
								<span class="form-validation-message"></span>
							</div>
							<div class="field-row">
								<span class="f-icon f-icon-password"></span>
								<input tabindex="2" class="form-control" title="Enter your password"
									   maxlength="200" placeholder="Password" name="password" type="password">
								<span class="form-validation-message"></span>
							</div>
						</div>

						<button tabindex="3" class="button button-fill-active" type="submit"
								data-sending-text="Sending data..."
								data-text="Sign In"
						>
							Sign In
						</button>
					</fieldset>
				</form>
			</div>
			<p class="form-connect-with">Or sign in with</p>
			<div class="form-social-footer">
				<div class="social-login">
					<a tabindex="4"
					   href="<?php echo esc_url( $options['fb_social_link'] ); ?>"
					   class="button social-login-button social-login-facebook">
						<span class="icon icon_facebook"></span>
						Facebook
					</a>
					<a tabindex="5"
					   href="<?php echo esc_url( $options['google_social_link'] ); ?>"
					   class="button social-login-button social-login-google">
						<span class="icon icon_google"></span>
						Google
					</a>
				</div>
			</div>
			<p class="form-have-account">
				New user?&nbsp; <a href="<?php echo esc_url( $sign_up_link ); ?>" tabindex="6">Create an account</a>
			</p>
		</section>
	</div>
</div>


<script>
	var GSC_OPTIONS = <?php echo wp_json_encode( $options ); ?>;
</script>
