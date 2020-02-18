<?php
session_start();
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

error_reporting(E_ERROR); // suppress warning messages
$headers="";
require '/home/am642/include/mysql.php';
define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/CreateAccount.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/CreateAccountdbi.php');
$referer = $_SERVER['HTTP_REFERER'];

// if rererrer is not the form redirect the browser to the form
if ( $referer != URLFORM && $referer != URLLIST ) {
  header('Location: ' . URLFORM);
}

// function to clean up any magic quotes
function stripslashes_array($data) {
  if (is_array($data)){
    foreach ($data as $key => $value){
      $data[$key] = stripslashes_array($value);
    }
    return $data;
  }else{
    return stripslashes($data);
  }
}


?>

<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml11.dtd">
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
<body>

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


  <!-- Header -->
  <header class="w3-container w3-padding-32 w3-center w3-black" id="createAccountdbiHeader">
    <h1 class="w3-jumbo"><span class="w3-hide-small">Car Pool</h1>
    <h2>Create Account</h2>
  </header>

<?php
// blimmin magic quotes should be off
if (get_magic_quotes_gpc()) {
$_POST = stripslashes_array($_POST);
}
// copy POST input to scalars
extract($_POST);
$strValid  = '';

//limit to 5 characters
$verificationCode = mt_rand(1,99999);

//CAPTCHA Matching code
$captchaBoolean="0";
if ($_SESSION["code"] == $_POST["captcha"]) {
    $captchaBoolean="1";
} else {
  $captchaBoolean="0";
}

$hashed_pword = password_hash($password, PASSWORD_DEFAULT);

$subject = "CarPool Verification Email";
$message = "Hi $firstName $lastName, thanks for signing up to CarPool. To verify your account please enter your username, password and verification code on the verification page: $verificationCode";
$headers .= "From: am642@greenwich.ac.uk";

$connect = mysqli_connect($host, $user, $passwd, $dbName);
$duplicate_username = mysqli_query($connect, "SELECT 1 FROM Members WHERE Username = '$username'");

// the Yes button commits input to the database
if ( isset($_POST['yesButton']) ) {
  if ( !($link = mysqli_connect($host, $user, $passwd, $dbName)) ) {
    echo '<p>Error connecting to database</p>';
  } else {
    $query = "INSERT INTO Members (FirstName, LastName, Email, DOB, Username, Password, Driver, VerificationCode)" .
            "VALUES ('$firstName', '$lastName', '$email', '$dob', '$username', '$hashed_pword', '$driverStatus', '$verificationCode')";
    if ( !mysqli_query($link, $query) ) {
      echo '<p>Insert error</p>';
    } else {
      echo "<p>Thank you for completing the registration form $firstName $lastName\n" .
        '<br /><br />Your account will remain inactive until it has been verifed ' .
        '<br /><br />To do so, please ' .
        '<a href="VerifyAccount.php"> click here</p>';
      mail($email, $subject, $message, $headers);
    }
    mysqli_close($link);
  }
  exit('</head><body></body></html>');
}

// use hidden fields to pass state back to the input form (No/Back button)
echo '<form action="' . URLFORM . '" method="post"><p>' . "\n" .
      '<input type="hidden" name="firstName" value="' . $firstName . '" />' . "\n" .
      '<input type="hidden" name="lastName" value="' . $lastName . '" />' . "\n" .
      '<input type="hidden" name="email" value="' . $email . '" />' . "\n" .
      '<input type="hidden" name="dob" value="' . $dob . '" />' . "\n" .
      '<input type="hidden" name="username" value="' . $username . '" />' . "\n" .
      '<input type="hidden" name="driverStatus" value="' . $driverStatus . '" /></p>' . "\n";

	// validate input from the form
	if ($duplicate_username->num_rows > 0)
	  $strValid .= "  Username already exists<br />\n";
	if ( preg_match('/[^a-zA-Z ]|^$/',$firstName) )
	  $strValid .= "  FirstName<br />\n";
	if ( preg_match('/[^a-zA-Z ]|^$/',$lastName) )
	  $strValid .= "  LastName<br />\n";
	if ( !preg_match('/^([\w\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$email) )
	  $strValid .= "  Email<br />\n";
	if ( $dob == "")
	  $strValid .= " Date of Birth<br />\n";
	if ( $username == "")
	  $strValid .= " Username<br />\n";
	if ( $password == "")
	  $strValid .= " Password<br />\n";
	if ( $driverStatus == "")
	  $strValid .= " Driver Status<br />\n";
	if ( $captchaBoolean == 0)
	  $strValid .= " CAPTCHA<br />\n";

	// report input data back to the user
	echo '<h2>Please check your details before submitting:</h2><p>' .
	  "\n First Name: $firstName<br />" .
	  "\n Last Name: $lastName<br />" .
	  "\n Email: $email<br />" .
	  "\n Date of Birth: $dob<br />" .
	  "\n Username: $username<br />" .
	  "\n Driver Status: $driverStatus<br />";

	// if there are validation failures
	// report them to the user an offer a 'Back' button
	if ( $strValid ) {
	?>

		<p>The following details have not been entered correctly:<br /><br />
			<?php echo $strValid ?>
			<br /><br />Please go back and correct them<br /><br />
			<input type="submit" value="Back"/>
		</p>

	<?php
	} else {
	// otherwise ask for input confirmation
	?>

		<p>Please confirm that the above details are correct<br />
			<input type="submit" value="No"/>
			<input type="submit" name="yesButton" value="Yes" onclick="this.form.action='<?php echo URLLIST ?>'"/>
		</p>

	<?php } ?>

</form>
</body>
</html>
