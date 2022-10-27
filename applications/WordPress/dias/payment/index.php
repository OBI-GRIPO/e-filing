<?php
require 'config.php';
require_once('Formio.php');


//Use nginx to check if post come from
//194.30.224.10 live
//194.30.224.19

//Init
$FORMID=$SUBMISSIONID="";
$RID=$ENV=$RF=$PAYTYPE=$CASEID="";


function postPayment($JSON,$endpoint) {

    global $username,$password,$obifurl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0); //To get only body
        curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        $answer = json_decode($server_output);
        error_log(print_r($answer,true));

}

/* post to node for bonita start*/
function getFormAndSubmissionIdFromBonita($CASEID) {

        global $username,$password,$obifurl,$FORMID,$SUBMISSIONID;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_URL, $obifurl.$CASEID);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0); //To get only body
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        $variables = json_decode($server_output);
      
        foreach ($variables as &$variable) {
         if ($variable->name=="formid") $FORMID=$variable->value;
         if ($variable->name=="submissionid") $SUBMISSIONID=$variable->value;
        }
}

//P se production
function accepted($ENV,$RID) {
$RO = '{"env":"'.$ENV.'","msgType":"DPG_ONResponse","incomingPaymentsStatus":[{"status":"ACCEPTED","rejectionCode":"","RID":"'.$RID.'"}]}';
header('Content-Type: application/json');
http_response_code (200);
print $RO;
}

function acceptdgon($ENV) {
$RO = '{"env":"'.$ENV.'","msgType":"DPG_ON"}';
header('Content-Type: application/json');
http_response_code (200);
print $RO;
}

function rejected($ENV,$RID,$REJECTION){
$RO = '{"env":"'.$ENV.'","msgType":"DPG_ONResponse","incomingPaymentsStatus":[{"status":"REJECTED","rejectionCode":"'.$REJECTION.'","RID":"'.$RID.'"}]}';
header('Content-Type: application/json');
http_response_code (200);
print $RO;
}

function extractDiasVars($obj,$index=0){
global $RID,$ENV,$RF,$PAYTYPE,$CASEID;

$RID=$obj->incomingPayments[$index]->RID;
$ENV=$obj->env;
$RF=$obj->incomingPayments[$index]->RI_MID;

$PAYTYPE=$RF[10];//Se euto to psifio mpenei o typos pliromhs
$CASEID=(string)intval(substr($RF,11));

//Just in case
if ($_SERVER['HTTP_USER_AGENT']!=="DIAS dsPlatform") {
         error_log("Wrong User Angent ".$_SERVER['HTTP_USER_AGENT']);
         rejected($ENV,$RID,"MS03");
         die();
       }
//Just in case 
if ("$PAYTYPE"!="1" && "$PAYTYPE"!="2" && "$PAYTYPE"!="3") {
    error_log("Reject payment $RF because i do not understund payment type");
    rejected($ENV,$RID,"MS03");
   die();
}

}

function startPaymentProcess($FORMID,$SUBMISSIONID,$PAYTYPE,$RF,$ENV,$RID,$reject=true){
global $formio_admin_email,$formio_admin_password,$firstpayurl,$lastpayurl;

//Login user
$formio = new Formio('http://formio-api:3001', array());
$user = $formio->login($formio_admin_email, $formio_admin_password);


$sub = $formio->get("form/".$FORMID."/submission/".$SUBMISSIONID);


if ("$PAYTYPE"=="1" && $sub['body']['data']['first_payment_id']!="") {
                    error_log("Reject $RF because we have already a first payment id ");
                    if($reject){
                     rejected($ENV,$RID,"AM05");
                     die();
                    }
                  }
if ("$PAYTYPE"=="2" && $sub['body']['data']['final_payment_id']!="") {
                    error_log("Reject $RF because we have already a second payment id ");
                   if($reject){
                    rejected($ENV,$RID,"AM05");
                    die();
                   }
                 }
// klisi tou analogou endpoint sta obif
$JSON='{"submission":{"_id":"'.$SUBMISSIONID.'","form":"'.$FORMID.'"},"payment":{"id":"'.$RF.'"}}';
if ("$PAYTYPE"=="1") {
  //send/payment
  error_log("Start first payment for form: $FORMID with submission: $SUBMISSIONID  using RF: $RF");
  postPayment($JSON,$firstpayurl);
} else {
 error_log("Start second payment for form: $FORMID with submission: $SUBMISSIONID  using RF: $RF");
 //send/finalpayment
 postPayment($JSON,$lastpayurl);
}


}

//Get the body
$json = file_get_contents('php://input'); 
$obj = json_decode($json);

error_log(print_r($obj,true));


if ($obj->msgType=="DPG_ONRequest"){
//isos na kano kai ena mod97 validation

//To proto apo ta psifia einai o typos tis pliromis first_payment last_payment free_payment klp, opote ta ypolipa 14 einai to case_id
//apo to case_id me kapio tropo vrisko to form_id kai to subbmision_id kai plirono sto bonita analoga me ton typo pliromis

extractDiasVars($obj); //$RID,$ENV,$RF,$PAYTYPE,$CASEID;

if ($PAYTYPE=="1" || $PAYTYPE=="2") {
//Get form variables apo to case ID
getFormAndSubmissionIdFromBonita($CASEID);

if ($FORMID!=""&&$SUBMISSIONID!="") {
    startPaymentProcess($FORMID,$SUBMISSIONID,$PAYTYPE,$RF,$ENV,$RID);
} else {
     error_log("Reject payment $RF because we dont find sumbission");
     rejected($ENV,$RID,"MS03");
     die();
  }
  // Enimerosi tou dias oti ola kala
  accepted($ENV,$RID);
 } else {
   if ("$PAYTYPE"=="3"){
    // Type 3 is a free payment.... Just ignore or send email
   error_log("Accept free payment using RF: $RF");
   accepted($ENV,$RID);
   die();
   } else {
    error_log("Reject payment $RF because have an wrong paytype");
    rejected($ENV,$RID,"MS03");
    die();
   } 
 }
} else
if  ($obj->msgType=="DPG_ON") {

acceptdgon($obj->env);

foreach ($obj->incomingPayments as $key => $payment) {

extractDiasVars($obj,$key); //$RID,$ENV,$RF,$PAYTYPE,$CASEID;
 
if ($PAYTYPE=="1" || $PAYTYPE=="2") {
  getFormAndSubmissionIdFromBonita($CASEID);
if ($FORMID!=""&&$SUBMISSIONID!="") {
    startPaymentProcess($FORMID,$SUBMISSIONID,$PAYTYPE,$RF,$ENV,$RID,false);
} else {
        error_log("Reject payment $RF because we dont find sumbission");
      }
}

}

 //oti epano alla me loupa kai xoris na stelno piso accepted h rejected. Diladi an exei idi plirothei to agnoo alios to plirono.
};
