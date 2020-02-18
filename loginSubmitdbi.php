<?php
session_start(); // Starting Session
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

  error_reporting(E_ERROR); // suppress warning messages
  ob_start();
  require '/home/am642/include/mysql.php';
  define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/Login.php');
  define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~am642/CarPool/loginSubmitdbi.php');
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
    <title>CarPool | Login</title>
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
    <header class="w3-container w3-padding-32 w3-center w3-black" id="loginSubmitdbiHeader">
      <h1 class="w3-jumbo"><span class="w3-hide-small">Car Pool</h1>
      <h2>Login</h2>
    </header>

    <?php
		// blimmin magic quotes should be off
		if (get_magic_quotes_gpc()) {
		$_POST = stripslashes_array($_POST);
		}
		// copy POST input to scalars
		extract($_POST);
		$strValid  = '';

    $_1 = '1';
    $_0 = '0';

    setcookie('login_boolean','',time()-3600);
    setcookie('verified_boolean','',time()-3600);
    setcookie('login_user','',time()-3600);

		$connect = mysqli_connect($host, $user, $passwd, $dbName);
    $DBhashed_pword = mysqli_query($connect,"SELECT Password FROM Members WHERE Username = '$username'");
		$verification = mysqli_query($connect,"SELECT Verified FROM Members WHERE Username = '$username'");

    while($row = $DBhashed_pword->fetch_assoc()) {
      $hashed_pword = $row['Password'];
      if (password_verify($password, $hashed_pword)) {
        $result = mysqli_query($connect, "SELECT 1 FROM Members WHERE Username = '$username' AND Password = '$hashed_pword'");
        while($row = $verification->fetch_assoc()) {
          $userVerified = $row['Verified'];
      			if (empty($_POST['username']) || empty($_POST['password'])) {
      				echo "<p>Username or Password is invalid</p>";
      			} else {
      				if ( !($link = mysqli_connect($host, $user, $passwd, $dbName)) ) {
      					echo '<p>Error connecting to database</p>';
      				} else {
                if ($userVerified == "0") {
                  setcookie('login_boolean',$_1);
                  setcookie('verified_boolean',$_0);
                  echo "<p>Account Unverified\n".
                  '<br /><br />Please click here to '.
                  '<a href="VerifyAccount.php"> Verify</p>';
                } else {
        					// Define $username and $password
        					$username=$_POST['username'];
        					$dbpassword=$_POST['password'];
        					// To protect MySQL injection for Security purpose
        					$username = stripslashes($username);
        					$dbpassword = stripslashes($password);
        					$username = mysqli_real_escape_string($link, $username);
        					$dbpassword = mysqli_real_escape_string($link, $password);
        					// SQL query to fetch information of registerd users and finds user match.
        					if ($result->num_rows > 0) {
                    setcookie('login_user',$username);
                    setcookie('login_boolean',$_1);
                    setcookie('verified_boolean',$_1);
        						echo "<p>Login Successful\n".
        						'<br /><br />Please click here to '.
        						'<a href="Home.php"> return to Home';
        					} else {
        						echo '<br /><br />Login 1 Unsuccessful, please check your details and try again';
        					}
        					mysqli_close($link);
                }
      				}
      			}
          }
        } else {
          echo "<p>Username or Password is Invalid\n".
          '<br /><br />Please click here to '.
          '<a href="Login.php"> try again';
        }
      }
    ?>
    </form>
  </body>
</html>
