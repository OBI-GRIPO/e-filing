<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: DELETE, GET, POST, OPTIONS'); //TODO to delete?
require_once('./Formio.php');

//Login user
$formio = new Formio('http://formio-api:3001', array());
$user = $formio->login('obiadmin@obi.gr', 'A9m_g+ZjX~~<X6-B');//TODO apo to SESSION 
//an den yparxei session redirect sthn login

//print_r($user);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	exit;
} else
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
header('Content-Type: application/json');
error_log(print_r($_POST,true));	
print '{}'; //TODO na postarei sthn forma
}else if ($_SERVER['REQUEST_METHOD'] === 'GET') {


if (!isset($_GET['f'])) die();


} else 
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
print "you cant";	
}else
{
//TODO na ftiakso to OPTION kai na kano allow to DELETE? kai to GET?
print $_SERVER['REQUEST_METHOD'];
}
