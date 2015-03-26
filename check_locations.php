<!DOCTYPE html>
<html>
<head>

<title>Register A Car</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

</head>
<body>
	<h2>Check Locations</h2>
	<form action="check_locations.php" method="post"> 
	<p>Select location:</p>
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
	
	$loctions = $db->query("SELECT locName,locNo FROM Locations");
	

	echo "<select name='locno'  value='Choose'>Dropdown</option>";
	
	foreach ($loctions as $row) {
		echo "<option value='$row[locNo]'</option>";
		echo "<p>"."$row[locName]"."</p>";
	}
	
	echo "</select>";
	?>
	<input type="submit" value="Search" />
	</form>
  
  <table>

<?php 
	
	  if(!empty($_POST)) 
		{ 
	try {
	
	$Location = $_POST["locno"];
	$query1 = "SELECT * FROM car WHERE LocNo = '$Location'";
	
		$rows1 = $db->query($query1);
			
		foreach($rows1 as $row1)
		{
		echo "<th>VIN</th><th>Make</th><th>Model</th><th>Year</th><th>Type of Vehicle</th>";
		echo "<tr><td>".$row1['VIN']."</td><td>".$row1['Make']."</td><td>".$row1['Model']."</td><td>".$row1['Year']."</td><td>".$row1['FeeClass']."</td><td>";
		echo "<tr><table>";
		echo "<tr><th>Reservation Number</th><th>Member Number</th><th>Reservation Date</th><th>Pickup Time</th></tr>";
		$query2 = "SELECT * FROM reservations WHERE VIN =".$row1['VIN']." ";
		$res = $db->query($query2);
		foreach($res as $res1)
		{echo "<tr><td>".$res1['ResNo']."</td><td>".$res1['MemberNo']."</td><td>".$res1['ResDate']."</td><td>".$res1['PickupTime']."</td><td>";
		echo "</tr>";
		}
		echo"<table/>";
		}
		} catch (PDOException $e) 
		{ print "Error!: " . $e->getMessage() . "<br/>";  
		  die();}
	}
	
?> 
</table>
</body>
</html>