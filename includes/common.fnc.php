<?php
/**
 * Common functions
 *
 * @package REST API plugin
 */

/**
 * Call before session_start()
 * Proper PHP session & cookie initialization.
 *
 * @param string $session_name Session name. Defaults to 'RosarioSIS REST API'.
 *
 * @return string Session name.
 */
function RESTAPISession( $session_name = 'RosarioSIS REST API' )
{
	session_name( $session_name );

	// See http://php.net/manual/en/session.security.php.
	$cookie_path = dirname( $_SERVER['SCRIPT_NAME'] ) === DIRECTORY_SEPARATOR ?
	    '/' : dirname( $_SERVER['SCRIPT_NAME'] ) . '/';

	session_set_cookie_params(
	    0,
	    $cookie_path,
	    '',
	    //  Cookie secure flag for https.
	    ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) ||
	        ( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == 443 ) ),
	    true
	);

	return $session_name;
}
