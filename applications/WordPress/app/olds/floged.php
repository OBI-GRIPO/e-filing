<?php

	header('Content-type: application/json');
	header('Cache-Control: no-cache, must-revalidate');

	session_start();	
	if($_SESSION['x-jwt-token']==''){
		$data["Unauthorized"] = 1;
		die(json_encode($data));	
	}		
	
	$formpath = "http://formio-api:3001/current";
	$ch = curl_init();

	$headers[] = 'x-jwt-token: ' . $_SESSION['x-jwt-token'];	
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $formpath);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);

	if($server_output == "Unauthorized"){
		$_SESSION['x-jwt-token'] = "";
		$data["Unauthorized"] = 1;
		echo json_encode($data);
	}else{
		$data["Unauthorized"] = 0;
		echo json_encode($data);
	}


?>
