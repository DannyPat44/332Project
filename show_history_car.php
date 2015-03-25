<!DOCTYPE html>
<html>
<head>

<title>Charge Membership Fees</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
  
</head>
<body>

<form action="show_history_car.php" method="post"> 
Select the cars VIN number:
<?php
	
	$VINS = $db->query("SELECT VIN FROM car");
	

	echo "<select name='VIN'  value='Choose'>Dropdown</option>";
	
	foreach ($VINS as $row) {
		echo "<option value='$row[VIN]'</option>";
		echo "<p>"."$row[VIN]"."</p>";
	}
	
	echo "</select>";
	?>
<input type="submit" value="Search" />
</form>

<form action="charge_members.php" method="post"> 
Date of Annual Membership Renewal:
    <input type="text" id=datepicker name="date">
    <br /><br /> 	
<input type="submit" value="Search" />

<table>
		<th>Reservation Number</th>
		<th>Member Number</th>
		<th>VIN</th>
		<th>Reservation Date</th>
		<th>Pickup Time</th>
		<th>Pickup Loction</th>
		<th>Return Time</th>
		<th>Pickup ODM Reading</th>
		<th>Return ODM Reading</th>
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
	
	  if(!empty($_POST)) 
		{ 
	
	$VINOFINT = $_POST["date"];
	
	try {
	
	
	$query1 = "SELECT * FROM `history` WHERE VIN = '$VINOFINT'";
	
	echo $query1;
		$rows1 = $db->query($query1);
			
		foreach($rows1 as $row1)
		{echo "<tr><td>".$row1['ResNo']."</td><td>".$row1['FName']."</td><td>".$row1['CreditCrdNo']."</td><td>".$row1['CreditExp']."</td><td>".$row1['AnnualFee']."</td></tr>";}
		$query1 = null;
		} catch (PDOException $e) 
		{ print "Error!: " . $e->getMessage() . "<br/>";  
		  die();}
	}
	
?> 

</body>
</html>