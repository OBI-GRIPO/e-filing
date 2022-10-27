<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: DELETE, GET, POST, OPTIONS'); //TODO to delete?
header('Content-Type: application/json');

$o="";
$r="";
$headers = getallheaders();
if (isset($headers["origin"])){
        $o=$headers["origin"];
        if ($o!="https://efiling.obi.gr"&&$o!="http://formio-api:3001"&&$o!="http://alfresco"&&$o!="http://bonita"){
                header("HTTP/1.1 403 Forbidden");
                die("Απαγορεύετε η πρόσβαση.");
        };
}
else
if (isset($headers["referer"])){
$r=$headers["referer"];
$refData = parse_url($headers["referer"]);
if($refData['host'] !== 'formio-api'&&$refData['host'] !== 'efiling.obi.gr'&&$refData['host'] !== 'alfresco'&&$refData['host'] !== 'bonita') {
                header("HTTP/1.1 403 Forbidden");
                die("Απαγορεύετε η πρόσβαση...");
}
}//TODO allow intenal network anyway.


error_log("REFERER=$r");

//na do an exei X-Bonita-API-Token
//$ONBONITA=array_key_exists ("X-Bonita-API-Token",$_COOKIE);
$ONBONITA=false;

if (strpos($r, 'http://bonita:9090/') !== false) {
    error_log("Inside BONITA");
    $ONBONITA=true;
}

$ONWP=array_key_exists ("wp_jwtToken",$_COOKIE);

if (!$ONBONITA && !$ONWP) {
                header("HTTP/1.1 403 Forbidden");
                die();
}

require_once ('config.php');
require_once ('JWT.php');
require_once ('Formio.php');


$token=null;
$userid="";
if ($ONWP&&$_COOKIE["wp_jwtToken"]!=""){
$token = JWT::decode($_COOKIE["wp_jwtToken"], $formio_jwt_secret);
$userid=$token->user->_id;
 error_log("USERID=".$userid);
}


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit;
} else
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        exit;
} else
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       exit;
} else 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
       //TODO na elenkso thn asfaleia
      $submissionid = $_GET['submissionid'];
      $formid= $_GET['formid'];

           
      if ( $formid!="" && $submissionid!="" ){
         
         if (!$ONBONITA) {
           if (!belongsToUser($formid,$submissionid,$userid)) exit;     
         };
  
         getReport($submissionid);
      }
}

/* Checks if the submisssion belongs to user */
function belongsToUser($formid,$submissionid,$userId){
        global $formio_admin_email, $formio_admin_password;
        $formio = new Formio('http://formio-api:3001', array());
        $user = $formio->login($formio_admin_email, $formio_admin_password);
        $submission=$formio->get("form/".$formid."/submission/".$submissionid);
        $owner= $submission['body']['owner'];
        if ($owner == $userId) return true;
        return false;
}



function getReport($submissionid){

global $jasperusername ,$jasperpassword;

$remote_url = 'http://reports:8080/jasperserver/rest_v2/reports/OBI/OBI_report_new_mf.pdf?submissionid='.$submissionid;


// Create a stream
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header' => "Authorization: Basic " . base64_encode("$jasperusername:$jasperpassword")                 
  )
);

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
$file = file_get_contents($remote_url, false, $context);

header('Content-type: application/pdf');
//header('Content-length:'.length($file));
print($file);
}
