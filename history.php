<!DOCTYPE html>
<html>
<head>

<title>Rental History</title>

</head>

<body>
	<h2>Rental History</h2>
	
	<table>
		<th>VIN</th>
		<th>PickupTime</th>
		<th>Return Time</th>
		<th>Location</th>
		<th>Pickup Odometer Reading</th>
		<th>Return Odometer Reading</th>
		<th>Charge</th>
<?php

$memberNo = 303;

	

try {
		$dbh = new PDO('mysql:host=localhost;dbname=KTCS', "root", "");
		$rows = $dbh->query("select VIN, PickupTime, ReturnTime, LocName, PickupODMReading, ReturnODMReading, Charge from history join locations on history.PickupLocNo = locations.LocNo where MemberNo = $memberNo order by PickupTime ");
		echo "select VIN, PickupTime, ReturnTime, LocName, PickupODMReading, ReturnODMReading, Charge from history join locations on history.PickupLocNo = location.LocNo where MemberNo = $memberNo order by PickupTime ";
		foreach($rows as $row) {
		
		echo "<tr><td>".$row['VIN']."</td><td>".$row['PickupTime']."</td><td>".$row['ReturnTime']."</td><td>".$row['LocName']."</td><td>".$row['PickupODMReading']."
		</td><td>".$row['ReturnODMReading']."</td><td>".$row['Charge']."</td></tr>";

		}
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}



?>
		
		
	</table>
	
	
	
	
	
	</body>
	</html>
