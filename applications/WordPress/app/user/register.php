<?php
require 'config.php';
require_once('../fioproxy/Formio.php');

header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

/* Checks if the user already exist in submission */
function alreadyExist($checkEmail){
	global $formio_admin_email, $formio_admin_password;
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($formio_admin_email, $formio_admin_password);
    $path = 'user/exists?data.email='. $checkEmail;
    $response = $formio->get($path);
    return !!$response['body']['_id'];
}

/* post to node for bonita start*/
function postToBonita($body){
	global $username,$password,$obifurl;
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));	
	curl_setopt($ch, CURLOPT_URL, $obifurl);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0); //To get only body
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
	$server_output = curl_exec($ch);
	curl_close ($ch);
	$answer = json_decode($server_output);
	if ($answer->caseId!=null)
	{
		die('{"location":"https://efiling.obi.gr/εφαρμογή/εγγραφή/ευχαριστούμε"}');//Ξεκίνησε η διαδικασία ταυτοποίησης σας
    }
    error_log("Send to bonita error".$server_output);
    die('{"location":"https://efiling.obi.gr/εφαρμογή/εγγραφή/πρόβλημα"');//TODO may send email to tech staff
}

function decrypt_stuff($code) {
	$key = "OBI33!naiT0pius.";
	$iv = "fedcba9876543210";
	return utf8_encode(trim(openssl_decrypt($code,"AES-128-CBC",$key,OPENSSL_ZERO_PADDING,$iv)));
}

$decrypted = decrypt_stuff(urldecode($_POST['token']));//urldecode????
if ($decrypted=="")
	$decrypted=decrypt_stuff(urlencode($_POST['token']));
if ($decrypted=="")
	$decrypted=decrypt_stuff($_POST['token']);
if ($decrypted=="")
	$decrypted=decrypt_stuff(preg_replace("/ /","+",$_POST['token'])); //WORDPRESS Fk

$registerData = json_decode($decrypted);

if (json_last_error() === JSON_ERROR_NONE) {

	//to prevent case of recreation of registered user
	if (!alreadyExist($registerData->email)) { //TODO check if bonita process start and prevent multiclicks

		//pass for manual review
		$body='{"submission":{"_id":"'.$registerData->submission_id.'","form":"'.$registerData->form_id.'","identification_method":"'.$registerData->identification_method.'","email":"'.$registerData->email.'","plain":"'.$registerData->plain.'" }}';
		if ($registerData->identification_method!="gsis") {

			//call bonita cause we need manual review 
			postToBonita($body);
		}

		//pass token to gsis oauth2 procedure
		//there we update the form submission with taxis data
		die('{"location":"https://efiling.obi.gr/gsis?fiot='.$_POST['token'].'"}');

	} else {

		$message = urlencode("You already have authenticate your account, use your credentials to login.");
		die('{"location":"https://efiling.obi.gr/εφαρμογή/είσοδος/?register=sucess&method='.$registerData->identification_method.'&message='.$message.'"}');
	}
} else {
	
   	$data["mailformed"] = 1;
    	$data["error"] =json_last_error_msg ();
    	$data["req"] = $_REQUEST['p'];
		header("Location: https://efiling.obi.gr/");
		error_log(json_encode($data));
		die('{"location":"https://efiling.obi.gr/"}');  	

}
