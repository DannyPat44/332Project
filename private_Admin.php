
<!DOCTYPE html>
<html>
<head>

<title>Register A Car</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

</head>
<body>
	<h2>Admin Dashboard</h2>


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

Hello <?php echo htmlentities($_SESSION['member']['Email'], ENT_QUOTES, 'UTF-8'); ?><br /> 
<a href="register_car.php">Register Car</a><br /> 
<a href="charge_members.php">Charge Membership Fees</a><br/>
<a href="show_history_car.php">Show Car Rental Histories</a><br/>
<a href="find_reservation.php">Reseverations for a Given Day</a><br/>
<a href="logout.php"><button>Logout</button></a>
</body>

<h3>Most Popular Car</h3>
<table>
<th>VIN</th>
<th>Make</th>
<th>Model</th>
<th>Year</th>
<th>Number Of Uses</th>
<?php
	
	$Car = $db->query("SELECT * FROM car natural join (SELECT NoUses, VIN FROM (SELECT COUNT(*) as NoUses, VIN FROM history GROUP BY VIN ORDER BY NoUses DESC)VinCount LIMIT 1)maxed");
	
	foreach($Car as $row1) {
	
		echo "<tr><td>".$row1['VIN']."</td><td>".$row1['Make']."</td><td>".$row1['Model']."</td><td>".$row1['Year']."</td><td>".$row1['NoUses']."</td><td>";
}
	?>
</table>
<h3>Least Popular Car</h3>
<table>
<th>VIN</th>
<th>Make</th>
<th>Model</th>
<th>Year</th>
<th>Number Of Uses</th>	
<?php	$Car = $db->query("SELECT * FROM car natural join (SELECT NoUses, VIN FROM (SELECT COUNT(*) as NoUses, VIN FROM history GROUP BY VIN ORDER BY NoUses)VinCount LIMIT 1)min");
	
	foreach($Car as $row1) {
	
		echo "<tr><td>".$row1['VIN']."</td><td>".$row1['Make']."</td><td>".$row1['Model']."</td><td>".$row1['Year']."</td><td>".$row1['NoUses']."</td><td>";
} ?>

</table>
<h3>Cars Which Need Maintenance</h3>
<table>
		<th>VIN</th>
		<th>Make</th>
		<th>Model</th>
		<th>Year</th>
		<th>Distance since last maintenance</th>
		
		<?php
	
	$Cars = $db->query("SELECT * FROM car WHERE (ODMREADING - ODMREADINGMatn) > 5000");
	
	foreach($Cars as $row1) {
		$DistanceMatn = ($row1['ODMReading'] - $row1['ODMReadingMatn']);
		echo "<tr><td>".$row1['VIN']."</td><td>".$row1['Make']."</td><td>".$row1['Model']."</td><td>".$row1['Year']."</td><td>".$DistanceMatn."</td></tr>";
}
	
	?>
</table>
</html>
