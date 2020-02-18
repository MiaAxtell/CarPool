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
$driverID="";
$passengerID="";
$username=$_COOKIE['login_user'];
require '/home/am642/include/mysql.php';
define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/AddPost.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/addPostdbi.php');
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
<title>CarPool | New Post</title>
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
  <header class="w3-container w3-padding-32 w3-center w3-black" id="addPostdbiHeader">
    <h1 class="w3-jumbo"><span class="w3-hide-small">CarPool</h1>
    <h2>New Post</h2>
  </header>
  

<?php
// blimmin magic quotes should be off
if (get_magic_quotes_gpc()) {
$_POST = stripslashes_array($_POST);
}
// copy POST input to scalars
extract($_POST);
$strValid  = '';

// $plats is a space delimited string
if ( isset($weekDays) ) {
   $days = '';
   if ( is_array($weekDays) ) {
      foreach ($weekDays as $value) { $days .= "$value "; }
   } else {
      $days = $weekDays;
   }
}

if ($liftStatus == "Providing"){
  $driverID = $username;
  $passengerID = "";
} else if ($liftStatus == "Obtaining"){
  $passengerID = $username;
  $driverID = "";
}

// the Yes button commits input to the database
if ( isset($_POST['yesButton']) ) {
  if ( !($link = mysqli_connect($host, $user, $passwd, $dbName)) ) {
    echo '<p>Error connecting to database</p>';
  } else {
    $query = "INSERT INTO Journeys (Start, Destination, Organiser_ID, Driver_ID, PassengerID, Date_Time, Days)" .
            "VALUES ('$startingPoint', '$destination', '$username', '$driverID', '$passengerID', '$travelTimes', '$days')";
    if ( !mysqli_query($link, $query) ) {
      echo '<p>Insert error</p>';
    } else {
      echo "<p>Thank you for adding a new journey $username\n" .
      '<br /><br />Click here to ' .
      '<a href="Home.php"> return Home</p>';
    }
    mysqli_close($link);
  }
  exit('</head><body></body></html>');
}

// use hidden fields to pass state back to the input form (No/Back button)
echo '<form action="' . URLFORM . '" method="post"><p>' . "\n" .
      '<input type="hidden" name="startingPoint" value="' . $startingPoint . '" />' . "\n" .
      '<input type="hidden" name="destination" value="' . $destination . '" />' . "\n" .
      '<input type="hidden" name="travelTimes" value="' . $travelTimes . '" />' . "\n" .
      '<input type="hidden" name="days" value="' . $days . '" /></p>' . "\n" .
      '<input type="hidden" name="liftStatus" value="' . $liftStatus . '" /></p>' . "\n";


	// validate input from the form
	if ($startingPoint == "")
	  $strValid .= "  StartingPoint<br />\n";
	if ($destination == "")
	  $strValid .= "  Destination<br />\n";
	if ($username == "")
	  $strValid .= "  Username<br />\n";
	if ($travelTimes == "")
	  $strValid .= "  DepartureTime<br />\n";
	if ($days == "")
	  $strValid .= "  Days<br />\n";

	// report input data back to the user
	$dayString = preg_replace('/ /',"<br />\n     ",$days);
	echo '<h2>Please check your details before submitting:</h2><p>' .
	  "\n Starting Point: $startingPoint<br />" .
	  "\n Destination: $destination<br />" .
	  "\n Username: $username<br />" .
	  "\n Departure Time: $travelTimes<br />" .
	  "\n Days:<br />     $days<br />" .
	  "\n Lift Status:<br />     $liftStatus<br />" ;

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
