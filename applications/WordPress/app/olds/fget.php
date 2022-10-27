<?php

	header('Content-type: application/json');
	header('Cache-Control: no-cache, must-revalidate');
	
	$formpath = $_GET['formpath'];
	$formpath = "http://formio-api:3001/$formpath";
	$ch = curl_init();



	session_start();

	$headers[] = 'x-jwt-token: ' . $_SESSION['x-jwt-token'];	
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $formpath);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	//curl_setopt($curl_handle, CURLOPT_HTTPGET, TRUE);
	

	$server_output = curl_exec ($ch);

	curl_close ($ch);



	$Unauthorized["Unauthorized"] = 1;
	if($server_output == "Unauthorized"){
		echo json_encode($Unauthorized);
	}else{
		echo $server_output;
	}


?>
