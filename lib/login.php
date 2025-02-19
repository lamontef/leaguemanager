<?php
/**
* Login class for the WordPress plugin RacketManager
*
* @author     Paul Moffat
* @package    RacketManager
* @copyright Copyright 2018
*/

class RacketManagerLogin extends RacketManager {

  /**
  * initialize shortcodes
  *
  * @return void
  */
  public function __construct() {
    add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
    add_shortcode( 'custom-password-lost-form', array( $this, 'render_password_lost_form' ) );
    add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
    add_shortcode( 'account-info', array( $this, 'render_member_account_form' ) );

    add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
    add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
    add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
    add_action( 'login_form_register', array( $this, 'do_register_user' ) );
    add_action( 'admin_init', array( $this, 'disable_dashboard' ) );
    add_action( 'wp_print_footer_scripts', array( $this, 'add_captcha_js_to_footer' ) );
    add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
    add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
    add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
    add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );
    add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
    add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );
    add_action( 'member_account_update', array( $this, 'do_member_account_update' ) );

    add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
    add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
    add_filter( 'admin_init' , array( $this, 'register_settings_fields' ) );
    add_filter( 'retrieve_password_message', array( $this, 'racketmanager_retrieve_password_email' ), 10, 4 );
    add_filter( 'password_change_email', array( $this, 'racketmanager_password_change_email' ), 10, 3 );
    add_filter( 'wp_privacy_personal_data_email_content', array( $this, 'racketmanager_privacy_personal_data_email' ), 10, 3 );
    add_filter( 'user_request_action_email_content', array( $this, 'racketmanager_user_request_action_email' ), 10, 2 );
    add_filter( 'wp_new_user_notification_email_admin', array( $this, 'my_wp_new_user_notification_email_admin' ), 10, 3 );
    add_filter( 'wp_new_user_notification_email', array( $this, 'my_wp_new_user_notification_email' ), 10, 3 );
  }

  public function my_wp_new_user_notification_email_admin($wp_new_user_notification_email, $user, $blogname) {

    $user_count = count_users();

    $wp_new_user_notification_email['subject'] = sprintf('[%s] New user %s registered.', $blogname, $user->user_login);
    $wp_new_user_notification_email['message'] = sprintf( "%s has registered to %s.", $user->user_login, $blogname) . "\n\n\r" . sprintf("You now have %d users", $user_count['total_users']);

    return $wp_new_user_notification_email;
  }

  public function my_wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname) {
    global $racketmanager_shortcodes, $racketmanager;

    $start = strpos($wp_new_user_notification_email['message'],'?action=rp&key=') + 15;
    $end = strpos($wp_new_user_notification_email['message'],'&login=');
    $length = $end - $start ;
    $key = substr($wp_new_user_notification_email['message'],$start,$length);
    $vars['site_name'] = $racketmanager->site_name;
    $vars['site_url'] = $racketmanager->site_url;
    $vars['user_login'] = $user->user_login;
    $vars['display_name'] = $user->display_name;
    $vars['action_url'] = wp_login_url() . '?action=rp&key='.$key.'&login='.rawurlencode($user->user_login);
    $vars['email_link'] = $racketmanager->admin_email;
    $wp_new_user_notification_email['message'] = $racketmanager_shortcodes->loadTemplate( 'email-welcome', $vars, 'email' );
    $wp_new_user_notification_email['headers'] = 'Content-Type: text/html; charset=UTF-8';

    return $wp_new_user_notification_email;
  }

  public function racketmanager_wp_email_content_type() {
    return 'text/html';
  }

  public function racketmanager_retrieve_password_email($message, $key, $user_login, $user_data) {
    global $racketmanager_shortcodes, $racketmanager;

    add_filter( 'wp_mail_content_type', array( $this,'racketmanager_wp_email_content_type' ) );
    $vars['site_name'] = $racketmanager->site_name;
    $vars['site_url'] = $racketmanager->site_url;
    $vars['user_login'] = $user_login;
    $vars['display_name'] = $user_data->display_name;
    $vars['action_url'] = wp_login_url() . '?action=rp&key='.$key.'&login='.rawurlencode($user_login);
    $message = $racketmanager_shortcodes->loadTemplate( 'email-password-reset', $vars, 'email' );

    return $message;
  }

  public function racketmanager_password_change_email($passwordChangeMessage, $user_data, $user_data_new) {
    global $racketmanager_shortcodes, $racketmanager;

    add_filter( 'wp_mail_content_type', array( $this,'racketmanager_wp_email_content_type' ) );
    $vars['site_name'] = $racketmanager->site_name;
    $vars['site_url'] = $racketmanager->site_url;
    $vars['user_login'] = $user_data['user_login'];
    $vars['display_name'] = $user_data['display_name'];
    $vars['email_link'] = $racketmanager->admin_email;
    $passwordChangeMessage['message'] = $racketmanager_shortcodes->loadTemplate( 'email-password-change', $vars, 'email' );

    return $passwordChangeMessage;
  }

  public function racketmanager_privacy_personal_data_email($message, $request, $email_data) {
    global $racketmanager_shortcodes, $racketmanager;

    add_filter( 'wp_mail_content_type', array( $this,'racketmanager_wp_email_content_type' ) );
    $vars['site_name'] = $racketmanager->site_name;
    $vars['site_url'] = $racketmanager->site_url;
    $message = $racketmanager_shortcodes->loadTemplate( 'email-privacy-personal-data', $vars, 'email' );

    return $message;
  }

  public function racketmanager_user_request_action_email($message, $email_data) {
    global $racketmanager_shortcodes, $racketmanager;

    add_filter( 'wp_mail_content_type', array( $this,'racketmanager_wp_email_content_type' ) );
    $vars['site_name'] = $racketmanager->site_name;
    $vars['site_url'] = $racketmanager->site_url;
    $message = $racketmanager_shortcodes->loadTemplate( 'email-user-request-action', $vars, 'email' );

    return $message;
  }

  public function disable_dashboard() {
    if (current_user_can('subscriber') && is_admin()) {
      if ( !DOING_AJAX ) {
        wp_redirect(home_url());
        exit;
      }
    }
  }

  /**
  * A shortcode for rendering the login form.
  *
  * @param  array   $vars  Shortcode vars.
  * @param  string  $content     The text content for shortcode. Not used.
  *
  * @return string  The shortcode output
  */
  public function render_login_form( $vars, $content = null ) {
    global $racketmanager_shortcodes, $racketmanager;

    // Parse shortcode vars
    $default_vars = array( 'show_title' => false );
    $vars = shortcode_atts( $default_vars, $vars );
    $show_title = $vars['show_title'];
    $vars['site_name'] = $racketmanager->site_name;
    $vars['site_url'] = $racketmanager->site_url;

    if ( is_user_logged_in() ) {
      return __( 'You are already signed in.', 'racketmanager' );
    }
    // Retrieve recaptcha key
    $vars['recaptcha_site_key'] = get_option( 'racketmanager-recaptcha-site-key', null );
    $action = isset($_GET[('action')]) ? $_GET[('action')]: '';
    if ( isset($action) && $action == 'register' ) {

      // Retrieve possible errors from request parameters
      $vars['errors'] = array();
      if ( isset( $_REQUEST['register-errors'] ) ) {
        $error_codes = explode( ',', $_REQUEST['register-errors'] );

        foreach ( $error_codes as $error_code ) {
          $vars['errors'] []= $this->get_error_message( $error_code );
        }
      }

      if ( is_user_logged_in() ) {
        return __( 'You are already signed in.', 'racketmanager' );
      } elseif ( ! get_option( 'users_can_register' ) ) {
        return __( 'Registering new users is currently not allowed.', 'racketmanager' );
      } else {
        return $racketmanager_shortcodes->loadTemplate( 'form-login', $vars );
      }
    } else {
      // Check if the user just registered
      $vars['registered'] = isset( $_REQUEST['registered'] );

      // Pass the redirect parameter to the WordPress login functionality: by default,
      // don't specify a redirect, but if a valid redirect URL has been passed as
      // request parameter, use it.
      $vars['redirect'] = '';
      if ( isset( $_REQUEST['redirect_to'] ) ) {
        $vars['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $vars['redirect'] );
      } elseif ( wp_get_referer() ) {
        if ( strpos(wp_get_referer(), $racketmanager->site_url ) === 0 ) {
          $vars['redirect'] = wp_validate_redirect( wp_get_referer(), $vars['redirect'] );
        }
      }
      // Error messages
      $errors = array();
      if ( isset( $_REQUEST['login'] ) ) {
        $error_codes = explode( ',', $_REQUEST['login'] );

        foreach ( $error_codes as $code ) {
          $errors []= $this->get_error_message( $code );
        }
      }
      $vars['errors'] = $errors;

      // Check if the user just requested a new password
      $vars['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

      // Check if user just updated password
      $vars['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

      // Check if user just logged out
      $vars['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

      // Render the login form using an external template
      return $racketmanager_shortcodes->loadTemplate( 'form-login', $vars );
    }
  }

  /**
  * Redirect the user to the custom login page instead of wp-login.php.
  */
  public function redirect_to_custom_login() {
    if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
      $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;


      if ( is_user_logged_in() ) {
        $this->redirect_logged_in_user( $redirect_to );
        exit;
      }

      // The rest are redirected to the login page
      $login_url = home_url( 'member-login' );
      if ( ! empty( $redirect_to ) ) {
        $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
      }

      wp_redirect( $login_url );
      exit;
    }
  }

  /**
  * Redirects the user to the correct page depending on whether he / she
  * is an admin or not.
  *
  * @param string $redirect_to   An optional redirect_to URL for admin users
  */
  public function redirect_logged_in_user( $redirect_to = null ) {
    $user = wp_get_current_user();
    if ( user_can( $user, 'manage_options' ) ) {
      if ( $redirect_to ) {
        wp_safe_redirect( $redirect_to );
      } else {
        wp_redirect( admin_url() );
      }
    } else {
      wp_redirect( home_url() );
    }
  }

  /**
  * Redirect the user after authentication if there were any errors.
  *
  * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
  * @param string            $username   The user name used to log in.
  * @param string            $password   The password used to log in.
  *
  * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
  */
  public function maybe_redirect_at_authenticate( $user, $username, $password ) {
    // Check if the earlier authenticate filter (most likely,
    // the default WordPress authentication) functions have found errors
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
      if ( is_wp_error( $user ) ) {
        $error_codes = join( ',', $user->get_error_codes() );

        $login_url = home_url( 'member-login' );
        $login_url = add_query_arg( 'login', $error_codes, $login_url );

        wp_redirect( $login_url );
        exit;
      }
    }

    return $user;
  }

  /**
  * Finds and returns a matching error message for the given error code.
  *
  * @param string $error_code    The error code to look up.
  *
  * @return string               An error message.
  */
  public function get_error_message( $error_code ) {
    switch ( $error_code ) {
      case 'empty_username':
      return __( 'You do have an email address, right?', 'racketmanager' );
      case 'empty_password':
      return __( 'You need to enter a password to login.', 'racketmanager' );
      case 'invalid_email':
      case 'invalid_username':
      return __( "We don't have any users with that email address. Maybe you used a different one when signing up?", 'racketmanager' );
      case 'incorrect_password':
      $err = __( "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?", 'racketmanager' );
      return sprintf( $err, wp_lostpassword_url() );
      case 'email':
      return __( 'The email address you entered is not valid.', 'racketmanager' );
      case 'email_exists':
      return __( 'An account exists with this email address.', 'racketmanager' );
      case 'closed':
      return __( 'Registering new users is currently not allowed.', 'racketmanager' );
      case 'captcha':
      return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'racketmanager' );
      case 'empty_username':
      return __( 'You need to enter your email address to continue.', 'racketmanager' );
      case 'invalid_email':
      case 'invalidcombo':
      return __( 'There are no users registered with this email address.', 'racketmanager' );
      case 'expiredkey':
      case 'invalidkey':
      return __( 'The password reset link you used is not valid anymore.', 'racketmanager' );
      case 'password_reset_mismatch':
      return __( "The two passwords you entered don't match.", 'racketmanager' );
      case 'password_reset_empty':
      return __( "Sorry, we don't accept empty passwords.", 'racketmanager' );
      case 'firstname_field_empty':
      return __( 'First name must be specified', 'racketmanager' );
      case 'lastname_field_empty':
      return __( 'Last name must be specified', 'racketmanager' );
      case 'gender_field_empty':
      return __( 'Gender must be specified', 'racketmanager' );
      case 'no_updates':
      return __( 'No updates to be made', 'racketmanager' );
      default:
      return $error_code;
    }
  }

  /**
  * Redirect to custom login page after the user has been logged out.
  */
  public function redirect_after_logout() {
    $redirect_url = home_url( 'member-login?logged_out=true' );
    wp_safe_redirect( $redirect_url );
    exit;
  }

  /**
  * Returns the URL to which the user should be redirected after the (successful) login.
  *
  * @param string           $redirect_to           The redirect destination URL.
  * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
  * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
  *
  * @return string Redirect URL
  */
  public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
    $redirect_url = home_url();
    if ( ! isset( $user->ID ) ) {
      return $redirect_url;
    }

    if ( user_can( $user, 'manage_options' ) ) {
      // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
      if ( $requested_redirect_to == '' ) {
        $redirect_url = admin_url();
      } else {
        $redirect_url = $requested_redirect_to;
      }
    } else {
      // Use the redirect_to parameter if one is set, otherwise redirect to homepage.
      if ( $requested_redirect_to == '' ) {
        $redirect_url = home_url();
      } else {
        $redirect_url = $requested_redirect_to;
      }
    }

    return wp_validate_redirect( $redirect_url, home_url() );
  }

  /**
  * Redirects the user to the custom registration page instead
  * of wp-login.php?action=register.
  */
  public function redirect_to_custom_register() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
      if ( is_user_logged_in() ) {
        $this->redirect_logged_in_user();
      } else {
        wp_redirect( home_url( 'member-login?action=register' ) );
      }
      exit;
    }
  }

  /**
  * Validates and then completes the new user signup process if all went well.
  *
  * @param string $email         The new user's email address
  * @param string $first_name    The new user's first name
  * @param string $last_name     The new user's last name
  *
  * @return int|WP_Error         The id of the user that was created, or error if failed.
  */
  public function register_user( $email, $first_name, $last_name ) {
    $errors = new WP_Error();

    // Email address is used as both username and email. It is also the only
    // parameter we need to validate
    if ( ! is_email( $email ) ) {
      $errors->add( 'email', $this->get_error_message( 'email' ) );
      return $errors;
    }

    if ( username_exists( $email ) || email_exists( $email ) ) {
      $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
      return $errors;
    }

    // Generate the password so that the subscriber will have to check email...
    $password = wp_generate_password( 12, false );

    $user_data = array(
      'user_login'    => $email,
      'user_email'    => $email,
      'user_pass'     => $password,
      'first_name'    => $first_name,
      'last_name'     => $last_name,
      'nickname'      => $first_name,
    );

    $user_id = wp_insert_user( $user_data );
    wp_new_user_notification( $user_id, NULL, 'both' );

    return $user_id;
  }

  /**
  * Handles the registration of a new user.
  *
  * Used through the action hook "login_form_register" activated on wp-login.php
  * when accessed through the registration action.
  */
  public function do_register_user() {
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
      $redirect_url = home_url( 'member-login?action=register' );

      if ( ! get_option( 'users_can_register' ) ) {
        // Registration closed, display error
        $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
      } elseif ( ! $this->verify_recaptcha() ) {
        // Recaptcha check failed, display error
        $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
      } else {
        $email = $_POST['email'];
        $first_name = sanitize_text_field( $_POST['first_name'] );
        $last_name = sanitize_text_field( $_POST['last_name'] );

        $result = $this->register_user( $email, $first_name, $last_name );

        if ( is_wp_error( $result ) ) {
          // Parse errors into a string and append as parameter to redirect
          $errors = join( ',', $result->get_error_codes() );
          $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
        } else {
          update_user_meta( $result, 'show_admin_bar_front', false );
          // Success, redirect to login page.
          $redirect_url = home_url( 'member-login' );
          $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
        }
      }

      wp_redirect( $redirect_url );
      exit;
    }
  }

  /**
  * Registers the settings fields needed by the plugin.
  */
  public function register_settings_fields() {
    // Create settings fields for the two keys used by reCAPTCHA
    register_setting( 'general', 'racketmanager-recaptcha-site-key' );
    register_setting( 'general', 'racketmanager-recaptcha-secret-key' );

    add_settings_field(
      'racketmanager-recaptcha-site-key',
      '<label for="racketmanager-recaptcha-site-key">' . __( 'reCAPTCHA site key' , 'racketmanager' ) . '</label>',
      array( $this, 'render_recaptcha_site_key_field' ),
      'general'
    );

    add_settings_field(
      'racketmanager-recaptcha-secret-key',
      '<label for="racketmanager-recaptcha-secret-key">' . __( 'reCAPTCHA secret key' , 'racketmanager' ) . '</label>',
      array( $this, 'render_recaptcha_secret_key_field' ),
      'general'
    );
  }

  public function render_recaptcha_site_key_field() {
    $value = get_option( 'racketmanager-recaptcha-site-key', '' );
    echo '<input type="text" id="racketmanager-recaptcha-site-key" name="racketmanager-recaptcha-site-key" value="' . esc_attr( $value ) . '" />';
  }

  public function render_recaptcha_secret_key_field() {
    $value = get_option( 'racketmanager-recaptcha-secret-key', '' );
    echo '<input type="text" id="racketmanager-recaptcha-secret-key" name="racketmanager-recaptcha-secret-key" value="' . esc_attr( $value ) . '" />';
  }

  /**
  * Checks that the reCAPTCHA parameter sent with the registration
  * request is valid.
  *
  * @return bool True if the CAPTCHA is OK, otherwise false.
  */
  private function verify_recaptcha() {
    // This field is set by the recaptcha widget if check is successful
    if ( isset ( $_POST['g-recaptcha-response'] ) ) {
      $captcha_response = $_POST['g-recaptcha-response'];
    } else {
      return false;
    }

    // Verify the captcha response from Google
    $response = wp_remote_post(
      'https://www.google.com/recaptcha/api/siteverify',
      array(
        'body' => array(
          'secret' => get_option( 'racketmanager-recaptcha-secret-key' ),
          'response' => $captcha_response
        )
      )
    );

    $success = false;
    if ( $response && is_array( $response ) ) {
      $decoded_response = json_decode( $response['body'] );
      $success = $decoded_response->success;
    }
    return $success;
  }

  /**
  * An action function used to include the reCAPTCHA JavaScript file
  * at the end of the page.
  */
  public function add_captcha_js_to_footer() {
    echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
  }

  /**
  * Redirects the user to the custom "Forgot your password?" page instead of
  * wp-login.php?action=lostpassword.
  */
  public function redirect_to_custom_lostpassword() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
      if ( is_user_logged_in() ) {
        $this->redirect_logged_in_user();
        exit;
      }

      wp_redirect( home_url( 'member-password-lost' ) );
      exit;
    }
  }

  /**
  * A shortcode for rendering the form used to initiate the password reset.
  *
  * @param  array   $vars  Shortcode vars.
  * @param  string  $content     The text content for shortcode. Not used.
  *
  * @return string  The shortcode output
  */
  public function render_password_lost_form( $vars, $content = null ) {

    global $racketmanager_shortcodes;

    // Parse shortcode vars
    $default_vars = array( 'show_title' => true );
    $vars = shortcode_atts( $default_vars, $vars );

    // Retrieve possible errors from request parameters
    $vars['errors'] = array();
    if ( isset( $_REQUEST['errors'] ) ) {
      $error_codes = explode( ',', $_REQUEST['errors'] );

      foreach ( $error_codes as $error_code ) {
        $vars['errors'] []= $this->get_error_message( $error_code );
      }
    }

    if ( is_user_logged_in() ) {
      return __( 'You are already signed in.', 'racketmanager' );
    } else {
      return $racketmanager_shortcodes->loadTemplate( 'form-password-lost', $vars );
    }
  }

  /**
  * Initiates password reset.
  */
  public function do_password_lost() {
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
      $errors = retrieve_password();
      if ( is_wp_error( $errors ) ) {
        // Errors found
        $redirect_url = home_url( 'member-password-lost' );
        $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
      } else {
        // Email sent
        $redirect_url = home_url( 'member-login' );
        $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
      }

      wp_redirect( $redirect_url );
      exit;
    }
  }

  /**
  * Returns the message body for the password reset mail.
  * Called through the retrieve_password_message filter.
  *
  * @param string  $message    Default mail message.
  * @param string  $key        The activation key.
  * @param string  $user_login The username for the user.
  * @param WP_User $user_data  WP_User object.
  *
  * @return string   The mail message to send.
  */
  public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
    // Create new message
    $msg  = __( 'Hello!', 'racketmanager' ) . "\r\n\r\n";
    $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'racketmanager' ), $user_login ) . "\r\n\r\n";
    $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'racketmanager' ) . "\r\n\r\n";
    $msg .= __( 'To reset your password, visit the following address:', 'racketmanager' ) . "\r\n\r\n";
    $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
    $msg .= __( 'Thanks!', 'racketmanager' ) . "\r\n";

    return $msg;
  }

  /**
  * Redirects to the custom password reset page, or the login page
  * if there are errors.
  */
  public function redirect_to_custom_password_reset() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
      // Verify key / login combo
      $key = preg_replace('/[^a-z0-9]/i', '', $_REQUEST['key']);
      $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
      if ( ! $user || is_wp_error( $user ) ) {
        if ( $user && $user->get_error_code() === 'expired_key' ) {
          wp_redirect( home_url( 'member-login?login=expiredkey' ) );
        } else {
          wp_redirect( home_url( 'member-login?login=invalidkey' ) );
        }
        exit;
      }

      $redirect_url = home_url( 'member-password-reset' );
      $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
      $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

      wp_redirect( $redirect_url );
      exit;
    }
  }

  /**
  * A shortcode for rendering the form used to reset a user's password.
  *
  * @param  array   $vars  Shortcode vars.
  * @param  string  $content     The text content for shortcode. Not used.
  *
  * @return string  The shortcode output
  */
  public function render_password_reset_form( $vars, $content = null ) {
    global $racketmanager_shortcodes;

    // Parse shortcode vars
    $default_vars = array( 'show_title' => false );
    $vars = shortcode_atts( $default_vars, $vars );

    if ( is_user_logged_in() ) {
      return __( 'You are already signed in.', 'racketmanager' );
    } else {
      if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
        $vars['login'] = $_REQUEST['login'];
        $vars['key'] = $_REQUEST['key'];

        // Error messages
        $errors = array();
        if ( isset( $_REQUEST['error'] ) ) {
          $error_codes = explode( ',', $_REQUEST['error'] );

          foreach ( $error_codes as $code ) {
            $errors []= $this->get_error_message( $code );
          }
        }
        $vars['errors'] = $errors;

        return $racketmanager_shortcodes->loadTemplate( 'form-password-reset', $vars );
      } else {
        return __( 'Invalid password reset link.', 'racketmanager' );
      }
    }
  }

  /**
  * Resets the user's password if the password reset form was submitted.
  */
  public function do_password_reset() {
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
      $rp_key = $_REQUEST['rp_key'];
      $rp_login = $_REQUEST['rp_login'];

      $user = check_password_reset_key( $rp_key, $rp_login );

      if ( ! $user || is_wp_error( $user ) ) {
        if ( $user && $user->get_error_code() === 'expired_key' ) {
          wp_redirect( home_url( 'member-login?login=expiredkey' ) );
        } else {
          wp_redirect( home_url( 'member-login?login=invalidkey' ) );
        }
        exit;
      }

      if ( isset( $_POST['password'] ) ) {
        if ( $_POST['password'] != $_POST['rePassword'] ) {
          // Passwords don't match
          $redirect_url = home_url( 'member-password-reset' );

          $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
          $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url ) ;
          $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

          wp_redirect( $redirect_url );
          exit;
        }

        if ( empty( $_POST['password'] ) ) {
          // Password is empty
          $redirect_url = home_url( 'member-password-reset' );

          $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
          $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
          $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

          wp_redirect( $redirect_url );
          exit;
        }

        // Parameter checks OK, reset password
        reset_password( $user, $_POST['password'] );
        wp_redirect( home_url( 'member-login?password=changed' ) );
      } else {
        echo "Invalid request.";
      }

      exit;
    }
  }

  /**
  * A shortcode for rendering the form used to display a member account.
  *
  * @return string  The shortcode output
  */
  public function render_member_account_form() {

    return $this->member_account_form;

  }

  /**
  * Generate the form used to display a member account.
  *
  * @return string  The output
  */
  public function generate_member_account_form() {
    global $racketmanager_shortcodes;

    if ( !is_user_logged_in() ) {
      return __( 'You must be signed in to access this page', 'racketmanager' );
    }

    $current_user = wp_get_current_user();
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'POST':
      if ( isset( $_POST['member_account_nonce_field'] ) && wp_verify_nonce( $_POST['member_account_nonce_field'], 'member_account_nonce' ) ) {
        $user_data = array(
          'user_name' => sanitize_email( $_POST['username'] ),
          'first_name' => sanitize_text_field( $_POST['firstname'] ),
          'last_name' => sanitize_text_field( $_POST['lastname'] ),
          'password' => $_POST['password'],
          'rePassword' => $_POST['rePassword'],
          'contactno' => sanitize_text_field( $_POST['contactno'] ),
          'gender' => sanitize_text_field( $_POST['gender'] ),
          'btm' => sanitize_text_field( $_POST['btm'] )
        );
      } else {
        return __( 'You are not authorised for this action', 'racketmanager' );
      }
      if ( !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {
        $user_data = $this->update_user_profile($current_user, $user_data);
      }
      break;
      case 'GET':
      $user_data = array(
        'user_name' => $current_user->user_email,
        'first_name' => get_user_meta($current_user->ID,'first_name',true),
        'last_name' => get_user_meta($current_user->ID,'last_name',true),
        'contactno' => get_user_meta($current_user->ID,'contactno',true),
        'gender' => get_user_meta($current_user->ID,'gender',true),
        'btm' => get_user_meta($current_user->ID,'btm',true),
      );
      break;
    }

    return $racketmanager_shortcodes->loadTemplate( 'form-member-account', array('user_data' => $user_data) );
  }

  /**
  * Generate the form used to display a member account.
  *
  * @param object  $current_user     current user object
  * @param array   $user_data        user data from form
  * @return array  $user_data        updated user data
  */
  private function update_user_profile($current_user, $user_data) {

    $updates = false;
    $validationErrors = false;

    if ( empty($user_data['user_name']) ) {
      $user_data['user_name_error'] = $this->get_error_message('empty_username');
      $validationErrors = true;
    } elseif ( $user_data['user_name'] != $current_user->user_email ) {
      $updates = true;
    }

    if ( empty($user_data['first_name']) ) {
      $user_data['first_name_error'] = $this->get_error_message('firstname_field_empty');
      $validationErrors = true;
    } elseif ( $user_data['first_name'] != get_user_meta($current_user->ID,'first_name',true) ) {
      $updates = true;
    }

    if ( empty($user_data['last_name']) ) {
      $user_data['last_name_error'] = $this->get_error_message('lastname_field_empty');
      $validationErrors = true;
    } elseif ( $user_data['last_name'] != get_user_meta($current_user->ID,'last_name',true) ) {
      $updates = true;
    }

    if ( empty($user_data['contactno']) ) {
      if ( !empty(get_user_meta($current_user->ID,'contactno',true)) ) {
        $updates = true;
      }
    } elseif ( $user_data['contactno'] != get_user_meta($current_user->ID,'contactno',true) ) {
      $updates = true;
    }

    if ( empty($user_data['gender']) ) {
      $user_data['gender_error'] = $this->get_error_message('gender_field_empty');
      $validationErrors = true;
    } elseif ( $user_data['gender'] != get_user_meta($current_user->ID,'gender',true) ) {
      $updates = true;
    }

    if ( empty($user_data['btm']) ) {
      if ( !empty(get_user_meta($current_user->ID,'btm',true)) ) {
        $updates = true;
      }
    } elseif ( $user_data['btm'] != get_user_meta($current_user->ID,'btm',true) ) {
      $updates = true;
    }

    if ( $user_data['password'] != $user_data['rePassword'] ) {
      $user_data['rePassword_error'] = $this->get_error_message('password_reset_mismatch');
      $validationErrors = true;
    } elseif ( !empty( $user_data['password'] ) ) {
      unset( $user_data['rePassword'] );
      $updates = true;
    }

    if ( $validationErrors ) {
      $user_data['error'] = true;
      $user_data['message'] = __( 'Errors in form', 'racketmanager');
      return $user_data;
    }
    if ( !$updates ) {
      $user_data['message'] = $this->get_error_message('no_updates');
      return $user_data;
    }

    foreach( $user_data as $key => $value ) {
      // http://codex.wordpress.org/Function_Reference/wp_update_user
      if( $key == 'contactno' ) {
        $userid = update_user_meta( $current_user->ID, $key, $value );
      } elseif( $key == 'btm' ) {
        $userid = update_user_meta( $current_user->ID, $key, $value );
      } elseif( $key == 'gender' ) {
        $userid = update_user_meta( $current_user->ID, $key, $value );
      } elseif( $key == 'first_name' ) {
        if ( $user_data['first_name'] != get_user_meta($current_user->ID,'first_name',true) ) {
          $userid = update_user_meta( $current_user->ID, $key, $value );
          $userid = wp_update_user( array( 'ID' => $current_user->ID, 'display_name' => $value.' '.sanitize_text_field( $user_data['last_name'] ) ) );
        }
      } elseif( $key == 'last_name' ) {
        if ( $user_data['last_name'] != get_user_meta($current_user->ID,'last_name',true) ) {
          $userid = update_user_meta( $current_user->ID, $key, $value );
          $userid = wp_update_user( array( 'ID' => $current_user->ID, 'display_name' => sanitize_text_field( $user_data['first_name'] ).' '.$value ) );
        }
      } elseif ( $key == 'password' ) {
        $userid = wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => $value ) );
      } else {
        $userid = wp_update_user( array( 'ID' => $current_user->ID, $key => $value ) );
      }
    }
    $user_data['message'] = __( 'Your profile has been successfully updated', 'racketmanager');
    return $user_data;
  }
}
?>
