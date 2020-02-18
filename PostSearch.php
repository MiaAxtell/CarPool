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
?>
<?php "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
  <head>
    <title>CarPool | Post Search</title>
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
    <a href="Login.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
      <i class="fa fa-home w3-xxlarge"></i>
      <p>LOGIN</p>
    </a>
    <a href="CreateAccount.php" class="w3-bar-item w3-button w3-padding-large w3-hover-black">
      <i class="fa fa-eye w3-xxlarge"></i>
      <p>CREATE ACCOUNT</p>
    </a>
    <a href="PostSearch.php" class="w3-bar-item w3-button w3-padding-large w3-black">
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

    <!-- Header/Home -->
    <header class="w3-container w3-padding-32 w3-center w3-black" id="home">
      <h1 class="w3-jumbo"><span class="w3-hide-small">Car Pool</h1>
      <h2>Post Search</h2>
	  <h3><b id="welcome">Welcome : <i><?php echo $login_cookie; ?></i></b></h3>
    </header>

    <div class="container w3-content w3-justify w3-text-grey w3-padding-64" id="posts">
      <h2 class="w3-text-light-grey">Search Posts</h2>
      <hr style="width:200px" class="w3-opacity">
        <table>
          <form action="PostSearch.php" method=post>
            <tr>
              <td>Starting Point:</td>
              <td>Destination Point:</td>
              <td>Time:</td>
              <td>Weekday:</td>
            </tr>
            <tr>
              <td width='5' align='left'>
                <input type='text' name='startingPoint' size='10'>
              </td>
              <td width='10' align='left'>
                <input type='text' name='desitinationPoint' size='15'>
              </td>
              <td width='5' align='left'>
                <input type='time' name='time'>
              </td>
              <td>
                <label for='Monday'>Monday</label>
                <input type='checkbox' id='Monday' name='weekDays[]' value='Monday'>
                <label for='Tuesday'>Tuesday</label>
                <input type='checkbox' id='Tuesday' name='weekDays[]' value='Tuesday'>
                <label for='Wednesday'>Wednesday</label>
                <input type='checkbox' id='Wednesday' name='weekDays[]' value='Wednesday'>
                <label for='Thursday'>Thursday</label>
                <input type='checkbox' id='Thursday' name='weekDays[]' value='Thursday'>
                <label for='Friday'>Friday</label>
                <input type='checkbox' id='Friday' name='weekDays[]' value='Friday'>
              </td>
              <td>
                <input type='submit' id="search" value='Search'>
              </td>
            </tr>
          </form>
        </table>

        <?php
			$query="";
			$start=$_POST["startingPoint"];
			$end=$_POST["destinationPoint"];
			$time=$_POST["time"];

			if ( isset($weekDays) ) {
			   $days = '';
			   if ( is_array($weekDays) ) {
				  foreach ($weekDays as $value) { $days .= "$value "; }
			   } else {
				  $days = $weekDays;
			   }
			}

			$query="SELECT * FROM Journeys";
			if (isset($start)) {
				$query .= " WHERE Start like '%$start%'";
			}
			if (isset($end)) {
				$query .= " AND Destination LIKE '%$end%'";
			}
			if (isset($time)) {
				$query .= " AND Date_Time LIKE '%$time%'";
			}
			if (isset($days)) {
				$query .= " AND Days LIKE '%$days%'";
			}
        ?>

    <!-- Posts Section -->
      <h2 class="w3-text-light-grey">Results</h2>
        <table>
          <form action="ViewPost.php" method="post" id="viewPost" name="ViewPost">
            <tbody>
              <tr>
                <td><b><font color="white">Journey ID</font></b></td>
                <td><b><font color="white">Starting Point</font></b></td>
                <td><b><font color="white">Destination</font></b></td>
                <td><b><font color="white">Organiser</font></b></td>
                <td><b><font color="white">Driver</font></b></td>
                <td><b><font color="white">Passenger</font></b></td>
                <td><b><font color="white">Time</font></b></td>
                <td><b><font color="white">Days</font></b></td>
                <td><b><font color="white">Select</font></b></td>
              </tr>
              <?php
				  $con=mysqli_connect($host, $user, $passwd, $dbName);
				  if ( !($link = mysqli_connect($host, $user, $passwd, $dbName)) ) {
					echo '<p>Error connecting to database</p>';
					} else {
						  $res=$con->query($query);
						  while($row=$res->fetch_assoc()){
              ?>
							  <tr>
								<td><?php echo $row['Journey_ID'] ?></td>
								<td><?php echo $row['Start'] ?></td>
								<td><?php echo $row['Destination'] ?></td>
								<td><?php echo $row['Organiser_ID'] ?></td>
								<td><?php echo $row['Driver_ID'] ?></td>
								<td><?php echo $row['PassengerID'] ?></td>
								<td><?php echo $row['Date_Time'] ?></td>
								<td><?php echo $row['Days'] ?></td>
								<td><input type='radio' id="selectPost" name='SelectPost' value='<?php echo $row['Journey_ID']?>'></td>
							  </tr>
						  <?php }
					} ?>
                <tr>
                  <td colspan="7">
                    <input type='submit' class="button" name="viewPost" value='View Post' onclick="viewPost">
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
