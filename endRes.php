
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

<title>End Reservation</title>

</head>

<body>
<h2>End Reservation</h2>
	
	<form method="POST">
	Return Odometer Reading:
	<input type="text" name="returnODM">
	Comments on Car:
	<input type="text" name="carComments">
	Comments on KTCS:
	<input type="text" name="KTCScomments">
	<input type="submit" name="endRes"  value="End Reservation">
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
	/*  if(isset($_POST['closeRes']))
	{
		echo 2;
		$odm = $_POST['returnODM'];
		header("Location: closeRes.php?resNo=$resNo&amp;odm=$odm");
		die("Redirecting to: closeRes.php?resNo=$resNo&amp;odm=$odm");
	} */
	/* $odmEnd = "UPDATE History
			SET ReturnODMReading = ".$_POST['returnODM']."
			WHERE ResNo = $resNO";
	$db->exec($odmEnd);
			echo $odmEnd;
	$odmCar = "UPDATE Car
				SET ODMReading = ".$_POST['returnODM']."
				WHERE VIN = (Select VIN From History WHERE ResNo = $resNo)";
	$db->exec($odmCar);
	
	$charge = "UPDATE History 
			SET Charge = (TIMESTAMPDIFF(SECOND, (SELECT PickUPTime FROM reservations WHERE ResNo = $resNo), (SELECT ReturnTime FROM reservations WHERE ResNo = $resNo) )/3600)*(Select rate FROM rentalfees natural join(SELECT Feeclass FROM car natural join (SELECT VIN FROM Reservations WHERE ResNo = $resNo)getVIN)getfeeclass) 
WHERE ResNo = $resNo";

	$db->exec($charge);
	
	$db->exec("DELETE FROM Reservations WHERE ResNo = $resNo"); */


	  

	
	#
	?>

	</body>
	</html>
