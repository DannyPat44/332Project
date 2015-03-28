
<?php 
    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
     
    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['member'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
     
    // Everything below this point in the file is secured by the login system 
     
    // We can display the user's username to them by reading it from the session array.  Remember that because 
    // a username is user submitted content we must use htmlentities on it before displaying it to the user. 
?> 

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <title>End Reservation</title>

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

</head>

 <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="about.php">K-Town Car Share</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
		    <li><a href="private_User.php">Dashboard for <?php echo htmlentities($_SESSION['member']['Email'], ENT_QUOTES, 'UTF-8'); ?></a></li>
			<li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="private_User.php">Dashboard <span class="sr-only"></span></a></li>
            <li><a href="makeRes.php">Make a reservation</a></li>
            <li><a href="history.php">View Rental History</a></li>
			<li><a href="edit_account.php">Edit Account</a></li>
            <li><a href="about.php">About Page</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">End Reservation</h1>



<div class="col-sm-3">
	
	<form role="form" method="POST" >
	<div class="form-group">
	<label for="returnODM">Return Odometer Reading: </label>
	<input type="text"  id="returnODM" class="form-control" name="returnODM">
	</div>
	<div class="form-group">
	<label for="carComments">Comments on Car: <label/>
	<input type="text"  class="form-control"  id="carCommets" name="carComments">
	</div>
	<div class="form-group">
	<label for="KTCScomments">Comments on KTCS: <label/>
	<input type="text"  class="form-control" id="KTCScomments" name="KTCScomments">
	</div>
	<input type="submit"  name="endRes"  value="End Reservation">
	
	<?php
	$memNoRes = $db->query("Select MemberNo from member where Email = '".$_SESSION['member']['Email']."'");
	foreach ($memNoRes as $memNo1){
	$memberNo = $memNo1['MemberNo'];
	}
	
	$resNo = $_GET['resNo'];
	
	
	if(isset($_POST['endRes']))
	{
	$odm = $_POST["returnODM"];
	
	$insertQ = "INSERT INTO History(ResNo, MemberNo, VIN, ResDate, PickUpTime, PickupLocNo)
				Select ResNo, MemberNo, VIN, ResDate, PickUpTime, PickupLocNo
				FROM Reservations 
				WHERE ResNo = $resNo";
	$db->exec($insertQ);
	
	$odmQ = "UPDATE history 
	SET pickupODMReading = (SELECT ODMReading FROM car natural join (SELECT VIN FROM reservations WHERE resNo = $resNo )getVIN)
	WHERE resNo = $resNo ";

	$db->exec($odmQ);
	

	


	$resQ =  "UPDATE History
		SET ReturnTime = CURRENT_TIMESTAMP()
		WHERE ResNo = $resNo";
		
	$db->exec($resQ);
	
	
	#######
	echo $odm;
	$odmEnd = "UPDATE History
			SET ReturnODMReading = ".$odm."
			WHERE ResNo = $resNo";
	$db->exec($odmEnd);
			echo $odmEnd;
	$odmCar = "UPDATE Car
				SET ODMReading = ".$odm."
				WHERE VIN = (Select VIN From History WHERE ResNo = $resNo)";
	$db->exec($odmCar);
	
	$charge = "UPDATE History 
			SET Charge = (TIMESTAMPDIFF(SECOND, (SELECT PickUPTime FROM reservations WHERE ResNo = $resNo), (SELECT ReturnTime FROM reservations WHERE ResNo = $resNo) )/3600)*(Select rate FROM rentalfees natural join(SELECT Feeclass FROM car natural join (SELECT VIN FROM Reservations WHERE ResNo = $resNo)getVIN)getfeeclass) 
	WHERE ResNo = $resNo";
	echo $charge;
	$db->exec($charge);
	
	$db->exec("DELETE FROM Reservations WHERE ResNo = $resNo");
	
	
	
	header("Location: private_user.php");
	die("Redirecting to: private_user.php");
	}
	
	#echo "<input type=&#34;submit&#34; name=&#34;closeRes&#34;  value=&#34;Close&#34;>";
	#echo "<a href='closeRes.php?resNo=$resNo&amp;odm=".$_POST['returnODM']."'><button>Close Reservation</button>";
	
	echo "</form>";

	?>
	</div>
	  </div>
      </div>
    </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	</body>
	</html>
