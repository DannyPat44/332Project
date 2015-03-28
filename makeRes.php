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

<title>Make a Reservation</title>
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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link href="dashboard.css" rel="stylesheet">
  
  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  $(function() {
    $( "#datepicker2" ).datepicker();
  });
  </script>
	

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
            <li><a href="private_User.php">Dashboard</a></li>
            <li class="active"><a href="makeRes.php">Make a reservation<span class="sr-only">(current)</span></a></li>
            <li><a href="history.php">View Rental History</a></li>
			<li><a href="edit_account.php">Edit Account</a></li>
            <li><a href="about.php">About Page</a></li>
          </ul>
        </div>
		
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Make a Reservations</h1>

	
	<form method="POST">
	<p>
	Rental Pick Up Date:
	<input type="text" id=datepicker name="pickupDate">
	Rental Return Date:
	<input type="text" id=datepicker2 name="returnDate">
	Location:
	<!-- <select name="location"> -->
	
	<?php
	#$dbh = new PDO('mysql:host=localhost;dbname=KTCS', "root", "");
	$memNoRes = $db->query("Select MemberNo from member where Email = '".$_SESSION['member']['Email']."'");
	foreach ($memNoRes as $memNo1){
	$memNo = $memNo1['MemberNo'];
	}
	#echo $memNo;
	$rows = $db->query("SELECT locName FROM Locations");
	#$con = mysql_connect("localhost","root","") or die ("Could not connect to mysql");
	#mysql_select_db("$KTCS") or die ("no database");
	
	#$query = "SELECT locName FROM Locations";
	#$result = mysql_query($query);
	echo "<select name=location  value='Choose'>Dropdown</option>";
	
	foreach ($rows as $row) {
		echo "<option value='$row[locName]'</option>";
		echo "<p>"."$row[locName]"."</p>";
	}
	
	echo "</select>";
	?>
	</p>
	Time(24-hour format):
	<input type="text" name="pickupTime" value = "08:00:00">
	Time(24-hour format):
	<input type="text" name="returnTime" value = "08:00:00">
	<input type="submit" name="findCars"  value="Find Available Cars">
	<h2 class="sub-header">Available Cars</h2>
	<div class="table-responsive">
            <table class="table table-striped">
              <thead>
			  <tr>
		<th>VIN</th>
		<th>Make</th>
		<th>Model</th>
		<th>Year</th>
		<th>Fee Class</th>
		<th>Make Reservation</th>
		 </tr>
              </thead>
              <tbody>
	<?php
	
	if(isset($_POST['findCars']))
	{
	$pickupDate = $_POST["pickupDate"];
	$newpickupDate = date("Y-m-d", strtotime($pickupDate));
	$newpickupDateTime = $newpickupDate.' '.$_POST["pickupTime"];
	$returnDate = $_POST["returnDate"];
	$location = $_POST["location"];

	$newreturnDate = date("Y-m-d", strtotime($returnDate));
	$newreturnDateTime = $newreturnDate.' '.$_POST["returnTime"];
	try {
	#$dbh = new PDO('mysql:host=localhost;dbname=KTCS', "root", "");
	$query1 = "SELECT * 
	FROM car join locations on car.LocNo = locations.LocNo
	WHERE VIN NOT IN(SELECT VIN FROM reservations WHERE(('$newpickupDateTime' <= ReturnTime AND ReturnTime <= '$newreturnDateTime')
	 OR ('$newpickupDateTime' <= PickupTime AND PickupTime <= '$newreturnDateTime') 
	 OR (PickupTime <= '$newpickupDateTime' AND ReturnTime >= '$newreturnDateTime'))) 
	AND LocName = '$location'";
	
	
	
		$rows1 = $db->query($query1);

		if($rows1 != NULL)
		{
		foreach($rows1 as $row1) {
		$locNo = $row1['LocNo'];
		echo "<tr><td>".$row1['VIN']."</td><td>".$row1['Make']."</td><td>".$row1['Model']."</td><td>".$row1['Year']."</td><td>".$row1['FeeClass']."</td>";
		#<td><button name='$row1['VIN']' value = 'Make Reservation' /></td></tr>";
		#echo "<td><input type='submit' name='makeResBtn' value = 'Make Reservation' id='". $row1['VIN'] ."' /></td></tr>";
		
		 #echo "<td><button type='button' >Make Reservation</button>   ";
         echo "<td><a href='makeResDB.php?VIN=".$row1['VIN']."&amp;memNo=".$memNo."&amp;PickUpTime=".$newpickupDateTime."&amp;ReturnTime=".$newreturnDateTime."&amp;LocNo=".$locNo."&amp;LocName=".$location."' >Make Reservation</a></td></tr>";
	
		$query1 = null;
		} 
		}
		else
		{
			echo "no cars available for that time and location";
		}
		
		
	
	}
	catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
	}




	?>
	    </tbody>
    </table>
</div>
	</div>
	</body>
	</html>