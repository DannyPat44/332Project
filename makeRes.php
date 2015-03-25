<!DOCTYPE html>
<html>
<head>

<title>Make a Reservation</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

  <meta charset="utf-8">
  <title>jQuery UI Datepicker - Default functionality</title>
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
	<h2>Make a Reservations</h2>
	
	
	
	<form method="POST">
	<p>
	Rental Pick Up Date:
	<input type="text" id=datepicker name="pickupDate">
	Rental Return Date:
	<input type="text" id=datepicker2 name="returnDate">
	Location:
	<!-- <select name="location"> -->
	
	<?php

	
	$dbh = new PDO('mysql:host=localhost;dbname=KTCS', "root", "");
	$rows = $dbh->query("SELECT locName FROM Locations");
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
	
	<table>
		<th>VIN</th>
		<th>Make</th>
		<th>Model</th>
		<th>Year</th>
		<th>Fee Class</th>
		<th>Make Reservation</th>
		
	<?php
	
	if(isset($_POST['findCars']))
	{
	$pickupDate = $_POST["pickupDate"];
	$newpickupDate = date("Y-m-d", strtotime($pickupDate));
	#$newpickupDate = $newpickupDate.' '.$_POST["pickupTime"];
	$returnDate = $_POST["pickupDate"];
	$location = $_POST["location"];
	echo $location;
	$newreturnDate = date("Y-m-d", strtotime($pickupDate));
	#$newreturnDate = $newreturnDate.' '.$_POST["returnTime"];
	try {
	$dbh = new PDO('mysql:host=localhost;dbname=KTCS', "root", "");
	$query1 = "SELECT * 
	FROM car join locations on car.LocNo = locations.LocNo
	WHERE VIN NOT IN(SELECT VIN FROM reservations WHERE(($newpickupDate < ReturnTime AND ReturnTime < $newreturnDate)
	 OR ($newpickupDate < PickupTime AND PickupTime < $newreturnDate) 
	 OR (PickupTime < $newpickupDate AND ReturnTime > $newreturnDate))) 
	AND LocName = '$location'";
	
	
		$rows1 = $dbh->query($query1);
	
		echo $query1;
		
		foreach($rows1 as $row1) {
			
		echo "<tr><td>".$row1['VIN']."</td><td>".$row1['Make']."</td><td>".$row1['Model']."</td><td>".$row1['Year']."</td><td>".$row1['FeeClass']."</td></tr>;
		#<td><button name='$row1['VIN']' value = 'Make Reservation' /></td></tr>";
	
		
		}
		$query1 = null;
		} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
	}
	?>
	</table>
	</form>
	
	
	
	
	
	
	
	
	</body>
	</html>
