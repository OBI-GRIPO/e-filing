<?php
	$formpath = $_GET['formpath'];
?>
<!DOCTYPE html>
<html>
  <head>
    <link  href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet' >
    <link  href='../plugins/formio/formio.full.min.css' rel='stylesheet' >
    <script src='../plugins/formio/formio.full.min.js'></script>
    <script type='text/javascript'>
      window.onload = function() {
        Formio.createForm(document.getElementById('formio'), 
        	'https://efiling.obi.gr/wp-content/app/formio.php?formpath=<?php echo $formpath; ?>');
      };
    </script>
  </head>
  <body>
    <div id='formio'></div>
  </body>
</html>