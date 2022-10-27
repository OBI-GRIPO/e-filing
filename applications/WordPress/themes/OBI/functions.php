<?php
function obi_enqueue_styles() {
    wp_register_style('bootstrap', get_template_directory_uri() . '/vendors/bootstrap3/css/bootstrap.min.css' );
    wp_register_style('bootstrap-theme', get_template_directory_uri() . '/vendors/bootstrap3/css/bootstrap-theme.min.css' );

    $dependencies = array('bootstrap');
    wp_enqueue_style( 'bootstrapstarter-style', get_stylesheet_uri(), $dependencies ); 
}

function obi_enqueue_scripts() {
    $dependencies = array('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri().'/vendors/bootstrap3/js/bootstrap.min.js', $dependencies, '3.3.6', true );
}

function obi_localize_theme() {
    load_theme_textdomain( 'obi', get_template_directory() . '/languages' );
}

function obi_wp_setup() {
    add_theme_support( 'title-tag' );
}

function obi_js() {
global $wp;
$current_url = home_url( add_query_arg( array(), $wp->request ) );

if (strpos(urldecode($current_url), 'εφαρμογή') !== false) {
$scripts = '

<!-Custom theme On all pages under url efarmogi '.$current_url.' ->
<script>var $ = jQuery.noConflict();</script>
<link type="text/css" rel="stylesheet" href="/wp-content/app/efiling/vendors/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" />
<link type="text/css" rel="stylesheet" href="/wp-content/app/efiling/vendors/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="/wp-content/app/efiling/vendors/jsgrid/1.5.3/jsgrid-theme.min.css" />
<link type="text/css" rel="stylesheet" href="/wp-content/app/efiling/vendors/formio/formio.full.min.css" />
<link type="text/css" rel="stylesheet" href="/wp-content/app/efiling/tools/fsp.css" />


<script type="text/javascript" src="/wp-content/app/efiling/vendors/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/wp-content/app/efiling/vendors/jsgrid/1.5.3/jsgrid.min.js"></script>
<script type="text/javascript" src="/wp-content/app/efiling/vendors/jsgrid/1.5.3/i18n/jsgrid-el.js"></script>
<script type="text/javascript" src="/wp-content/app/efiling/vendors/jspdf/jspdf.min.js"></script>
<script type="text/javascript" src="/wp-content/app/efiling/vendors/html2canvas/html2canvas.min.js"></script>
<script type="text/javascript" src="/wp-content/app/efiling/tools/fsp.js"></script>
<script src="/wp-content/app/efiling/jsgrid-payum.js"></script>
<script src="/wp-content/app/efiling/vendors/formio/formio.full.min.js"></script>
<script src="/wp-content/themes/OBI/vendors/flatpickr/gr.js"></script>
<script src="/wp-content/app/efiling/efiling.js"></script>
<script src="/payum/payum.js"></script>
<script>
flatpickr.localize(gr);
</script>
';
echo $scripts;
}

$globalScripts = '
<script>
jQuery(document).ready(function($){
if (localStorage.getItem("formioToken")!=null){//TODO And token not expires

$("#user-dashboard").show();

$("#user-logout").show();
$("#user-logout").click(function() {
localStorage.removeItem("formioAppUser");
localStorage.removeItem("formioUser");
localStorage.removeItem("formioToken");
document.cookie = "wp_jwtToken=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/" ;
window.location = "/";
});
$("#user-submissions").show();
$("#user-submissions").click(function() {
window.location = "/αιτήσεις/οι-αιτήσεις-μου/";
});

if (localStorage.getItem("formioUser")!=null){
var usr = JSON.parse(localStorage.getItem("formioUser"));
console.log(usr);
$("#user-email").text(usr.data.email);
}

} else
{
//MAY show login button here
}
});
</script>
';
echo $globalScripts;
}


add_action( 'wp_head', 'obi_js' );

add_action( 'after_setup_theme', 'obi_wp_setup' );
add_action( 'after_setup_theme', 'obi_localize_theme' );
add_action( 'wp_enqueue_scripts', 'obi_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'obi_enqueue_scripts' );
