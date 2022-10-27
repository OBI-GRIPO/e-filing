<?php
/**
 * @category Authorization
 * @package Gsis
 * @author Panagiotis Skarvelis <panagiotis@skarvelis.gr>
 * @license GPLv2 or later
 */
 
/*
Plugin Name: gsislogin
Plugin URI: https://skarvelis.gr/gsislogin
Description: Custom plugin for OBI, you can use oauth 2.0 from the Greek Goverment GSIS site to authenticate and register users
Version: 1
Author: Panagiotis Skarvelis (sl45sms@gmail.com)
Author URI: https://skarvelis.gr/
License: GPLv2 or later
Text Domain: gsis
*/

/**
 * @file
 * This file is part of gsislogin plugin.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('ABSPATH') || die('No script kiddies please!');

require_once('Formio.php');
require ('config.php');

///Start session
function register_gsislogin_session()
{
    if (!session_id()) {
        session_start();
    }
}
//add_action('init', 'register_gsislogin_session'); //not use this!, brokes caching

////////Set allowed Query Vars//////////////////////////////////////////
function add_gsis_query_vars_filter($vars)
{
	$vars[] = "fiot";
    $vars[] = "error";
    $vars[] = "error_description";
    $vars[] = "code";
    $vars[] = "state";
    return $vars;
}
add_filter('query_vars', 'add_gsis_query_vars_filter');

///////////////Code to register gsis////////////////////////////////////
//Activate custom 'gsis' url
register_activation_hook(__FILE__, 'gsis_activation');
function gsis_activation()
{
    gsis_custom_output();
    flush_rewrite_rules(); // Update the permalink entries in the database
}

// If the plugin is deactivated, clean the permalink structure
register_deactivation_hook(__FILE__, 'gsis_deactivation');
function gsis_deactivation()
{
    flush_rewrite_rules();
}

// Create a new permalink entry
add_action('init', 'gsis_custom_output');
function gsis_custom_output()
{
    add_rewrite_tag('%gsis%', '([^/]+)');
    add_permastruct('gsis', '/%gsis%');
    untrailingslashit("gsis");
}

//Configurations
add_action('admin_menu', 'gsislogin_admin_add_page');
function gsislogin_admin_add_page()
{
    add_options_page('gsislogin Page', __("gsislogin settings", "gsislogin"), 'manage_options', 'gsislogin', 'gsis_options_page');
}

//Set admin options settings page
function gsis_options_page()
{
?>
<div>
<h2>gsis login options</h2>
Options relating to the gsislogin Plugin.
<form action="options.php" method="post">
<?php settings_fields('gsislogin_options'); ?>
<?php do_settings_sections('gsislogin'); ?>
 
<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form></div>
 
<?php
}
add_action('admin_init', 'gsislogin_admin_init');
function gsislogin_admin_init()
{
    register_setting('gsislogin_options', 'gsislogin_options', 'gsislogin_options_validate');
    add_settings_section('gsislogin_main', 'GSIS Oauth 2.0 user Settings', 'gsislogin_section_text', 'gsislogin');
    add_settings_field('gsislogin_appId', 'Application Id (username)', 'gsis_app_id_setting', 'gsislogin', 'gsislogin_main');
    add_settings_field('gsislogin_secret', 'Application Secret (password)', 'gsis_secret_setting', 'gsislogin', 'gsislogin_main');
    add_settings_field('gsislogin_ontest', 'Check for test.gsis.gr', 'gsis_ontest_setting', 'gsislogin', 'gsislogin_main');
}

function gsislogin_section_text()
{
    echo '<p>You can get the usename and password from gsis.gr</p>';
}

function gsis_app_id_setting()
{
    $options = get_option('gsislogin_options');
    echo "<input id='gsislogin_appId' name='gsislogin_options[gsislogin_appId]' size='40' type='text' value='{$options['gsislogin_appId']}' />";
}

function gsis_secret_setting()
{
    $options = get_option('gsislogin_options');
    echo "<input id='gsislogin_secret' name='gsislogin_options[gsislogin_secret]' size='40' type='text' value='{$options['gsislogin_secret']}' />";
}

function gsis_ontest_setting()
{
    $options = get_option('gsislogin_options');
    $html = '<input type="checkbox" id="gsislogin_ontest" name="gsislogin_options[gsislogin_ontest]" value="1"' . checked(1, array_key_exists('gsislogin_ontest', $options), false) . '/>';
    $html .= '<label for="checkbox_example">select for test enviroment</label>';
    echo $html;
}

function gsislogin_options_validate($input)
{
    return $input; //TODO validation is needed?
}

// To show admin notes
function gsis_notes_profile_fields($user)
{
    if (current_user_can('administrator')) { ?>
    <h3><?php echo __("Admin Notes For Registration", "gsislogin"); ?></h3>
    <table class="form-table">
    <tr>
        <th><label for="admin_notes"><?php echo __("Registration Notes"); ?></label></th>
        <td>
            <span id="admin_notes" ><?php echo nl2br(esc_attr(get_user_meta($user->ID, 'admin_notes', true))); ?></span><br />
        </td>
    </tr>
    </table>

<?php
    }
}
add_action('show_user_profile', 'gsis_notes_profile_fields');
add_action('edit_user_profile', 'gsis_notes_profile_fields');

////////////////////////////////////////////////////////////////////////
function decrypt_stuff($code) {
$key = "OBI33!naiT0pius.";
$iv = "fedcba9876543210";
return utf8_encode(trim(openssl_decrypt($code,"AES-128-CBC",$key,OPENSSL_ZERO_PADDING,$iv)));
}

////////////////////////////////////////////////////////////////////////
//Set defaults
function gsis_o()
{
    $options = get_option('gsislogin_options');
    $oa = array();
    $oa['secret']=$options['gsislogin_secret'];
    $oa['appId']=$options['gsislogin_appId'];
    $ontest = array_key_exists('gsislogin_ontest', $options);

    if ($ontest) {
        $oa['tokenUrl'] = 'https://test.gsis.gr/oauth2server/oauth/token';
        $oa['userinfoUrl'] = 'https://test.gsis.gr/oauth2server/userinfo';
        $oa['authorizeUrl'] = 'https://test.gsis.gr/oauth2server/oauth/authorize';
    } else {
        $oa['tokenUrl'] = 'https://www1.gsis.gr/oauth2server/oauth/token';
        $oa['userinfoUrl'] = 'https://www1.gsis.gr/oauth2server/userinfo';
        $oa['authorizeUrl'] = 'https://www1.gsis.gr/oauth2server/oauth/authorize';
    }
    
    $oa['redirectUri']= home_url("/gsis");
 
    $oa['tag'] = "_gsis_" . uniqid(mt_rand());

    return $oa;
}

function gsis_set_fio(){

	register_gsislogin_session();
    $fiot = get_query_var('fiot','unset');

    if ($fiot=='unset') wp_redirect('/');
    
    $decrypted = decrypt_stuff(urldecode($fiot));//urldecode?
    if ($decrypted=="") $decrypted=decrypt_stuff(urlencode($fiot));
    if ($decrypted=="") $decrypted=decrypt_stuff(preg_replace("/ /","+",$fiot)); //WORDPRESS Fk
    if ($decrypted=="")$decrypted=decrypt_stuff($fiot);
  
  
  
    $registerData = json_decode($decrypted);
    //error_log(print_r($registerData,true));
  
           
    if (json_last_error() === JSON_ERROR_NONE) {
     if ($registerData->form_id!=""&&$registerData->submission_id!=""){        
        //Save for later
        $_SESSION['form_id']=$registerData->form_id;
        $_SESSION['submission_id']=$registerData->submission_id;
        $_SESSION['email'] = $registerData->email;
        $_SESSION['plain']=$registerData->plain;
      } else wp_redirect('/');
      } else wp_redirect('/');

}

//Oauth step
function gsis_oauth2_step1()
{
    $o = gsis_o();
     register_gsislogin_session();
    $_SESSION['gsis_state'] = $o['tag'];
    $url = $o['authorizeUrl'] . '?client_id=' . $o['appId'] . '&redirect_uri=' . $o['redirectUri'] . '&response_type=code&scope=read&state=' . $o['tag'];
    wp_redirect($url);
    
}

/* Checks if the user already exist in submission */
function alreadyExist($checkEmail){
	global $formio_admin_email, $formio_admin_password;
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($formio_admin_email, $formio_admin_password);
    $path = 'user/exists?data.email='. $checkEmail;
    $response = $formio->get($path);
    return !!$response['body']['_id'];
}

