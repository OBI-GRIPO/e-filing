<?php
header('Content-type: application/json');
//header('Cache-Control: no-cache, must-revalidate');

require_once('Formio.php');

	$Email = trim($_POST['Email']);
	$Password = trim($_POST['Password']);
	if(!filter_var($Email, FILTER_VALIDATE_EMAIL)){
		$data['Error'] = 101;
		$data['ErrorDescr'] = 'Μη έγκυρο email!';
		die(json_encode($data));	
	}
	
    if ($Password =="") {
        $data['Error'] = 102;
		$data['ErrorDescr'] = 'Κενό password!';
	    die(json_encode($data));	
    }
    
    $formio = new Formio('https://efiling.obi.gr/formio/', array());
    $user = $formio->login($Email, $Password);
    
    if ($user=="") {
	    $data['Error'] = 103;
		$data['ErrorDescr'] = 'Λανθασμένα στοιχεία εισόδου!';
	    die(json_encode($data));		
	}

    $user['token']=$formio->getToken();
    $_SESSION['x-jwt-token']==$user['token'];
    print json_encode($user);
