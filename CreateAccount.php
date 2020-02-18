<?php
session_start();
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    //header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

error_reporting(E_ERROR); // suppress warning messages

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$username = $_POST['username'];
$driverStatus = $_POST['driverStatus'];

?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
    <head>
    <title>CarPool | Create Account</title>
    <meta name="Author" content="am642@gre.ac.uk"/>
    <link rel="stylesheet" type="text/css" href="cw.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <style>
      body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
      .w3-row-padding img {margin-bottom: 12px}
      .w3-sidebar {width: 120px;background: #222;}
      #main {margin-left: 120px}
      table {width:115%;}
    </style>
	
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<?php
	if(!isset($_COOKIE['eucookie']))
	{ ?>
		<script type="text/javascript">
			function SetCookie(c_name,value,expiredays) {
				var exdate=new Date()
				exdate.setDate(exdate.getDate()+expiredays)
				document.cookie=c_name+ "=" +escape(value)+";path=/"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
			}
		</script>
	<?php } ?>

	
  </head>
  <body onload="init()" class="w3-black">
    <!-- Icon Bar (Sidebar - hidden on small screens) -->
    <nav class="w3-sidebar w3-bar-block w3-small w3-hide-small w3-center">
      <a href="Login.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-home w3-xxlarge"></i>
        <p>LOGIN</p>
      </a>
      <a href="CreateAccount.php" class="w3-bar-item w3-button w3-padding-large w3-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>CREATE ACCOUNT</p>
      </a>
      <a href="PostSearch.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>POST SEARCH</p>
      </a>
    </nav>

  	<div align=center>
		<?php
		if(!isset($_COOKIE['eucookie']))
		{ ?>
			<div id="eucookielaw" >
				<p>We use cookies. By browsing our site you agree to our use of cookies.</p>
				<a id="removecookie">Accept this cookie</a>
			</div>
			<script type="text/javascript">
				if( document.cookie.indexOf("eucookie") ===-1 ){
					$("#eucookielaw").show();
				}
					$("#removecookie").click(function () {
					SetCookie('eucookie','eucookie',365*10)
					$("#eucookielaw").remove();
				});
			</script>
		<?php } ?>
	</div>	

	
    <!-- Page Content -->
    <div class="w3-padding-large" id="main">

      <!-- Header -->
      <header class="w3-container w3-padding-32 w3-center w3-black" id="createAccountHeader">
        <h1 class="w3-jumbo"><span class="w3-hide-small">CarPool</h1>
        <h2>Create Account</h2>
      </header>

      <!-- Create Account Section -->
      <form action="CreateAccountdbi.php" method="post" id="createAccount" name="CreateAccount">
        <table>
          <thead>
            <tr>
              <th colspan="2"><h3>Fill in your Information</h3><br></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>First Name:</td>
              <td><input type='text' name='firstName' id='firstName1' value=<?php echo $firstName; ?>></td>
            </tr>
            <tr>
              <td>Last Name:</td>
              <td><input type='text' name='lastName' id='lastName1' value=<?php echo $lastName; ?>></td>
            </tr>
            <tr>
              <td>Email:</td>
              <td><input type='text' name='email' id='email1' value=<?php echo $email; ?>></td>
            </tr>
            <tr>
              <td>Date Of Birth:</td>
              <td><input type='date' name='dob' id='dob1' value=<?php echo $dob; ?>></td>
            </tr>
            <tr>
              <td>Username:</td>
              <td><input type='text' name='username' id='username1' value=<?php echo $username; ?>></td>
            </tr>
            <tr>
              <td>Password:</td>
              <td><input type='password' name='password' id='password1'></td>
            </tr>
            <tr>
              <td>Are you a Driver?:</td>
              <td><input type='text' name='driverStatus' id='driverStatus1' value=<?php echo $driverStatus; ?>></td>
            </tr>
            <tr>
              <td><br/><br/><br/></td>
              <td id="imgparent"><img id="img" src="captcha.php"></td>
            </tr>
            <tr>
              <td>Enter Image Text:</td>
              <td><input id="captcha1" name="captcha" type="text"></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <input type='submit' class="button" name="submitForm" value='Submit' onclick="submitForm">
                <input type='reset' class="button" name="clear" value='Reset'>
              </td>
            </tr>
          </tbody>
        </table>
        <p>Already a member? <br>Login <a href="Login.php">here</a>.</p>
      </form>
    </body>
  </html>
