<!DOCTYPE html>
<html>
<head>

<title>Make a Reservation</title>
</head>
<body>
<?php
	if(isset($_GET['VIN'])){
    try {
	$dbh = new PDO('mysql:host=localhost;dbname=KTCS', "root", "");
	$VIN = $_GET['VIN'];
	$memNo = $_GET['memNo'];
	$newpickupDate = $_GET['PickUpTime'];
	$newreturnDate = $_GET['ReturnTime'];
	$locNo = $_GET['LocNo'];
    #$resQuery = "INSERT INTO reservations VALUES resNo = 1, VIN=".$VIN.", MemberNo = ".$memNo.", ResDate = ".date('Y-m-d H:i:s', time()).", PickUpTime = ".$newpickupDate.", ReturnTime = ".$newreturnDate.", PickupLocNo = ".$locNo;
	$resQuery = "INSERT INTO reservations VALUES (null,".$memNo.", ".$VIN.", '".date('Y-m-d H:i:s', time())."', '".$newpickupDate."', ".$locNo.", '".$newreturnDate."')";
	#date('Y-m-d H:i:s', time())
	echo $resQuery;
    $dbh->exec($resQuery);
	#echo $result;
	echo "<p>Your reservation has been made.  Your reservation detials are as follows:</p>";
	echo "<p>Pickup Date: $newpickupDate</p>";
	echo "<p>Return Date: $newreturnDate</p>";
	echo "<p>Pickup Location: ".$_GET['LocName']."</p>";
	echo "<p>VIN: $VIN</p>";
	
	}
    catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
	}
	else{
	echo "Reservation could not be made. Please try again.";
	}
	?>
	</body>
	</html>
	