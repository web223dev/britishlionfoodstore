<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki_Control_Xt_Premium' ) && class_exists('Kirki_Control_Base')) {

	/**
	 * Dashicons control (modified radio).
	 */
	class Kirki_Control_Xt_Premium extends Kirki_Control_Base {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'xt-premium';


		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see Kirki_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 *
		 * @access protected
		 */
		protected function content_template() {
			?>
			<# if ( data.tooltip ) { #>
				<a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='woofcicons woofcicons-info'></span></a>
			<# } #>

			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>

            <# if ( data.description ) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>

            <# if ( data.default ) { #>

                <# if ( data.default.link ) { #>
                    <a href="{{{data.default.link}}}">
                <# } #>

                    <# if ( data.default.type === 'image' ) { #>
                        <img src="{{{ data.default.value }}}" width="100%" />
                    <# }else{ #>
                        <span class="xt-premium-feature" style="color:#cb2222">{{{ data.default.value }}}</span>
                    <# } #>

                <# if ( data.default.link ) { #>
                    </a>
                <# } #>

            <# } #>

			<?php
		}

        /**
         * Sets the $sanitize_callback
         *
         * @access protected
         */
        protected function set_sanitize_callback() {

            // If a custom sanitize_callback has been defined,
            // then we don't need to proceed any further.
            if ( ! empty( $this->sanitize_callback ) ) {
                return;
            }
            // Custom fields don't actually save any value.
            // just use __return_true.
            $this->sanitize_callback = '__return_true';

        }

		public function render_content() {

			self::print_template();
		}

	}
}
