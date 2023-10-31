<?php
	ini_set("display_errors", 1); 		
	error_reporting(E_ALL);

	require "/Applications/XAMPP/xamppfiles/htdocs/inc/bootstrap.php";

	//CORS Header (NOT SECURE AS OF RIGHT NOW)
	header ("Access-Control-Allow-Origin:*");

	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri = explode( '/', $uri );

	if ((isset($uri[2]) && !($uri[2] == 'user' || $uri[2] == 'music')) || !isset($uri[3])) {
		header("HTTP/1.1 404 Not Found");
		echo "BAD 1";
		exit();
	}

	if($uri[2] == 'user') {
		require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
		$objFeedController = new UserController();
		$strMethodName = $uri[3] . 'Action';
		$objFeedController->{$strMethodName}();
	}

	else if ($uri[2] == 'music') {
		require PROJECT_ROOT_PATH . "/Controller/Api/MusicController.php";
		$objFeedController = new MusicController();
		$strMethodName = $uri[3] . 'Action';
		$objFeedController->{$strMethodName}();
	}


?>