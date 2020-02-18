<?php
session_start();// Starting Session
error_reporting(E_ERROR); // suppress warning messages
require '/home/am642/include/mysql.php';
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysqli_connect($host, $user, $passwd, $dbName);
// Storing Session
$user_check=$_COOKIE['login_user'];
// SQL Query To Fetch Complete Information Of User
$cookie_sql=mysqli_query($connection, "select username from Members where username='$user_check'");
$row = mysqli_fetch_assoc($cookie_sql);
$login_cookie =$row['username'];
if(!isset($login_cookie)){
	mysqli_close($connection); // Closing Connection
}
?>
