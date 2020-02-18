<?php
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}
error_reporting(E_ERROR); // suppress warning messages
include('session.php');

  require '/home/am642/include/mysql.php';
  define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/VerifyAccount.php');
  define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/VerifyAccountdbi.php');
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
    } else {
      return stripslashes($data);
    }
  }
?>

<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
  <head>
    <title>Car Pool | Verify Account</title>
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
      <h2>Verify Account</h2>
    </header>

    <?php
      // blimmin magic quotes should be off
      if (get_magic_quotes_gpc()) {
        $_POST = stripslashes_array($_POST);
      }
      // copy POST input to scalars
      extract($_POST);
      $strValid  = '';

      $connect = mysqli_connect($host, $user, $passwd, $dbName);
      $verify =mysqli_query($connect, "SELECT 1 FROM Members WHERE VerificationCode = '$receivedCode'");

      if ( !($link = mysqli_connect($host, $user, $passwd, $dbName)) ) {
        echo '<p>Error connecting to database</p>';
      } else {
        if ($verify->num_rows > 0) {
          $query = "UPDATE Members SET Verified = 1 WHERE VerificationCode = '$receivedCode'";
          if ( !mysqli_query($link, $query) ) {
            echo '<p>Insert error</p>';
          } else {
            echo "<p>Thank you for verifying your account\n" .
              '<br /><br />Your account has now been activated ' .
              '<br /><br />Please click here to ' .
              '<a href="Login.php"> Login</p>';
          }
        } else {
          echo '<br /><br />Account cannot be verified, please check your details and try again';
        }
        mysqli_close($link);
      }
    ?>
    </form>
  </body>
</html>
