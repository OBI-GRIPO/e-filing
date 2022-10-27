<?php
	header('Content-type: application/json');
	header('Cache-Control: no-cache, must-revalidate');

require 'config.php';
require_once('../fioproxy/Formio.php');

/* Checks if the payment attribute already exist in submission
 * payment attributes, first_payment_id, final_payment_id are updated 
 * after successful transaction from bonita workflow */
function alreadyExist($formid,$submissionid,$checkPayment){
	global $formio_admin_email, $formio_admin_password;
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($formio_admin_email, $formio_admin_password);
	if($checkPayment=="first") $attribute="first_payment_id"; else $attribute="final_payment_id";
    $submission=$formio->get("form/".$formid."/submission/".$submissionid);
    $data= $submission['body']['data'];
    return array_key_exists($attribute,$data);
}

function postToBonita($body,$obifurl){
	global $username,$password;
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
	 header("Location: https://efiling.obi.gr/εφαρμογή/πληρωμή/ευχαριστούμε?caseid=".$answer->caseId."&d=".base64_encode ($body));
     die();
    }
    error_log("Send to bonita error".$server_output);
    header("Location: https://efiling.obi.gr/εφαρμογή/πληρωμή/πρόβλημα");
    die($server_output);//TODO may send email to tech staff
}

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-_]/', '', $string); // Removes special chars.
}

$PID=clean($_GET['paymentId']);

$ch = curl_init();
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, 'http://payum/payments/'.$PID);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close($ch);
//error_log($server_output);

$transaction = json_decode($server_output);

error_log(print_r($transaction,true));


if ($transaction->payment->status=="canceled") {
  header("Location: https://efiling.obi.gr/εφαρμογή/πληρωμή/ακύρωση");
  die();
};

if ($transaction->payment->status=="refused") {
  header("Location: https://efiling.obi.gr/εφαρμογή/πληρωμή/απόρριψη");
  die();
};

if ($transaction->payment->status=="failed") {
  header("Location: https://efiling.obi.gr/εφαρμογή/πληρωμή/απόρριψη");
  die();
};

if ($transaction->payment->status=="captured") {
    error_log("CAPTURED");
    $submissionid = $transaction->payment->details->custom5;
    $formid = $transaction->payment->details->custom4;
    $paymentid = $transaction->payment->id;
    
    $body='{"submission":{"_id":"'.$submissionid.'","form":"'.$formid.'"},"payment":{"id":"'.$paymentid.'"}}';


    if ($transaction->payment->details->custom3=="free_fee")
    {
          if (!alreadyExist($formid,$submissionid,"first")){
         postToBonita($body,$submissionFreeFee);
        }
    } else
    if ($transaction->payment->details->custom3=="submission_fee")
    {
	  if (!alreadyExist($formid,$submissionid,"first")){  
         postToBonita($body,$submissionFee);
        } 
    } else 
    if ($transaction->payment->details->custom3=="final_fee") {
         
	  if (!alreadyExist($formid,$submissionid,"final")){  	
	       postToBonita($body,$finalFee);
        }
	}

     header("Location: https://efiling.obi.gr/εφαρμογή/πληρωμή/απόρριψη");
     die();

};
