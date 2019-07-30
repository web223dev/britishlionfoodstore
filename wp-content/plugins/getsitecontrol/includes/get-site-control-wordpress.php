<?php

/**
 * Class GetSiteControlWordPress
 */
class GetSiteControlWordPress {


	const PASSWORD_MIN_LENGTH       = 4;
	public static $version          = '2.3.0';
	public static $registerLink     = 'https://app.getsitecontrol.com/api/v1/users/register';
	public static $loginLink        = 'https://app.getsitecontrol.com/api/v1/users/login';
	public static $sitesLink        = 'https://app.getsitecontrol.com/api/v1/sites/own';
	public static $autoLoginLink    = 'https://app.getsitecontrol.com/api/v1/users/autologin';
	public static $fbSocialLink     = 'https://app.getsitecontrol.com/api/v1/socialauth-begin/facebook/?mode=signup-popup';
	public static $googleSocialLink = 'https://app.getsitecontrol.com/api/v1/socialauth-begin/google-oauth2/?mode=signup-popup';
	public static $settings         = array();
	public static $errors           = array();
	public static $actions          = array(
		'index' => array(
			'slug'     => 'getsitecontrol',
			'function' => 'action_admin_menu_page',
			'name'     => 'GetSiteControl',
			'title'    => 'GetSiteControl for WordPress settings',
		),
		'auth'  => array(
			'sign-out' => array(
				'slug'     => 'getsitecontrol_sign_out',
				'function' => 'action_admin_menu_sign_out',
				'name'     => 'Sign out',
				'title'    => 'Sign out - GetSiteControl',
			),
		),
		'guest' => array(
			'sign-in' => array(
				'slug'     => 'getsitecontrol_sign_in',
				'function' => 'action_admin_menu_sign_in',
				'name'     => 'Sign in',
				'title'    => 'Sign in to GetSiteControl',
			),
			'sign-up' => array(
				'slug'     => 'getsitecontrol_sign_up',
				'function' => 'action_admin_menu_sign_up',
				'name'     => 'Sign up',
				'title'    => 'Sign up to GetSiteControl',
			),
		),

	);
	private static $getSiteControl = null;

	public function __construct() {

	}


	/**
	 * Get instance
	 *
	 * @return GetSiteControlWordPress|null
	 */
	public static function init() {
		if ( is_null( self::$getSiteControl ) ) {
			self::$getSiteControl = new self();
			self::add_actions();
			self::$settings = self::gsc_settings();
			if ( empty( self::$settings ) || ( self::$version !== self::$settings['version'] ) ) {
				self::install( self::$settings );
			}
		}

		return self::$getSiteControl;
	}