/* Checks if the afm exist in submission */
function afmExist($checkAFM){
	global $formio_admin_email, $formio_admin_password;
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($formio_admin_email, $formio_admin_password);
    $path = 'user/register/exists?data.gsis.taxid='. $checkAFM;
    $response = $formio->get($path);
    //return false; //to disable afm check for tests
    return !!$response['body']['_id'];
}

function getSubmission($formid,$submissionid){
global $formio_admin_email, $formio_admin_password;
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($formio_admin_email, $formio_admin_password);
    $path = "form/$formid/submission/$submissionid";
    $response = $formio->get($path);
   // print_r($response);exit;
    return $response['body']['data'];
}

function updateSubmission($formid,$submissionid,$body){
	global $formio_admin_email, $formio_admin_password;
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($formio_admin_email, $formio_admin_password);
    $path = "form/$formid/submission/$submissionid";
    $response = $formio->put($path,$body);
    error_log(print_r($response,true));
    return $response['body']['data'];
}

function createUser($email,$password){
	global $formio_admin_email,$formio_admin_password;
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($formio_admin_email, $formio_admin_password);
    $body['data'] = ['email'=>$email,'password'=>$password];
    $response = $formio->post("user",$body);
    return $response['body']['data'];
}


function getformioToken($email,$password){
    $formio = new Formio('http://formio-api:3001', array());
    $user = $formio->login($email, $password);
	return ["token"=>$formio->getToken(),"user"=>$user];
}

