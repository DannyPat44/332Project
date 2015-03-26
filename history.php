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
	
	$memberemail = $_SESSION['member'];
	$email = $memberemail['Email'];
	
	$memberNo = $db->query("SELECT MemberNo FROM member WHERE Email = '$email'");
foreach($memberNo as $row) {	
try {
		
		$rows = $db->query("select VIN, PickupTime, ReturnTime, LocName, PickupODMReading, ReturnODMReading, Charge from history join locations on history.PickupLocNo = locations.LocNo where MemberNo = ".$row['MemberNo']." order by PickupTime ");
		
		foreach($rows as $row) {
		
		echo "<tr><td>".$row['VIN']."</td><td>".$row['PickupTime']."</td><td>".$row['ReturnTime']."</td><td>".$row['LocName']."</td><td>".$row['PickupODMReading']."</td><td>".$row['ReturnODMReading']."</td><td>".$row['Charge']."</td></tr>";

		}
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
}


?>
		
		
	</table>
	
	
	
	
	
	</body>
	</html>
