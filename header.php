<?php
// Only starts a session if there isn't already an active session
if(session_status() != PHP_SESSION_ACTIVE){
  session_start();
}
// Function that can be used to redirect user using Javascript rather than manipulating the header
// Changes header location is not possible after outputting anything so javascript needs to be used
function redirect($url){ ?>
  <script type='text/javascript'>
  window.location.href = '<?php echo $url; ?>';
  </script>
<?php } ?>

<!DOCTYPE html>
<html>
<!-- Header for COMP3391 - Ben Rose -->
<head>
  <!-- Meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Twitter Bootstrap from https://getbootstrap.com/ -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- Link stylesheet -->
  <link rel="stylesheet" href="css/main.css">
  <title>Film Rental | <?php echo $header ?></title>
</head>
<body>
