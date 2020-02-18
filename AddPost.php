<?php
session_start();
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

error_reporting(E_ERROR); // suppress warning messages
if ($_COOKIE['login_boolean']!= "1"){
  header("location: Unauthorised.php");
} else if ($_COOKIE['verified_boolean']!= "1"){
    header("location: VerifyAccount.php");
}

$liftStatus = "";

$startingPoint = $_POST['startingPoint'];
$destination = $_POST['destination'];
$username = $_POST['username'];
$travelTimes = $_POST['travelTimes'];

?>
<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "DTD/xhtml11.dtd">
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
  <body onload="init()" class="w3-black">

    <!-- Icon Bar (Sidebar - hidden on small screens) -->
    <nav class="w3-sidebar w3-bar-block w3-small w3-hide-small w3-center">
      <a href="Home.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-home w3-xxlarge"></i>
        <p>HOME</p>
      </a>
      <a href="Home.php#allPosts" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>POSTS</p>
      </a>
      <a href="#" class="w3-bar-item w3-button w3-padding-large w3-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>NEW POST</p>
      </a>
      <a href="MyPosts.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>MY POSTS</p>
      </a>
      <a href="Profile.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>MY PROFILE</p>
      </a>
      <a href="PostSearch.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>POST SEARCH</p>
      </a>
      <a href="logout.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
        <i class="fa fa-eye w3-xxlarge"></i>
        <p>LOGOUT</p>
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
        <h2>New Post</h2>
      </header>

      <!-- Create Account Section -->
      <form action="addPostdbi.php" method="post" id="addPost" name="AddPost">
        <table>
          <thead>
            <tr>
              <th colspan="4"><h3>Fill in your Journey Information</h3><br></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Starting Point:</td>
              <td><input type='text' name='startingPoint' id='startingPoint1' value=<?php echo $startingPoint; ?>></td>
            </tr>
            <tr>
              <td>Destination:</td>
              <td><input type='text' name='destination' id='destination1' value=<?php echo $destination; ?>></td>
            </tr>
            <tr>
              <td>Departure Time:</td>
              <td><input type='time' name='travelTimes' id='travelTimesl1' value=<?php echo $travelTimes; ?>></td>
            </tr>
            <tr>
              <td>Days:</td>
              <td>
                <p>
					<input type='checkbox' id="Monday" name='weekDays[]' value="Monday">
					<label for="Monday">Monday</label>
				</p>
                <p>
					<input type='checkbox' id="Tuesday" name='weekDays[]' value="Tuesday">
					<label for="Tuesday">Tuesday</label>
				</p>
	            <p>
					<input type='checkbox' id="Wednesday" name='weekDays[]' value="Wednesday">
					<label for="Wednesday">Wednesday</label>
				</p>
                <p>
					<input type='checkbox' id="Thursday" name='weekDays[]' value="Thursday">
					<label for="Thursday">Thursday</label>
				</p>
                <p>
					<input type='checkbox' id="Friday" name='weekDays[]' value="Friday">
					<label for="Friday">Friday</label>
				</p>
              </td>
            </tr>
            <tr>
              <td>Are you Providing or Obtaining a lift?:</td>
              <td>
                <p>
					<input type='radio' id="Providing" name='liftStatus' value="Providing">
					<label for="Providing">Providing</label>
				</p>
                <p>
					<input type='radio' id="Obtaining" name='liftStatus' value="Obtaining">
					<label for="Obtaining">Obtaining</label>
				</p>
              </td>
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
      </form>
    </body>
  </html>