function gsis_oauth2_step4($userDetails)
{

     //Array ( [userid] => rg1201 [taxid] => 068933130 [lastname] => ΒΑΒΟΥΛΑ [firstname] => ΕΥΤΥΧΙΑ [fathername] => ΕΜΜΑΝΟΥΗΛ [mothername] => ΑΝΝΑ [birthyear] => 1950 )
     
     register_gsislogin_session(); //WHY?
     
 if (!alreadyExist($_SESSION['email'])){
 
  if (!afmExist(trim($userDetails['taxid']))){

   	$submission=getSubmission($_SESSION['form_id'],$_SESSION['submission_id']);
	
	if ($submission!=""){
	       $submission['password']=$_SESSION['plain'];
	       
		   $submission['gsis']['userid']=trim($userDetails['userid']);
		   $submission['gsis']['taxid']=trim($userDetails['taxid']); 
		   $submission['gsis']['firstname']=trim($userDetails['firstname']);
		   $submission['gsis']['lastname']=trim($userDetails['lastname']);
		   $submission['gsis']['fathername']=trim($userDetails['fathername']);
		   $submission['gsis']['mothername']=trim($userDetails['mothername']);
		   $submission['gsis']['birthyear']=trim($userDetails['birthyear']);	
		   //$submission['gsis']['plain']=''; //REMOVE plain for protection 
		                                    //TODO may update earlyer
		   $body['data']=$submission;
		  
		   updateSubmission($_SESSION['form_id'],$_SESSION['submission_id'],$body);
	           
		   createUser($submission['email'],$_SESSION['plain']);
		   
		   $message = urlencode("You have successfully authenticate your account, use your credentials to login.");
		   wp_redirect(home_url() .'/εφαρμογή/είσοδος/?register=sucess&method=gsis&message='.$message);

           startInformMessagesProcess($_SESSION['email']);
	      
		   exit;	
	
	       } else {
		   $error = urlencode(__("ERR: 009 Unxpected error,submission not found.", "gsislogin"));
	       wp_redirect(home_url() . '/?login=failed&reason='.$error);
	       exit;
	       }


      } else {
	     $error = urlencode(__("ERR: 010 tax id already used.", "gsislogin"));
          wp_redirect(home_url() . '/?login=failed&reason='.$error);
          exit;
       }

	} else {
		$message = urlencode("You already have authenticate your account, use your credentials to login.");
		wp_redirect(home_url() .'/εφαρμογή/είσοδος/?register=fail&method=gsis&message='.$message);
		exit;
	}

}


