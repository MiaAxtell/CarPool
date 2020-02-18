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
$username=$_COOKIE['login_user'];
require '/home/am642/include/mysql.php';
define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/EditProfile.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/editProfiledbi.php');
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
<title>CarPool | Edit Profile</title>
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
  <header class="w3-container w3-padding-32 w3-center w3-black" id="editProfiledbiHeader">
    <h1 class="w3-jumbo"><span class="w3-hide-small">CarPool</h1>
    <h2>Edit Profile</h2>
  </header>

<?php
// blimmin magic quotes should be off
if (get_magic_quotes_gpc()) {
$_POST = stripslashes_array($_POST);
}
// copy POST input to scalars
extract($_POST);
$strValid  = '';

// the Yes button commits input to the database
if ( isset($_POST['yesButton']) ) {
  if ( !($link = mysqli_connect($host, $user, $passwd, $dbName)) ) {
    echo '<p>Error connecting to database</p>';
  } else {
    $query = "UPDATE `Members` SET `DOB`='$dob',`Driver`='$driver',`Username`='$username',`Email`='$email',`FirstName`='$firstName',`LastName`='$lastName' WHERE `Member_ID`='$member_ID'";
    if ( !mysqli_query($link, $query) ) {
      echo '<p>Insert error</p>';
    } else {
      echo "<p>Thank you for editing your profile $username\n" .
      '<br /><br />Click here to ' .
      '<a href="Home.php"> return Home</p>';
    }
    mysqli_close($link);
  }
  exit('</head><body></body></html>');
}

// use hidden fields to pass state back to the input form (No/Back button)
echo '<form action="' . URLFORM . '" method="post"><p>' . "\n" .
      '<input type="hidden" name="member_ID" value="' . $member_ID . '" />' . "\n" .
      '<input type="hidden" name="dob" value="' . $dob . '" />' . "\n" .
      '<input type="hidden" name="driver" value="' . $driver . '" />' . "\n" .
      '<input type="hidden" name="verified" value="' . $verified . '" />' . "\n" .
      '<input type="hidden" name="username" value="' . $username . '" />' . "\n" .
      '<input type="hidden" name="email" value="' . $email . '" />' . "\n" .
      '<input type="hidden" name="firstName" value="' . $firstName . '" />' . "\n" .
      '<input type="hidden" name="lastName" value="' . $lastName . '" />' . "\n" .
      '<input type="hidden" name="imageRef" value="' . $imageRef . '" /></p>' . "\n" ;


// report input data back to the user
	echo '<h2>Please check your details before submitting:</h2><p>' .
	  "\n Member ID: $member_ID<br />" .
	  "\n Date of Birth: $dob<br />" .
	  "\n Driver: $driver<br />" .
	  "\n Username: $username<br />" .
	  "\n Email: $email<br />" .
	  "\n First Name: $firstName<br />" .
	  "\n Last Name: $lastName<br />" ;

	?>

	<p>Please confirm that the above details are correct<br />
		<input type="submit" value="No"/>
		<input type="submit" name="yesButton" value="Yes" onclick="this.form.action='<?php echo URLLIST ?>'"/>
	</p>


</form>
</body>
</html>
