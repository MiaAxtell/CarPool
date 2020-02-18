<?php
session_start();
if(session_destroy()) { // Destroying All Sessions
	setcookie('login_boolean','',time()-3600);
	setcookie('verified_boolean','',time()-3600);
	setcookie('login_user','',time()-3600);
	header("Location: Goodbye.php"); // Redirecting To Home Page
}
?>
