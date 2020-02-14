<?php

require_once '../../config.inc.php';

require_once 'includes/common.fnc.php';

RESTAPISession();

main([
	'default' => [
		'api.php' => [
			// Should be defined in config.inc.php.
			'secret' => ( defined( 'ROSARIO_REST_API_SECRET' ) ? ROSARIO_REST_API_SECRET : 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ik5UWXhRalU0TmpreU9ESXpORFkxT1RVM01FRkRNRVF3UlRSR1JqRkZPVGxDUVRrNE1EZ3hNZyJ9.eyJpc3MiOiJodHRwczovL3ZhbmlhbmV2ZXMuYXV0aDAuY29tLyIsInN1YiI6IkwxNHg2dVJPcjNMTUh4QzdORVJRV1RyNGE0SUNtQ3dLQGNsaWVudHMiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0L3Jvc2FyaW9zaXMvcGx1Z2lucy9SRVNUX0FQSS9jbGllbnQtZXhhbXBsZS5waHAiLCJpYXQiOjE1ODE1ODgyNzUsImV4cCI6MTU4MTY3NDY3NSwiYXpwIjoiTDE0eDZ1Uk9yM0xNSHhDN05FUlFXVHI0YTRJQ21Dd0siLCJndHkiOiJjbGllbnQtY3JlZGVudGlhbHMifQ.4wQDz39tNqr_5zM11cWORlagincVwU6FuZU9RrFjjU15z1jhPP8jFV79jbjqA9kG3X64SKamEMf-ZXBIMnvIkznWRaUkrEQLfjCOs4te0ewNfn0yMkoebkSDnFWx_Yn900Mk6ll9KS--AhaAamvxXGq1tJgpzeG6aQJZ1I5fdUdPXG4wB4KTBQSiu1kuaLV0sjzx0ZRQehXWE1eY50UDQAF72lJE1XDQrl8mlbk-tUqcdK5Tn-8Ba3LJPFV_S70K5wK8YtMRyjeVHFKteXH5Tyjnd7P13DpKBIh8_M-kdfoorLkgljpGTUZr5XVjAvKO3MApHsxkuuSgJaRfy_1Vzg' ),
			// Always redirect to same URL, if need be.
			'redirects' => ( isset( $_GET['redirect_uri'] ) ? $_GET['redirect_uri'] : '' ),
			'validate' => 'RosarioAPILogin',
		],
	],
]);
