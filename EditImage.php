<?php
session_start();
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

error_reporting(E_ERROR); // suppress warning messages
include('session.php');
require '/home/am642/include/mysql.php';
if ($_COOKIE['login_boolean']!= "1"){
  header("location: Unauthorised.php");
} else if ($_COOKIE['verified_boolean']!= "1"){
    header("location: VerifyAccount.php");
}
$imageid = $_POST['SelectPost'];

$image_ID = $_POST['$image_ID'];
?>

<script>
    function submitForm(action)
    {
        document.getElementById('editImage').action = action;
        document.getElementById('editImage').submit();
    }
</script>

<?php "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
  <head>
    <title>CarPool | Edit Image</title>
    <meta name="Author" content="am642@gre.ac.uk"/>
    <link rel="stylesheet" type="text/css" href="cw.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <style>
      body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
      .w3-row-padding img {margin-bottom: 12px}
      /* Set the width of the sidebar to 120px */
      .w3-sidebar {width: 120px;background: #222;}
      /* Add a left margin to the "page content" that matches the width of the sidebar (120px) */
      #main {margin-left: 120px}
      /* Remove margins from "page content" on small screens
      @media only screen and (max-width: 600px) {#main {margin-left: 0}} */
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
  <body class="w3-black">
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
    <a href="AddPost.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
      <i class="fa fa-eye w3-xxlarge"></i>
      <p>NEW POST</p>
    </a>
    <a href="MyPosts.php" class="w3-bar-item w3-button w3-padding-large w3-black">
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

    <!-- Header/Home -->
    <header class="w3-container w3-padding-32 w3-center w3-black" id="editPosts">
      <h1 class="w3-jumbo"><span class="w3-hide-small">CarPool</h1>
      <h2>Edit Image Details</h2>
	  <h3><b id="welcome">Welcome : <i><?php echo $login_cookie; ?></i></b></h3>
    </header>


    <!-- Posts Section -->
    <table>
      <form method="post" id="editImage" name="EditImage">
        <thead>
          <tr>
            <th colspan="4"><h3>Edit your Image Information</h3><br></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $connect = mysqli_connect($host, $user, $passwd, $dbName);
          $result = mysqli_query($connect, "SELECT * FROM Images WHERE Image_ID = '$imageid' OR Image_ID = 'image_ID'");
          while($row = $result->fetch_assoc()) {
          ?>
          <tr>
            <td>Image ID:</td>
            <td><input type='text' name='image_ID' value='<?php echo $row['Image_ID'] ?>' readonly</td>
          </tr>
          <tr>
            <td>Journey ID:</td>
            <td><input type='text' name='journey_ID' value='<?php echo $row['Journey_ID'] ?>' </td>
          </tr>
          <tr>
            <td>Image Type:</td>
            <td><input type='text' name='image_type' value='<?php echo $row['Image_Type'] ?>' </td>
          </tr>
          <tr>
            <td>Image Name:</td>
            <td><input type='text' name='image_name' value='<?php echo $row['Image_Name'] ?>' readonly</td>
          </tr>
          <tr>
            <td>Image Description:</td>
            <td><input type='text' name='image_alt' value='<?php echo $row['Image_alt'] ?>' </td>
          </tr>
          <tr>
            <td>Image:</td>
            <td><input type='image' name='img' value='<?php echo $row['img'] ?>' </td>
          </tr>
              <?php } ?>
          <tr>
            <td>
              <input type='submit' class="button" name="editImage" onclick="submitForm('imageEditdbi.php')" value='Save Changes'>
              <input type='submit' class="button" name="deleteImage" onclick="submitForm('imageDeletedbi.php')" value='Delete Image'>
            </td>
          </tr>
          </tbody>
        </form>
      </table>
      <hr />
	   </div>
   </div>
  </body>
</html>
