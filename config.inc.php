<?php
/**
 * Plugin configuration interface
 *
 * @package REST_API plugin
 */

// Check the script is called by the right program & plugin is activated.
if ( $_REQUEST['modname'] !== 'School_Setup/Configuration.php'
	|| ! $RosarioPlugins['REST_API']
	|| $_REQUEST['modfunc'] !== 'config' )
{
	$error[] = _( 'You\'re not allowed to use this program!' );

	echo ErrorMessage( $error, 'fatal' );
}

// Note: no need to call ProgramTitle() here!

if ( isset( $_REQUEST['save'] )
	&& $_REQUEST['save'] === 'true' )
{
	if ( $_REQUEST['values']['PROGRAM_USER_CONFIG']
		&& $_POST['values']
		&& AllowEdit() )
	{
		// Update the PROGRAM_USER_CONFIG table.
		$sql = '';

		if ( isset( $_REQUEST['values']['PROGRAM_USER_CONFIG'] )
			&& is_array( $_REQUEST['values']['PROGRAM_USER_CONFIG'] ) )
		{
			foreach ( (array) $_REQUEST['values']['PROGRAM_USER_CONFIG'] as $column => $value )
			{
				ProgramUserConfig( 'REST_API', User( 'STAFF_ID' ), array( $column => $value ) );
			}
		}

		if ( $sql != '' )
		{
			DBQuery( $sql );

			$note[] = button( 'check' ) . '&nbsp;' . _( 'The plugin configuration has been modified.' );
		}
	}

	// Unset save & values & redirect URL.
	RedirectURL( 'save', 'values' );
}


if ( empty( $_REQUEST['save'] ) )
{
	$error = _PHPPostgreSQLCheck();

	// Check Secret passphrase is defined in config.inc.php.
	if ( ! defined( 'ROSARIO_REST_API_SECRET' ) )
	{
		$error[] = dgettext( 'REST_API', 'Please define the <code>ROSARIO_REST_API_SECRET</code> constant in the config.inc.php file. Check installation instructions for more information.' );
	}

	echo '<form action="Modules.php?modname=' . $_REQUEST['modname'] . '&tab=plugins&modfunc=config&plugin=REST_API&save=true" method="POST">';

	DrawHeader( '', SubmitButton( _( 'Save' ) ) );

	echo ErrorMessage( $note, 'note' );

	echo ErrorMessage( $error, 'error' );

	echo '<br />';

	PopTable(
		'header',
		dgettext( 'REST_API', 'REST API' )
	);
	
	$script_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) .
		'://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

	$api_url = str_replace( 'Modules.php', 'plugins/REST_API/api.php', $script_url );

	$auth_url = str_replace( 'Modules.php', 'plugins/REST_API/auth.php', $script_url );

	$example_client_url = str_replace( 'Modules.php', 'plugins/REST_API/client-example.php', $script_url );

	$api_config = ProgramUserConfig( 'REST_API' );

	$api_user_token = ! empty( $api_config['USER_TOKEN'] ) ? $api_config['USER_TOKEN'] : '';

	echo '<table class="width-100p">';

	echo '<tr><td>' . NoInput(
		'<code>' . $api_url . '</code>',
		dgettext( 'REST_API', 'API URL' )
	) . '</td></tr>';

	echo '<tr><td>' . NoInput(
		'<code>' . $auth_url . '</code>',
		dgettext( 'REST_API', 'Authentication URL' )
	) . '</td></tr>';

	$example_client_title = dgettext( 'REST_API', 'Example client' );
	$example_client_value = '<code>' . $example_client_url . '</code>';

	if ( ! empty( $api_user_token ) )
	{
		$example_client_url .= '?usertoken=' . $api_user_token;

		$example_client_value = '<a href="' . $example_client_url . '" target="_blank">' . $example_client_url . '</a>';

		$example_client_title .= '<div class="tooltip"><i>' .
			_( 'Add &path=students to the URL to get students.' ) .
			'</i></div>';
	}

	echo '<tr><td>' . NoInput(
		$example_client_value,
		$example_client_title
	) . '</td></tr>';

	$api_user_token_value = empty( $api_user_token ) ? bin2hex( openssl_random_pseudo_bytes( 16 ) ) : $api_user_token;

	echo '<tr><td>' . TextInput(
		$api_user_token_value,
		'values[PROGRAM_USER_CONFIG][USER_TOKEN]',
		dgettext( 'REST_API', 'User Token' ),
		'pattern=".{32,}"',
		! empty( $api_user_token )
	) . '</td></tr>';

	echo '</table>';

	PopTable( 'footer' );

	echo '<br /><div class="center">' . SubmitButton( _( 'Save' ) ) . '</div></form>';
}

/**
 * Check PHP min version, PostgreSQL min version.
 *
 * @return array Error messages for failed checks.
 */
function _PHPPostgreSQLCheck()
{
	$ret = array();

	if ( version_compare( PHP_VERSION, '7.0' ) == -1 )
	{
		$ret[] = 'REST API requires PHP 7.0+ to run, your version is : ' . PHP_VERSION;
	}

	$postgresql_version = (float) DBGetOne( "SHOW server_version;" );

	if ( version_compare( $postgresql_version, '9.1' ) == -1 )
	{
		$ret[] = 'REST API requires PostgreSQL 9.1+ to run, your version is : ' . $postgresql_version;
	}

	return $ret;
}
