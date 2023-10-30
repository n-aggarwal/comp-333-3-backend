<?php
	ini_set("display_errors", 1); 		
	error_reporting(E_ALL);

	require "/Applications/XAMPP/xamppfiles/htdocs/inc/bootstrap.php";
	echo "Hello World";

	echo "Hello World 2\n";

	//CORS Header (NOT SECURE AS OF RIGHT NOW)
	header ("Access-Control-Allow-Origin:*");

	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	echo $uri . "\n";
		
	$uri = explode( '/', $uri );
	echo $uri[2] . "\n";

	if ((isset($uri[2]) && !($uri[2] == 'user' || $uri[2] == 'music')) || !isset($uri[3])) {
		header("HTTP/1.1 404 Not Found");
		echo "BAD 1";
		exit();
	}

	echo "Point 1";
	require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
	echo "Point 2";
	$objFeedController = new UserController();
	$strMethodName = $uri[3] . 'Action';
	$objFeedController->{$strMethodName}();
?>