<?php
  session_start();
  if( isset( $_SESSION[ 'identifier' ] ) ) {
    $jb_login = TRUE;    
    $username = $_SESSION['name'];
    $identifier = $_SESSION['identifier'];
  }
?>