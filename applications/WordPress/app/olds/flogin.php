<?php

	header('Content-type: application/json');
	header('Cache-Control: no-cache, must-revalidate');

	$formpath = "http://formio-api:3001/user/login";	
	$Email = trim($_POST['Email']);
	$Password = trim($_POST['Password']);
	$body = '{
    	"data": {
        	"email": "' . $Email . '",
        	"password": "' . $Password . '"
		}
	}';

	if(!filter_var($Email, FILTER_VALIDATE_EMAIL)){
		$data['Error'] = 101;
		$data['ErrorDescr'] = 'Μη έγκυρο email!';
		die(json_encode($data));	
	}	

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));	
	curl_setopt($ch, CURLOPT_URL, $formpath);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	$server_output = curl_exec($ch);
	curl_close ($ch);
	$headers = get_headers_from_curl_response($server_output);

	session_start();
	if($headers['x-jwt-token'] != ''){
		$_SESSION['Email'] = $Email;
		$_SESSION['x-jwt-token'] = $headers['x-jwt-token'];	
		$errcode = 0;
		$errdescr = '';
	}else{
		$_SESSION['Email'] = "";
		$_SESSION['x-jwt-token'] = "";
		$errcode = 101;
		$errdescr = 'Το Email ή το password είναι λανθασμένα!';
	}


	$data['Error'] = $errcode;
	$data['ErrorDescr'] = $errdescr;
	echo json_encode($data);

function get_headers_from_curl_response($response){
    $headers = array();
    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
    foreach (explode("\r\n", $header_text) as $i => $line)
        if ($i === 0)
            $headers['http_code'] = $line;
        else{
            list ($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }
    return $headers;
}

?>
