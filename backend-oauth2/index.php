<?php

require __DIR__ . '/src/requests/AuthorizationCode.php';
require __DIR__ . '/src/requests/RequestToken.php';
require __DIR__ . '/src/requests/UserMeasure.php';
require __DIR__ . '/src/http/HttpClient.php';

require __DIR__ . '/src/OAuth2Controller.php';
require __DIR__ . '/src/MeasureController.php';

use App\controllers\MeasureController;
use App\controllers\OAuth2Controller;

define('WITHINGS_CLIENT_ID', '');
define('WITHINGS_CLIENT_SECRET', '');
define('WITHINGS_REDIRECT_URL', '');
define('WITHINGS_WBSAPI_URL', 'https://wbsapi.withings.net/v2');
define('WITHINGS_ACCOUNT_URL', 'https://account.withings.com');


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$withingsAuthUrl = WITHINGS_REDIRECT_URL . "/auth";
$path = null;

if (isset($uri[2])) {
	$path = $uri[2];
}

$authController = new OAuth2Controller(
	WITHINGS_CLIENT_ID,
	WITHINGS_CLIENT_SECRET,
	WITHINGS_REDIRECT_URL,
	WITHINGS_WBSAPI_URL,
	WITHINGS_ACCOUNT_URL,
	"withings_test"
);


if (isset($uri[1])) {
	if ($uri[1] === "auth") {
		$authController->requestAuthorizationCode();
	} else {
		$authController->getRequestToken();
	}
}
$accessToken = $_SESSION['access_token'];
$expiresAt = $_SESSION['expires_at'];

if (!empty($accessToken) && !empty($expiresAt) && $expiresAt >= new DateTime()) {
	$measureController = new MeasureController(
		WITHINGS_WBSAPI_URL,
		$accessToken
	);
	$measureController->getMeasures();

?>
	<html>

	<head>
		<title>User Weight</title>
		<style>
			body {
				font-family: Arial, sans-serif;
				background-color: #f4f4f4;
				text-align: center;
				margin: auto;
				padding: 50px;
			}

			h1 {
				color: #333;
			}
		</style>
	</head>

	<body>
		<h1>User Weight</h1>
		<?php if ($weight !== null): ?>
			<p>Your weight is: <?php echo htmlspecialchars($weight); ?> kg</p>
		<?php else: ?>
			<p>Unable to fetch weight data.</p>
		<?php endif; ?>
	</body>

	</html>
<?php
} else { ?>

	<html>

	<head>
		<title>Withings Oauth2</title>
		<style>
			body {
				font-family: Arial, sans-serif;
				background-color: #f4f4f4;
				text-align: center;
				margin: auto;
				padding: 50px;
			}

			h1 {
				color: #333;
			}

			button {
				background-color: rgb(45, 135, 187);
				color: white;
				padding: 15px 32px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				font-size: 16px;
				margin: 4px 2px;
				cursor: pointer;
				border: none;
				border-radius: 4px;
			}

			button:hover {
				background-color: #45a049;
			}
		</style>
	</head>

	<body>
		<h1>Welcome to Withings partner application</h1>
		<form action="<?php echo $withingsAuthUrl; ?>" method="get">
			<button type="submit">Access Withings Account</button>
		</form>
	</body>

	</html>
<?php
}
?>