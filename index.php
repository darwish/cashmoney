<?php
include 'your-trips.php';
die;
?>

<?php
$base = "/"; // This is running at root (not in a subfolder)
$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

if (strncmp($requestUri, $base, strlen($base)) === 0) {
	$requestPage = substr($requestUri, strlen($base));
} else {
	$requestPage = $requestUri; // Doesn't match the webroot. Not sure what that means.
}

switch ($requestPage) {
	case "":
		$template = "your-trips.php";
		break;

	case "your-trips":
	case "new-expenses":
		$template = "$requestPage.php";
		break;
	
	case "trip-details":
		$template = "trip-details.php";
		break;
		
	default:
		$template = "404.html";
		break;
}

require $template;
?>