function gsis_oauth2_step3($access_token)
{
     $o = gsis_o();

     $getuserurl = $o['userinfoUrl'] . "?format=xml&access_token=" . $access_token;
       
    $response = wp_remote_get(
        $getuserurl, array(
        'timeout'     => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'user-agent'  => 'gsislogin plugin for wordpress',
        'blocking'    => true,
        'headers'     => [
            'Accept' => 'application/xml',
            'Content-Type' => 'application/x-www-form-urlencoded;',
          ],
        'cookies'     => array(),
        'body'        => null,
        'compress'    => false,
        'decompress'  => true,
        'sslverify'   => false,
        'stream'      => false,
        'filename'    => null
        )
    );
       
     $userxml = wp_remote_retrieve_body($response);
    if ($userxml == "") {
        $error = urlencode(__("ERR: 006 Problem of entering data from the General Secretariat of Information Systems.", "gsislogin"));
        wp_redirect(home_url() . '/?login=failed&reason='.$error);
    }
  
        // In case of error gsis sends JSON !!!
        $checkerror = json_decode($userxml);
    if ($checkerror !== null) {
        $error = urlencode(__("ERR: 005 Data collection problem from the General Secretariat of Information Systems.", "gsislogin"));
        wp_redirect(home_url() . '/?login=failed&reason='.$error);
    }

        $xml = simplexml_load_string($userxml);

        $userid = $xml->userinfo['userid'][0]->__toString();
        $taxid = $xml->userinfo['taxid'][0]->__toString();
        $lastname = $xml->userinfo['lastname'][0]->__toString();
        $firstname = $xml->userinfo['firstname'][0]->__toString();
        $fathername = $xml->userinfo['fathername'][0]->__toString();
        $mothername = $xml->userinfo['mothername'][0]->__toString();
        $birthyear = $xml->userinfo['birthyear'][0]->__toString();

        $userDetails = [
          "userid" => $userid,
          "taxid" => $taxid,
          "lastname" => $lastname,
          "firstname" => $firstname,
          "fathername" => $fathername,
          "mothername" => $mothername,
          "birthyear" => $birthyear,
        ];

        
        gsis_oauth2_step4($userDetails);
        
}

function gsis_oauth2_step2()
{
    $o = gsis_o();
    $code = get_query_var('code');
    $state = get_query_var('state');
    register_gsislogin_session(); //WHY?
 
    if ($state != $_SESSION['gsis_state']) {
        $error = urlencode(__("ERR: 002 Problem in response to the systems of the General Secretariat for Information Systems.", "gsislogin"));
        wp_redirect(home_url() . '/?login=failed&reason='.$error);
    }
      
      $gettokenurl = $o['tokenUrl'];
      $data = [
          'code' => $code,
          'redirect_uri' => $o['redirectUri'],
          'client_id' => $o['appId'],
          'client_secret' => $o['secret'],
          'scope' => '',
          'grant_type' => 'authorization_code',
        ];
     
      $response = wp_remote_post(
          $gettokenurl, array(
          'method' => 'POST',
          'timeout' => 45,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking' => true,
          'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
          ],
          'body' => $data,
          'cookies' => array()
          )
      );
   
      $body = wp_remote_retrieve_body($response);
      if ($body=="") {
           $error = urlencode(__("ERR: 003 Problem in connection with the General Secretariat of Information Systems.", "gsislogin"));
            wp_redirect(home_url() . '/?login=failed&reason='.$error);
        }
   
        $rt=array();
        $rt=json_decode($body, true);
   
        if (!array_key_exists('access_token', $rt)) {
            $error = urlencode(__("ERR: 004 Problem in connection with the General Secretariat of Information Systems.", "gsislogin"));
            wp_redirect(home_url() . '/?login=failed&reason='.$error);
        }
    
    
        gsis_oauth2_step3($rt['access_token']);
}

