<!DOCTYPE html>
<html>
<head>

<title>Register A Car</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

 <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  
    function updateTextInput(val) {
      document.getElementById('rangeValue1').value= (val*100); 
    }
  

  </script>
  
</head>
<body>
	<h2>Register A Car</h2>
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
        // Ensure that the user has entered a non-empty username 
        if(empty($_POST['VIN'])) 
        { 
            die("Please enter a VIN."); 
        } 
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['make'])) 
        { 
            die("Please enter a Make."); 
        } 
         
		$VIN = $_POST['VIN'];
		 
        $query = " 
            SELECT 
                * 
            FROM car 
            WHERE VIN = :VIN
        "; 
         
         $stmt = $db->prepare($query);
         
		 $query_params = array( 
            'VIN' => $_POST['VIN'] 
        ); 
         
        try 
        { 
            // These two statements run the query against your database table. 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
		 
      
        // The fetch() method returns an array representing the "next" row from 
        // the selected results, or false if there are no more rows to fetch. 
        $row = $stmt->fetch(); 
         
        // If a row was returned, then we know a matching username was found in 
        // the database already and we should not allow the user to continue. 
        if($row) 
        { 
            die("This VIN is already in use"); 
        } 
       		
        
		$make = $_POST['make'];
		$model = $_POST['model'];
		$year = $_POST['year'];
		$feeclass = $_POST['feeclass'];
		$locno = $_POST['locno'];
		$odmreading = $_POST['odmreading'];
		$gasreading = $_POST['gasreading'];
		$odmreadingmatn = $_POST['odmreadingmatn'];
		$datematn = $_POST['datematn'];
		 
	$update ="Insert into  car (VIN, Make, Model, Year, FeeClass, LocNo, ODMReading, GasReading, ODMReadingMatn, DateMatn) VALUES ('$VIN', '$make', '$model', '$year', '$feeclass', '$locno', '$odmreading', '$gasreading', '$odmreadingmatn', '$datematn')";
	
	// Execute the query
$result2 = $db->query($update);


// Check for errors
if (!$result) {
  
  echo "Update record failed: (" . $db->errno . ") " . $db->error;

} 

		 
        // This redirects the user back to the login page after they register 
        header("Location: private_Admin.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to private_Admin.php"); 
    } 
	 
	 
	 
?> 

<form action="register_car.php" method="post"> 
    VIN:<br /> 
    <input type="text" name="VIN" value="" /> 
    <br /><br /> 
    Manufacturer:<br /> 
    <input type="text" name="make" value="" /> 
    <br /><br /> 
    Model:<br /> 
    <input type="text" name="model" value="" /> 
    <br /><br /> 
	Year:<br /> 
    <input type="text" name="year" value="" /> 
    <br /><br /> 
	Location:<br /> 
	<?php
	
	$loctions = $db->query("SELECT locName,locNo FROM Locations");
	

	echo "<select name='locno'  value='Choose'>Dropdown</option>";
	
	foreach ($loctions as $row) {
		echo "<option value='$row[locNo]'</option>";
		echo "<p>"."$row[locName]"."</p>";
	}
	
	echo "</select>";
	?>
    <br /><br /> 
	Gas Reading:<br /> 
<input id="slider1" name="gasreading" type="range" min="0" max="1" step="0.05" onchange ="updateTextInput(this.value);"/>
<input id="rangeValue1" type="text" value="50" Size=2>%
    <br /><br /> 
	<?php
	
	$FeeClass = $db->query("SELECT FeeClass FROM rentalfees");
	

	echo "<select name='feeclass'  value='Choose'>Dropdown</option>";
	
	foreach ($FeeClass as $row2) {
		echo "<option value='$row2[FeeClass]'</option>";
		echo "<p>"."$row2[FeeClass]"."</p>";
	}
	
	echo "</select>";
	?>
    <br /><br /> 
	ODM Reading:<br /> 
    <input type="text" name="odmreading" value="" /> 
    <br /><br /> 
	ODM Reading at the Last Maintenance :<br /> 
    <input type="text" name="odmreadingmatn" value="" /> 
    <br /><br /> 
	Date of the Maintenance:<br /> 
    <input type="text" id=datepicker name="datematn">
    <br /><br /> 	
	<input type="submit" value="Register" />
</form>
</body>
</html>