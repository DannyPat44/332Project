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
Hello <?php echo htmlentities($_SESSION['member']['Email'], ENT_QUOTES, 'UTF-8'); ?>, secret content!<br /> 


<a href="history.php">Show Rental History</a><br/>
<a href="makeRes.php">Make a Reservation</a><br/>
<a href="logout.php"><button>Logout</button></a>


<!DOCTYPE html>
<html>
<head>

<title>My Dashboard</title>

</head>

<body>
	<h2>Ongoing Reservations</h2>
	
	<table>
		<th>VIN</th>
		<th>PickupTime</th>
		<th>Expected Return Time</th>
		<th>Location</th>
		<th>End Reservation</th>
<?php
	$memNoRes = $db->query("Select MemberNo from member where Email = '".$_SESSION['member']['Email']."'");
	foreach ($memNoRes as $memNo1){
	$memberNo = $memNo1['MemberNo'];
	}


	

try {
		
		$resQuery = "SELECT * FROM reservations join Locations on reservations.PickupLocNo = Locations.LocNo WHERE MemberNo = $memberNo and PickupTime<=NOW() order by PickupTime";
		$rows = $db->query($resQuery);
		
		#echo "SELECT * FROM reservations WHERE MemberNo = $memberNo and PickupTime<=NOW() order by PickupTime"
		foreach($rows as $row) {
		$resNo = $row['ResNo'];
	    #echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]"</td></tr>";			 
		echo "<tr><td>".$row['VIN']."</td><td>".$row['PickupTime']."</td><td>".$row['ReturnTime']."</td><td>".$row['LocName']."</td>
		<td><a href='endRes.php?resNo=".$resNo."&amp;memNo=".$memberNo."' >End Reservation</a></td></tr>";
		#<td><form  method='POST'><input type='submit' name='resNo' value = 'End Reservation' id='". $resNo ."' /></form></td></tr>";
#action='endRes.php'
		}
    
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

		echo "</table>
	<h2>Upcoming Reservations</h2>
	<table>
		<th>VIN</th>
		<th>PickupTime</th>
		<th>Expected Return Time</th>
		<th>Location</th>
		<th>Cancel Reservation</th>";
		$getFut = "SELECT * FROM reservations join locations on reservations.PickupLocNo = locations.LocNo WHERE MemberNo = $memberNo and pickupTime>NOW() order by PickupTime ";
		$futRes = $db->query($getFut);
	
		foreach($futRes as $row) {
		$resNo = $row['ResNo'];
	    echo "<tr><td>".$row['VIN']."</td><td>".$row['PickupTime']."</td><td>".$row['ReturnTime']."</td><td>".$row['LocName']."</td>
		<td><a href='cancelRes.php?resNo=".$resNo."' >Cancel Reservation</a></td></tr>";
		#<td><form  method='POST'><input type='submit' name='resNo' value = 'End Reservation' id='". $resNo ."' /></form></td></tr>";
#action='endRes.php'
		}
		echo "</table>";
?>
		
		
	
	<?php
		
		
	
	?>
	
	
	</body>
	</html>

