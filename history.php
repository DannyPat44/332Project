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

<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <title>Rental History</title>

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet"></head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">K-Town Car Share</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
		    <li><a href="private_User.php">Dashboard for <?php echo htmlentities($_SESSION['member']['Email'], ENT_QUOTES, 'UTF-8'); ?></a></li>
			<li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="private_User.php">Dashboard</a></li>
            <li><a href="makeRes.php">Make a reservation</a></li>
            <li class="active"><a href="history.php">View Rental History</a><span class="sr-only">(current)</span></li>
			<li><a href="edit_account.php">Edit Account</a></li>
            <li><a href="about.php">About Page</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">History</h1>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>VIN</th>
		<th>PickupTime</th>
		<th>Return Time</th>
		<th>Location</th>
		<th>Pickup Odometer Reading</th>
		<th>Return Odometer Reading</th>
		<th>Charge</th>
                </tr>
              </thead>
              <tbody>
<?php

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
		
		
	   </tbody>
    </table>
</div>		  
        </div>
      </div>
    </div>
	
	</body>
	</html>
