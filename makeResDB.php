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
    $result = $dbh->query($resQuery);
	}
    catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
	}
	else{
	echo "didn't work";
	}
	?>
	</body>
	</html>
	