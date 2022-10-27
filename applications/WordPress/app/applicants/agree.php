<?php

require 'config.php';

header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

function decrypt_stuff($code) {
	$key = "OBI33!naiT0pius.";
	$iv = "fedcba9876543210";
	return utf8_encode(trim(openssl_decrypt($code,"AES-128-CBC",$key,OPENSSL_ZERO_PADDING,$iv)));
}

$decrypted = decrypt_stuff(urldecode($_POST['agree']));//urldecode????
if ($decrypted=="")
	$decrypted=decrypt_stuff(urlencode($_POST['agree']));

if ($decrypted=="")
	$decrypted=decrypt_stuff($_POST['agree']);

if ($decrypted=="")
	$decrypted=decrypt_stuff(preg_replace("/ /","+",$_POST['agree'])); //WORDPRESS Fk

$agreeData = json_decode($decrypted);

if (json_last_error() === JSON_ERROR_NONE) {
	
	//Check if already aggee
	$curl = curl_init();
	curl_setopt_array($curl, array(
    	CURLOPT_RETURNTRANSFER => 1,
    	CURLOPT_USERPWD => $username . ":" . $password,
    	CURLOPT_URL => $obifcheckurl."/?processInstanceId=".$agreeData->{'processInstanceId'},
	));

	$resp = curl_exec($curl);
	
	// Close request to clear up some resources
	curl_close($curl);

	if ($resp=="waiting") {

		//{"form_id":"5b37565f076e80002c4969f6","submission_id":"5b7574d51a7e1d002ce143ff","aplicant_Email":"panagiotis@skarvelis.gr","rootProcessInstanceId":"9153","processInstanceId":"9167","activityInstanceId":"181307"}
		//TODO checks form_id exists? submission_id exists? aplicant_Email is valid? and realy on submission?

    	$body = '{
			"submission":{
				"form":"'.$agreeData->{'form_id'}.'",
				"_id":"'.$agreeData->{'submission_id'}.'"
			},
			"aplicant":{
				"email":"'.$agreeData->{'aplicant_Email'}.'"
			}
		}';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));	
		curl_setopt($ch, CURLOPT_URL, $obifaproveurl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		$server_output = curl_exec($ch);
		curl_close ($ch);
		$resp=json_decode($server_output);

		if (json_last_error() === JSON_ERROR_NONE) {
			if ($resp->caseId) {
				header("Location: https://efiling.obi.gr/εφαρμογή/αποδοχή-καταθέτη/ευχαριστούμε/");
				die();
			}
		}
		$data["mailformed"] = 1;
    	$data["error"] =json_last_error_msg ();
    	$data["message"] = $server_output;
    	$data["req"] = $_REQUEST['agree'];
		header("Location: https://efiling.obi.gr/");
		die(json_encode($data));

	} else {
    	$data["mailformed"] = 1;
    	$data["error"] =json_last_error_msg ();
    	$data["message"] = $resp;
    	$data["req"] = $_REQUEST['agree'];
		header("Location: https://efiling.obi.gr/");
		die(json_encode($data));
	}

} else {

	$data["mailformed"] = 1;
	$data["error"] =json_last_error_msg ();
	$data["req"] = $_REQUEST['agree'];
	header("Location: https://efiling.obi.gr/");
	die(json_encode($data));	
}
