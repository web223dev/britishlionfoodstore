<?php
/**
 * @var $sign_in_link string
 * @var $data array
 * @var $options array
 */
?>
<div class="wrap get-site-control" data-auth="">
	<div class="heading">
		<h2> Create Account</h2>
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
                <p class="privacy">
                By signing up, you agree to the<br/> <a target="_blank" href="https://getsitecontrol.com/terms/">Terms of Service</a> and <a target="_blank" href="https://getsitecontrol.com/privacy/">Privacy Policy</a>
                </p>
				<form novalidate
					  method="post"
					  action="<?php echo esc_url( $options['api_url'] ); ?>"
					  data-form-validate="">
					<fieldset class="form-group">
						<div class="form-wrapper">
							<div class="field-row">
								<span class="f-icon f-icon-name"></span>
								<input tabindex="1" class="form-control" title="Enter your name"
									   value="<?php echo ! empty( $data['name'] ) ? esc_attr( $data['name'] ) : ''; ?>"
									   maxlength="200" placeholder="Name" name="name" type="text">
								<span class="form-validation-message"></span>
							</div>

							<div class="field-row">
								<span class="f-icon f-icon-email"></span>
								<input tabindex="2" class="form-control" title="Enter your email" required=""
									   maxlength="200" placeholder="Email Address" name="email" type="email"
									   value="<?php echo ! empty( $data['email'] ) ? esc_attr( $data['email'] ) : ''; ?>">
								<span class="form-validation-message"></span>
							</div>

							<div class="field-row">
								<span class="f-icon f-icon-site"></span>
								<input tabindex="3" class="form-control" title="Enter your site" required=""
									   maxlength="200" placeholder="Site Address (URL)" name="site" type="url"
									   value="<?php echo ! empty( $data['site'] ) ? esc_attr( $data['site'] ) : ''; ?>">
								<span class="form-validation-message"></span>
							</div>
						</div>
						<button tabindex="4" class="button button-fill-active" type="submit"
								data-sending-text="Sending data..."
								data-text="Sign up"
						>
							Sign up
						</button>
					</fieldset>
				</form>
			</div>
			<p class="form-connect-with">Or sign up with</p>
			<div class="form-social-footer">
				<div class="social-login">
					<a tabindex="5"
					   href="<?php echo esc_url( $options['fb_social_link'] ); ?>"
					   class="button social-login-button social-login-facebook">
						<span class="icon icon_facebook"></span>
						Facebook
					</a>
					<a tabindex="6"
					   href="<?php echo esc_url( $options['google_social_link'] ); ?>"
					   class="button social-login-button social-login-google">
						<span class="icon icon_google"></span>
						Google
					</a>
				</div>
			</div>
			<p class="form-have-account">
				Already have an account?&nbsp; <a tabindex="7" href="<?php echo esc_url( $sign_in_link ); ?>">Sign In</a>
			</p>
		</section>
	</div>

	<div class="block block-features even">
		<div class="features">
			<article class="feature">
				<header class="feature-header tint-active">
					<h3 class="feature-title-smile">Truly free</h3></header>
				<p>Sign up and stay on the Free plan for as long as you want. The Free plan does not expire, has no
					hidden costs and includes all core features.</p>
			</article>
			<article class="feature">
				<header class="feature-header tint-active">
					<h3 class="feature-title-box">Seven widgets</h3></header>
				<p>Create unlimited surveys, live chats, contact forms, promo notifications, opt-in forms, follow and
					share widgets, and manage them all in one dashboard.</p>
			</article>
			<article class="feature">
				<header class="feature-header tint-active">
					<h3 class="feature-title-wallet">Want more?</h3></header>
				<p>You can upgrade any time if you need advanced features, want to manage several websites or invite
					additional users. <a target="_blank" href="https://getsitecontrol.com/pricing/">Paid plans</a> start
					at $19/month.</p>
			</article>
		</div>
	</div>

	<div class="block block-heading bht odd">
		<div class="heading">
			<h2>Frequently asked questions</h2>
		</div>
	</div>

	<div class="block block-faq last even">
		<div class="faqs">
			<article class="faq">
				<h3>Is the Free plan really free?</h3>
				<p>Totally. Our Free plan includes all the standard features and is free forever, with no hidden
					costs.</p>
			</article>
			<article class="faq">
				<h3>How many widgets can I create?</h3>
				<p>As many as you want! There are no limitations on the number of widgets you can create and publish on
					your website.</p>
			</article>
			<article class="faq">
				<h3>How many survey responses, messages, subscribers can I collect?</h3>
				<p><a target="_blank" href="https://getsitecontrol.com/pricing/">All subscription plans</a> allow
					unlimited survey responses, subscribers and form
					submissions.</p>
			</article>
		</div>
	</div>

</div>

<script>
	var GSC_OPTIONS = <?php echo wp_json_encode( $options ); ?>;
</script>
