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
	<h2>Charge Membership</h2>
	
	<form action="charge_members.php" method="post"> 
Date of Annual Membership Renewal:
    <input type="text" id=datepicker name="date">
    <br /><br /> 	
<input type="submit" value="Search" />
	
	<table>
		<th>MemberNo</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Credit Card Number</th>
		<th>Credit Card Expiry</th>
		<th>Annual Fee</th>
	
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
     
     if(!empty($_POST)) 
    { 
	
	$DateofRenewal = $_POST["date"];
	
	try {
	
	
	$query1 = "SELECT MemberNo, FName, LName, CreditCrdNO, CreditExp, AnnualFee FROM member natural join memberfees WHERE (EXTRACT(MONTH FROM $DateofRenewal) = EXTRACT(MONTH FROM RegAnn)) AND (EXTRACT(DAY FROM $DateofRenewal) = EXTRACT(DAY FROM RegAnn))";
	
	echo $query1;
		$rows1 = $db->query($query1);
			
		foreach($rows1 as $row1)
		{echo "<tr><td>".$row1['MemberNo']."</td><td>".$row1['FName']."</td><td>".$row1['CreditCrdNo']."</td><td>".$row1['CreditExp']."</td><td>".$row1['AnnualFee']."</td></tr>";}
		$query1 = null;
		} catch (PDOException $e) 
		{ print "Error!: " . $e->getMessage() . "<br/>";  
		  die();}
	}
	?>
</table>
</form>
</body>
</html>