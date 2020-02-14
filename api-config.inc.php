<?php

require_once '../../config.inc.php';

require_once 'includes/common.fnc.php';

RESTAPISession();

$config = new Tqdev\PhpCrudApi\Config([
	'driver' => 'pgsql',
	'port' => $DatabasePort,
	'address' => $DatabaseServer,
	'username' => $DatabaseUsername,
	'password' => $DatabasePassword,
	'database' => $DatabaseName,
	'controllers' => 'records,openapi,columns',
	// @link https://github.com/mevdschee/php-crud-api/tree/master#middleware
	'middlewares' => 'cors,authorization,sanitation,jwtAuth',
	// @link https://github.com/mevdschee/php-crud-api#jwt-authentication
	'jwtAuth.secret' => ( defined( 'ROSARIO_REST_API_SECRET' ) ? ROSARIO_REST_API_SECRET : 'defaultPassphrase' ),
	// Do not allow operations for PASSWORD column (STUDENTS & STAFF tables mainly).
	// @link https://github.com/mevdschee/php-crud-api/tree/master#authorizing-tables-columns-and-records
	'authorization.columnHandler' => function ($operation, $tableName, $columnName) {
		return $columnName !== 'password';
	},
	// Sanitize input: strip HTML tags.
	// @todo Edge cases: find a solution to allow for HTML fields such as templates?
	// @link https://github.com/mevdschee/php-crud-api/tree/master#sanitizing-input
	'sanitation.handler' => function ($operation, $tableName, $column, $value) {
		return strip_tags( $value );
	},
    'openApiBase' => '{"info":{"title":"RosarioSIS REST API","version":"1.0"}}',
]);

$request = Tqdev\PhpCrudApi\RequestFactory::fromGlobals();
$api = new Tqdev\PhpCrudApi\Api($config);
$response = $api->handle($request);
Tqdev\PhpCrudApi\ResponseUtils::output($response);