// do the oauth 2.0 magic
add_action('template_redirect', 'gsis_display', 0);//Set priority to 0 to prevent 301 redirect
function gsis_display()
{
	    $query_var = get_query_var('gsis');
	           
	    //On our magic page
	    if ($query_var=='gsis') {
	           
       // In case of gsis error.
       if ('unset' !== get_query_var('error', 'unset')&&get_query_var('error')!="") {
           
           if ('unset' !== get_query_var('error_description', 'unset')) {

               if (get_query_var('error_description', 'unset') == "User denied access") {

                   $error = urlencode(__("You chose not to proceed to login from the systems of the Greek General Secretariat for Information Systems.", "gsislogin"));
                   wp_redirect(home_url() . '/?login=failed&reason='.$error);
                   exit;
               } else {
                   $error = urlencode(__("ERR: 001 There was a problem connecting to the systems of the General Secretariat for Information Systems.", "gsislogin"));
                   wp_redirect(home_url() . '/?login=failed&reason='.$error);
                   exit;
               }
           } else {
			       $error = urlencode(__("ERR: 006 Unknown Error.", "gsislogin"));
                   wp_redirect(home_url() . '/?login=failed&reason='.$error);

           }
       }

        if ('unset' !== get_query_var('fiot', 'unset') && get_query_var('code', 'unset')=='unset') {
          gsis_set_fio();
		}

        if (get_query_var('code', 'unset')=='unset') {
					    
               gsis_oauth2_step1();

        } else {
             gsis_oauth2_step2();
             exit; //
        }
    }
}

/* post to node for bonita inform messages process to send email*/
function startInformMessagesProcess($recipient){
    global $username,$password,$obif_email_url;
    $emailBody = '<p>Σας ευχαριστούμε για την εγγραφή σας.</p><p>Για ζητήματα που τυχόν προκύψουν παρακαλούμε επικοινωνήστε στην ηλεκτρονική διεύθυνση <a href=\"mailto:help_desk@obi.gr\">help_desk@obi.gr</a></p><hr/><span style=\"color: #666666\">ΟΡΓΑΝΙΣΜΟΣ ΒΙΟΜΗΧΑΝΙΚΗΣ ΙΔΙΟΚΤΗΣΙΑΣ (ΟΒΙ)<br/><span style=\"font-size: 12px;\"><i>Γ. Σταυρουλάκη 5, 151 25 Παράδεισος Αμαρουσίου</i></span><br/><span style=\"font-size: 12px;\"><i>Τηλ. 210 6183500 Fax: 210 6819231</i></span><br/><span style=\"font-size: 12px;\"><a style=\"color: #337ab7;\" href=\"mailto:help_desk@obi.gr\">help_desk@obi.gr</a> / <a style=\"color: #337ab7;\" href=\"www.obi.gr\">www.obi.gr</a></span><br/><span style=\"font-size: 12px;\">Follow us on:</span><br/><span style=\"font-size: 12px;\"><a style=\"color: #337ab7; text-decoration: none;\" href=\"https://www.facebook.com/pages/OBI-Hellenic-Industrial-Property-Organisation/236086996541567?fref=ts\">Facebook</a> | <a style=\"color: #337ab7; text-decoration: none;\" href=\"https://www.youtube.com/channel/UC6gcSEQ9ovZeZpulSTTy2Aw\">YouTube</a> | <a style=\"color: #337ab7; text-decoration: none;\" href=\"https://twitter.com/OBI_Hellas\">Twitter</a></span></span>';
    $body = '{"email":{"sender":"efiling@obi.gr","recipient":"'.$recipient.'","subject":"Η ΕΓΓΡΑΦΗ ΣΑΣ ΟΛΟΚΛΗΡΩΘΗΚΕ","body":"'.$emailBody.'" }}';
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));	
	curl_setopt($ch, CURLOPT_URL, $obif_email_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0); //To get only body
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
	$server_output = curl_exec($ch);
	curl_close ($ch);
	$answer = json_decode($server_output);
	if (!$answer->caseId)
	{
		error_log("Error sending email using bonita InformMessages process. ".$server_output);
    }
}