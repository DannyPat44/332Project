<!DOCTYPE html>
<html>
<head>

<title>Make a Reservation</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

  <meta charset="utf-8">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
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

	$memNo =303;
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
	$newpickupDateTime = $newpickupDate.' '.$_POST["pickupTime"];
	$returnDate = $_POST["returnDate"];
	$location = $_POST["location"];
	echo $location;
	$newreturnDate = date("Y-m-d", strtotime($returnDate));
	$newreturnDateTime = $newreturnDate.' '.$_POST["returnTime"];
	try {
	$dbh = new PDO('mysql:host=localhost;dbname=KTCS', "root", "");
	$query1 = "SELECT * 
	FROM car join locations on car.LocNo = locations.LocNo
	WHERE VIN NOT IN(SELECT VIN FROM reservations WHERE(('$newpickupDateTime' <= ReturnTime AND ReturnTime <= '$newreturnDateTime')
	 OR ('$newpickupDateTime' <= PickupTime AND PickupTime <= '$newreturnDateTime') 
	 OR (PickupTime <= '$newpickupDateTime' AND ReturnTime >= '$newreturnDateTime'))) 
	AND LocName = '$location'";
	
	
	
		$rows1 = $dbh->query($query1);
	
		echo $query1;
		if($rows1 != NULL)
		{
		foreach($rows1 as $row1) {
		$locNo = $row1['LocNo'];
		echo "<tr><td>".$row1['VIN']."</td><td>".$row1['Make']."</td><td>".$row1['Model']."</td><td>".$row1['Year']."</td><td>".$row1['FeeClass']."</td>";
		#<td><button name='$row1['VIN']' value = 'Make Reservation' /></td></tr>";
		#echo "<td><input type='submit' name='makeResBtn' value = 'Make Reservation' id='". $row1['VIN'] ."' /></td></tr>";
		
		 #echo "<td><button type='button' >Make Reservation</button>   ";
         echo "<td><a href='makeResDB.php?VIN=".$row1['VIN']."&amp;memNo=".$memNo."&amp;PickUpTime=".$newpickupDateTime."&amp;ReturnTime=".$newreturnDateTime."&amp;LocNo=".$locNo."' >Make Reservation</a></td></tr>";
	
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
/*
	if(isset($_POST['makeResBtn'])){
	echo "<p>".$_POST['makeResBtn']."</p>";
	echo $sql = "INSERT INTO History
(ResNo, MemberNo, VIN, ResDate, PickUpTime, PickupLocNo)
Select ResNo, MemberNo, VIN, ResDate, PickUpTime, PickupLocNo
FROM Reservations 
WHERE ResNo = '".$_POST['resNo']."' ";
$query = $dbh->query($sql);
if ($query == 1) { 
    echo 'Reservation Has Been Deleted';
} else { 
    echo 'Deletion Failed';
} 
}
*/



	?>
	</table>
	</form>
	
	
	
	
	
	
	
	
	</body>
	</html>