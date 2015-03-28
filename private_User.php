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
<html lang="en">
  <head>
  <!--HEY GIRL HEY -->
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

    <title>Dashboard</title>

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
	
  </head>
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
          <a class="navbar-brand" href="about.php">K-Town Car Share</a>
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
            <li class="active"><a href="#">Dashboard <span class="sr-only">(current)</span></a></li>
            <li><a href="makeRes.php">Make a reservation</a></li>
            <li><a href="history.php">View Rental History</a></li>
			<li><a href="edit_account.php">Edit Account</a></li>
            <li><a href="about.php">About Page</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Dashboard</h1>

         

          <h2 class="sub-header">Ongoing Reservations</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>VIN</th>
                  <th>PickupTime</th>
                  <th>Expected Return Time</th>
                  <th>Location</th>
                  <th>End Reservation</th>
                </tr>
              </thead>
              <tbody>
                <?php
	$memNoRes = $db->query("Select MemberNo from member where Email = '".$_SESSION['member']['Email']."'");
	foreach ($memNoRes as $memNo1){
	$memberNo = $memNo1['MemberNo'];
	}
try {
		
		$resQuery = "SELECT * FROM reservations join Locations on reservations.PickupLocNo = Locations.LocNo WHERE MemberNo = $memberNo and PickupTime<=NOW() order by PickupTime";
		$rows = $db->query($resQuery);
		
		
		foreach($rows as $row) {
		$resNo = $row['ResNo'];		 
		echo "<tr><td>".$row['VIN']."</td><td>".$row['PickupTime']."</td><td>".$row['ReturnTime']."</td><td>".$row['LocName']."</td>
		<td><a href='endRes.php?resNo=".$resNo."&amp;memNo=".$memberNo."' >End Reservation</a></td></tr>";
		
		}
    
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();}
	?>
          </tbody>
    </table>
</div>
		  
<h2 class="sub-header">Upcoming Reservations</h2>
<div class="table-responsive">
<table class="table table-striped">		  
		<thead>
                <tr>
                  <th>VIN</th>
                  <th>PickupTime</th>
                  <th>Expected Return Time</th>
                  <th>Location</th>
                  <th>End Reservation</th>
<?php $getFut = "SELECT * FROM reservations join locations on reservations.PickupLocNo = locations.LocNo WHERE MemberNo = $memberNo and pickupTime>NOW() order by PickupTime ";
		$futRes = $db->query($getFut);
	
		foreach($futRes as $row) {
		$resNo = $row['ResNo'];
	    echo "<tr><td>".$row['VIN']."</td><td>".$row['PickupTime']."</td><td>".$row['ReturnTime']."</td><td>".$row['LocName']."</td>
		<td><a href='cancelRes.php?resNo=".$resNo."' >Cancel Reservation</a></td></tr>";
		}
?>				  
		  
     </tbody>
    </table>
</div>		  
        </div>
      </div>
    </div>

  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</body>
	</html>

