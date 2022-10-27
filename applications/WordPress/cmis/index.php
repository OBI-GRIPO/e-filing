<?php

//error_log(print_r($_COOKIE,true));
//error_log(print_r(getallheaders(),true));
//TODO na elenxo to cookie ovi_internal
// mporo ston browser sthn consola na to vazo me document.cookie="ovi_internal=true; expires=Thu, 27 Dec 2040 12:00:00 UTC; path=/wp-content/cmis";




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

//Gia na mporeso na to tsekaro auto prepei me kapio tropo na grafo kati sta metadata tou eggrafou

require_once ('library/cmis-lib.php');
require_once ('config.php');
require_once ('JWT.php');

//192.168.3.32

$token=null;
$userid="";
if ($ONWP&&$_COOKIE["wp_jwtToken"]!=""){
$token = JWT::decode($_COOKIE["wp_jwtToken"], $formio_jwt_secret);
$userid=$token->user->_id;
}


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	exit;
} else
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$destination = trim($_POST['dir']);
if ($destination=="efiling"){
$AlfFolderUUID="9aa6df85-0ada-4bd7-ab70-5e44e06e0e12";
} else
if ($destination=="registration"){
$AlfFolderUUID="1a06a5c8-46ad-43bd-be39-ee7b55f863da";   
} else {
 header("HTTP/1.1 403 Forbidden");	
 die("Λάθος ρυθμίσεων.");
}

$folder_id = "workspace://SpacesStore/".$AlfFolderUUID;
$filename = trim($_FILES['file']['name']);

$name=trim($_POST['name']);

$tmp_filename = trim($_FILES['file']['tmp_name']); //TODO elenxos an to name einai epitrepomeno dil pdf..
$tmp_filesize = trim($_FILES['file']['size']);
$tmp_filetype = trim($_FILES['file']['type']); //TODO elenxos an to type einai epitrepomeno dil application/pdf ..

if ($destination=="registration"){
//check pdf signature existanse
 $content = file_get_contents($tmp_filename);
 $regexp = '#ByteRange.*\[.*(\d+) (\d+) (\d+) (\d+).*]#'; // subexpressions are used to extract b and c
 $result = [];
 preg_match_all($regexp, $content, $result);
 if (isset($result[2]) && isset($result[3]) && isset($result[2][0]) && isset($result[3][0]))
 {
     $start = $result[2][0];
     $end = $result[3][0];     
     if ($stream = fopen($tmp_filename, 'rb')) {
         $signature = stream_get_contents($stream, $end - $start - 2, $start + 1); // because we need to exclude < and > from start and end
         fclose($stream);
     }
 }
if ($signature==null){
 header("HTTP/1.1 451 Unavailable For Legal Reasons");
 die("Το αρχείο δέν περιέχει ψηφιακή υπογραφή.");
 }
}

$handle = fopen($tmp_filename, "rb");
$content = fread($handle, $tmp_filesize);
fclose($handle);

$client = new CMISService($repo_url, $repo_username, $repo_password);
$properties = array("cmis:objectTypeId"=>"cmis:document","cm:title"=>"$name","cmis:description"=>"$userid"); 
$options =  array("title"=>"$name","summary"=>"");

try {
$cmis=$client->createDocument($folder_id,$name,$properties,$content,$tmp_filetype,$options);
} catch (Exception $e)
{
 error_log(print_r($e->getMessage(),true));
 header("HTTP/1.1 500 Internal Server Error");
 die();	
}
$uuid=$cmis->id;
//To formio exei bug sxetiko me to url pou exei diorthothei.
//https://github.com/formio/formio.js/commit/535e76877f605fc2ce5a31d77403f213c99ab6b1
//Logo ton depentecies ekana apeuthias patch sto volume...Se periptosh neoteris ekdosis prepei na to ksanado. 

//TODO stin getfile na elenxo an ontos einai diko tou!
header("HTTP/1.1 201 Created");

print '{
  "url": "https://efiling.obi.gr/wp-content/cmis/?f='.urlencode($uuid).'", 
  "name": "'.$filename.'",
  "id": "'.$uuid.'",
  "size": "'.$tmp_filesize.'",
  "cmis:objectId":'.json_encode($cmis->properties["cmis:objectId"]).'
}'; //, "cmis":'.json_encode($cmis).' ////  "cmis:objectId":'.json_encode($cmis->properties["cmis:objectId"]).',
die();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

$ref = $_SERVER['HTTP_REFERER'];
$refData = parse_url($ref);

if (!isset($_GET['f'])) die();
$doc_id=$_GET['f'];

$client = new CMISService($repo_url, $repo_username, $repo_password);
try{
$myDoc = $client->getObject($doc_id);
} catch (Exception $e)
{
die();	
}

//TODO sthn periptosh tou registration den yparxei description. Me auton ton elegxo to epitrepei..Pithanon na epitrepei kai alla arxeia ektos efarmoghs an mpoun.
$owner=$myDoc->properties['cmis:description'];
if (!$ONBONITA && $owner!=$userid) {
                header("HTTP/1.1 403 Forbidden");
                die();
}

$name = urlencode($myDoc->properties['cmis:name']);
$mime = $myDoc->properties['cmis:contentStreamMimeType'];
$size = $myDoc->properties['cmis:contentStreamLength'];
$quoted =sprintf('"%s"', addcslashes($name, '"\\'));	

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header('Content-Description: File Transfer');
header('Content-Type: '. $mime );
header('Content-language: el');

if (strpos($_SERVER['HTTP_USER_AGENT'] , 'irefox') !== false) {
  $quoted=str_replace ("+","%20",$quoted);
  $quoted=substr($quoted, 1, -1);
  header("Content-Disposition: attachment; filename*=UTF-8''$quoted");
} else {
  $quoted=str_replace ("+"," ",$quoted);
  header("Content-Disposition: attachment; filename=$quoted");
}

header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $size);
print $client->getContentStream($doc_id);
die();

} else 
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
//TODO na kano allow to delete mono an to parent folder einai ena apo ta 
//$AlfFolderUUID="9aa6df85-0ada-4bd7-ab70-5e44e06e0e12";

//$AlfFolderUUID="1a06a5c8-46ad-43bd-be39-ee7b55f863da"; 

//Auto simenh oti prepei na to katevaso kai na tsekaro ta properties.

$client = new CMISService($repo_url, $repo_username, $repo_password);
try{
//Na to energopoihso mono an ta parent einai kapoia apo ta parapano
// $delDoc = $client->deleteObject($_REQUEST["f"]);//TODO check if belongs to userid else exit	
} catch (Exception $e)
{
die();
}
header("HTTP/1.1 204 No Content");
die();
}
else
{
print $_SERVER['REQUEST_METHOD'];
}
