<?php
/**
 * Authentication with usertoken.
 * Then consume API.
 *
 * @example plugins/API/client2-example.php?usertoken=mytoken&path=access_log
 *
 * @package REST API plugin
 */

// Load cURL class.
require_once '../../classes/curl.php';

$script_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) .
	'://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

$api_url = str_replace( basename( __FILE__ ), 'api.php', $script_url );

$auth_url = str_replace( basename( __FILE__ ), 'auth.php', $script_url );

if ( empty( $_REQUEST['usertoken'] ) )
{
	echo 'Error: no usertoken. Add ?usertoken=XXX to the URL where XXX is your user token to access the API.';

	echo '<br />';

	echo 'Note: add &path=XXX to the URL to access a specific API point.';

	exit;
}

$curl = new curl;

$response = $curl->post( $auth_url, array( 'usertoken' => $_REQUEST['usertoken'] ) );

$response = _getJson( $response );

if ( empty( $response['access_token'] ) )
{
	var_dump( $response );

	exit;
}

// Path defaults to /openapi.
$request_path = 'openapi';

$path = isset($_GET['path']) ? $_GET['path'] : $_POST['path']; 

if ( ! empty( $path ) )
{
	$request_path = $path ;

	// Can omit 'records/'.
	if ( strpos( $request_path, 'records/' ) === false )
	{
		$request_path = 'records/' . $request_path;
	}
}

if ( strpos( $request_path, '/' ) !== 0 )
{
	$request_path = '/' . $request_path;
}

$api_url_path = $api_url . $request_path;

// Send our access_token in the X-Authorization HTTP header.
$curl->setHeader( 'X-Authorization: Bearer ' . $response['access_token'] );

if (!empty($_POST)){	
	$data = $_POST['data'];
	$response = $curl->post( $api_url_path, $data );	
}
else {
	$response = $curl->get( $api_url_path);
}


header( 'Content-Type: application/json' );

 echo $response;

exit;


function _getJson( $data )
{
	$decoded = json_decode( $data, true );

	if ( json_last_error() !== JSON_ERROR_NONE )
	{
		return $data;
	}

	return $decoded;
}
