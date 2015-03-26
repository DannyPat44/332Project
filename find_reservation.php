<!DOCTYPE html>
<html>
<head>

<title>Charge Membership Fees</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script>
  $(function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
  });

</script>
</head>
<body>
<h2>Reservations for a given Day</h2>
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
<p>Select the date:</p>
<form action="find_reservation.php" method="post"> 
<input type="text" id=datepicker name="dateofres">
<input type="submit" value="Search" />
</form>

<form action="find_reservation.php" method="post"> 

<table>
		<th>Reservation Number</th>
		<th>VIN</th>
		<th>Member Number</th>
		<th>Reservation Date</th>
		<th>Pickup Time</th>
		<th>Pickup Loction</th>
		<th>Return Time</th>

<?php 

	
	  if(!empty($_POST)) 
		{ 
	
	$Date = $_POST["dateofres"]." 12:00:00";
	
	try {
	
	
	$query1 = "SELECT * FROM Reservations WHERE VIN IN(SELECT VIN FROM reservations WHERE ('$Date' < ReturnTime AND '$Date' > PickupTime))
";
		$rows1 = $db->query($query1);
			
		foreach($rows1 as $row1)
		{
		echo "<tr><td>".$row1['ResNo']."</td><td>".$row1['VIN']."</td><td>".$row1['MemberNo']."</td><td>".$row1['ResDate']."</td><td>".$row1['PickupTime']."</td><td>".$row1['PickupLocNo']."</td><td>".$row1['ReturnTime']."</td><td>";}
		} catch (PDOException $e) 
		{ print "Error!: " . $e->getMessage() . "<br/>";  
		  die();}
	}
	
?> 

</body>
</html>