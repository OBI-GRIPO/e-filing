<?php

	header('Content-type: application/json');
	header('Cache-Control: no-cache, must-revalidate');

	session_start();

	$headers[] = 'x-jwt-token: ' . $_SESSION['x-jwt-token'];
	
		
	$formpath = $_GET['formpath'];
	$formpath = "http://formio-api:3001/$formpath";
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $formpath);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	if($server_output == "Unauthorized"){
		echo "Unauthorized";
	}else{
		echo $server_output;
	}


?>