	/**
	 * Add actions for plugin
	 */
	public static function add_actions() {
		if ( is_admin() ) {
			add_action( 'admin_init', array( __CLASS__, 'redirect_rule' ) );

			add_action( 'admin_menu', array( __CLASS__, 'admin_menu_add' ) );
			add_action( 'admin_menu', array( __CLASS__, 'admin_sub_menu_add' ) );

			add_action( 'wp_ajax_gsc_post_update_widget', array( __CLASS__, 'gsc_post_update_widget' ) );
			add_action( 'wp_ajax_gsc_post_clear_api_key', array( __CLASS__, 'gsc_post_clear_api_key' ) );
			add_action( 'wp_ajax_gsc_post_sign_in', array( __CLASS__, 'gsc_post_sign_in' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
		} else {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_script' ) );
			add_action( 'wp_head', array( __CLASS__, 'add_inline_script' ) );
		}
	}

	/**
	 * Get GetSiteControl settings
	 *
	 * @return mixed
	 */
	public static function gsc_settings() {
		return get_option( 'get_site_control_settings' );
	}

	/**
	 * Set GetSiteControl settings
	 *
	 * @param $gsc_settings
	 */
	public static function install( $gsc_settings ) {
		if ( empty( $gsc_settings ) ) {
			$gsc_settings = array(
				'api_key'     => null,
				'version'     => self::$version,
				'widget_id'   => null,
				'widget_link' => null,
			);
			add_option( 'get_site_control_settings', $gsc_settings );
		}

		if ( self::$version !== $gsc_settings['version'] ) {
			self::update( $gsc_settings );
		}
	}

	/**
	 * Update GetSiteControl settings
	 *
	 * @param $gsc_settings
	 *
	 * @return bool
	 */
	public static function update( $gsc_settings ) {
		$gsc_settings['version'] = self::$version;

		return update_option( 'get_site_control_settings', $gsc_settings );
	}

	/**
	 * Add plugin link in sidebar
	 */
	public static function admin_menu_add() {
		add_menu_page(
			self::$actions['index']['title'],
			self::$actions['index']['name'],
			'manage_options',
			self::$actions['index']['slug'],
			array( __CLASS__, self::$actions['index']['function'] ),
			GSC_URL . 'templates/images/logo@2x.png'
		);
	}


	/**
	 * Add plugin sub links in sidebar
	 */
	public static function admin_sub_menu_add() {
		$type = 'auth';

		if ( empty( self::$settings['api_key'] ) ) {
			$type = 'guest';
		}

		foreach ( self::$actions[ $type ] as $action ) {
			add_submenu_page(
				self::$actions['index']['slug'], $action['title'], $action['name'], 'manage_options', $action['slug'], array(
					__CLASS__,
					$action['function'],
				)
			);
		}
	}


	/**
	 * Register styles and scripts
	 */
	public static function admin_scripts() {
		wp_enqueue_style( 'gsc_admin_style', GSC_URL . 'templates/css/get-site-control-admin.css', '', self::$version );
		wp_enqueue_script( 'gsc_admin_script', GSC_URL . 'templates/js/get-site-control-admin.js', '', self::$version, true );
	}


	/**
	 * Add script before </body>
	 */
	public static function add_script() {
		if ( ! empty( self::$settings['widget_link'] ) ) {
			wp_enqueue_script( 'gsc_widget_script', self::$settings['widget_link'], '', self::$version, true );
			add_filter( 'script_loader_tag', array( __CLASS__, 'filter_script_loader_tag' ), 10, 2 );
		}
	}

	public static function add_inline_script() {
		if ( ! empty( self::$settings['widget_link'] ) ) {
			if ( function_exists( 'wp_add_inline_script' ) ) {
				wp_add_inline_script( 'gsc_widget_script', 'window._gscq = window._gscq || []', 'before' );
			} else {
				echo '<script>window._gscq = window._gscq || []</script>';
			}
		}
	}

	/**
	 * Filter script loader
	 *
	 * @param $tag
	 * @param $handle
	 *
	 * @return mixed
	 */
	public static function filter_script_loader_tag( $tag, $handle ) {
		if ( 'gsc_widget_script' !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' data-cfasync="false" async src', $tag );
	}

	/**
	 * Main page action
	 */
	public static function action_admin_menu_page() {
		self::check_access_die();

		$options                         = self::$settings;
		$options['update_widget_action'] = 'gsc_post_update_widget';
		$options['clear_api_key_action'] = 'gsc_post_clear_api_key';
		$options['api_url']              = self::$sitesLink;
		$options['manage_site_link']     = self::$autoLoginLink . '?api_key=' .
										   self::$settings['api_key'] . '&next=/#/dashboard/sites/<SITE_ID>/widgets/list';

		self::render_template(
			'index',
			array(
				'options'       => $options,
				'add_site_link' => self::$autoLoginLink . '?api_key=' . self::$settings['api_key'] . '&next=/#/account/sites',
			)
		);
	}

	/**
	 * Check user access with die
	 */
	protected static function check_access_die() {
		if ( ! self::check_access() ) {
			wp_die( 'You do not have sufficient permissions to access this page' );
		}
	}

	/**
	 * Check user access
	 *
	 * @return bool
	 */
	protected static function check_access() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Render template by name
	 *
	 * @param $viewFile
	 * @param array    $params
	 */
	protected static function render_template( $viewFile, $params = array() ) {
		$path = GSC_PATH . '/templates/' . $viewFile . '.php';

		if ( file_exists( $path ) ) {
			foreach ( $params as $paramKey => $paramValue ) {
				$$paramKey = $paramValue;
			}
			include_once $path;
		} else {
			wp_die( 'The template file (' . esc_html( $viewFile ) . '.php) not found!' );
		}
	}

	/**
	 * Processing update widget id form
	 */
	public static function gsc_post_update_widget() {
		if ( self::post( 'gsc_update_widget' ) && self::check_access() && ! empty( self::$settings['api_key'] ) ) {
			self::$settings['widget_id']   = self::post( 'gsc_widget_id', null );
			self::$settings['widget_link'] = self::post( 'gsc_widget_link', null );
			echo wp_json_encode(
				array(
					'error' => ! self::update( self::$settings ),
				)
			);
			wp_die();
		}
	}

	/**
	 * Get value from $_POST
	 *
	 * @param $param
	 * @param bool|false $allowEmpty
	 * @param null       $default
	 *
	 * @return null
	 */
	protected static function post( $param, $allowEmpty = false, $default = null ) {
		if ( ( isset( $_POST[ $param ] ) && $allowEmpty ) || ( ! empty( $_POST[ $param ] ) && ! $allowEmpty ) ) {
			return sanitize_text_field( wp_unslash( $_POST[ $param ] ) );
		} else {
			return $default;
		}
	}

	/**
	 * Sign out action
	 */
	public static function action_admin_menu_sign_out() {
		self::check_access_die();
		$options                         = self::$settings;
		$options['clear_api_key_action'] = 'gsc_post_clear_api_key';

		self::render_template(
			'sign_out', array(
				'options' => $options,
			)
		);
	}

	/**
	 * Processing sign out form
	 */
	public static function gsc_post_clear_api_key() {
		if ( self::post( 'gsc_clear_api_key' ) && self::check_access() ) {
			self::$settings['api_key'] = null;
			echo wp_json_encode(
				array(
					'error'         => ! self::update( self::$settings ),
					'redirect_link' => admin_url( 'admin.php?page=' . self::$actions['guest']['sign-in']['slug'] ),
				)
			);
			wp_die();
		}
	}

	/**
	 * Sign up action
	 */
	public static function action_admin_menu_sign_up() {
		self::check_access_die();

		$data['email'] = get_option( 'admin_email' );
		$data['name']  = get_option( 'blogname' );
		$data['site']  = get_option( 'siteurl' );

		$options                   = self::$settings;
		$options['success_action'] = 'gsc_post_sign_in';
		$options['form_type']      = 'sign-up';
		$options['api_url']        = self::$registerLink;
		$options['timezone']       = get_option( 'gmt_offset' );
		$options['timezone_name']  = get_option( 'timezone_string' );

		$socialParams                  =
		'&name=' . rawurlencode( $data['name'] ) .
		'&site=' . rawurlencode( $data['site'] ) .
		'&timezone=' . rawurlencode( $options['timezone'] ) .
		'&timezone_name=' . rawurlencode( $options['timezone_name'] ) .
		'&utm_campaign=WordpressPlugin&utm_medium=plugin';
		$options['fb_social_link']     = self::$fbSocialLink . $socialParams;
		$options['google_social_link'] = self::$googleSocialLink . $socialParams;

		self::render_template(
			'sign_up',
			array(
				'sign_in_link' => admin_url( 'admin.php?page=' . self::$actions['guest']['sign-in']['slug'] ),
				'data'         => $data,
				'options'      => $options,
			)
		);
	}

	/**
	 * Sign in action
	 */
	public static function action_admin_menu_sign_in() {
		self::check_access_die();

		$options                       = self::$settings;
		$options['success_action']     = 'gsc_post_sign_in';
		$options['form_type']          = 'sign-in';
		$options['api_url']            = self::$loginLink;
		$options['fb_social_link']     = self::$fbSocialLink;
		$options['google_social_link'] = self::$googleSocialLink;

		$data['email'] = get_option( 'admin_email' );

		self::render_template(
			'sign_in',
			array(
				'sign_up_link' => admin_url( 'admin.php?page=' . self::$actions['guest']['sign-up']['slug'] ),
				'data'         => $data,
				'options'      => $options,
			)
		);
	}

	/**
	 * Processing a sign up form
	 */
	public static function gsc_post_sign_in() {
		if ( self::post( 'gsc_api_key' ) && self::check_access() ) {
			self::$settings['api_key'] = self::post( 'gsc_api_key' );
			self::update( self::$settings );
			echo wp_json_encode(
				array(
					'redirect_link' => admin_url( 'admin.php?page=' . self::$actions['index']['slug'] ),
				)
			);
			wp_die();
		}
	}

	/**
	 * Redirect rules
	 */
	public static function redirect_rule() {
		$action = self::get( 'page' );
		if ( $action === self::$actions['index']['slug'] && empty( self::$settings['api_key'] ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=' . self::$actions['guest']['sign-up']['slug'] ) );
		}
	}

	/**
	 * Get value from $_GET
	 *
	 * @param $param
	 * @param bool|false $allowEmpty
	 * @param null       $default
	 *
	 * @return null
	 */
	protected static function get( $param, $allowEmpty = false, $default = null ) {
		if ( ( isset( $_GET[ $param ] ) && $allowEmpty ) || ( ! empty( $_GET[ $param ] ) && ! $allowEmpty ) ) {
			return wp_unslash( $_GET[ $param ] );
		} else {
			return $default;
		}
	}

	/**
	 * Scenarios list
	 *
	 * @return array
	 */
	protected static function scenarios() {
		return array(
			'auth'     => array( 'email', 'password' ),
			'register' => array( 'name', 'email', 'password', 'site' ),
		);
	}

	/**
	 * Compare Urls
	 *
	 * @param $url1
	 * @param $url2
	 *
	 * @return bool
	 */
	protected static function compare_urls( $url1, $url2 ) {
		$url1 = trim( str_replace( array( 'http://', 'https://' ), '', $url1 ), '/' );
		$url2 = trim( str_replace( array( 'http://', 'https://' ), '', $url2 ), '/' );

		return $url1 === $url2;
	}

}
