<?php
	header("Progma:no-cache");
	header("Cache-Control: no-store, no-cache ,must-revalidate");
?>

<?php
    session_start();
    session_destroy();
    header("Location: ../login/login.php"); 
?>