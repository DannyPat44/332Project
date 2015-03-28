<!DOCTYPE html>
<html>
<head>

<title>Register</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
 <script>
  $(function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
  });

  </script>
   <link href="dashboard.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">K-Town Car Share</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
          </ul>
        </div>
      </div>
    </nav>
	<div class="container-fluid">
    <div class="row">
	

<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
     
    // This if statement checks to determine whether the registration form has been submitted 
    // If it has, then the registration code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        // Ensure that the user has entered a non-empty username 
        if(empty($_POST['licenseNo'])) 
        { 
            die("Please enter a License Number."); 
        } 
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['password'])) 
        { 
            die("Please enter a password."); 
        } 
         
		if(empty($_POST['fname'])) 
        { 
            die("Please enter a First name."); 
        } 
		 
		if(empty($_POST['lname'])) 
        { 
            die("Please enter a Last name."); 
        } 
		 
		 
		 
        
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
        // We will use this SQL query to see whether the username entered by the 
        // user is already in use.  A SELECT query is used to retrieve data from the database. 
        // :username is a special token, we will substitute a real value in its place when 
        // we execute the query. 
        $query = " 
            SELECT 
                1 
            FROM member 
            WHERE 
                LicenseNo = :licenseNo
        "; 
         
        // This contains the definitions for any special tokens that we place in 
        // our SQL query.  In this case, we are defining a value for the token 
        // :username.  It is possible to insert $_POST['username'] directly into 
        // your $query string; however doing so is very insecure and opens your 
        // code up to SQL injection exploits.  Using tokens prevents this. 
        // For more information on SQL injections, see Wikipedia: 
        // http://en.wikipedia.org/wiki/SQL_Injection 
        $query_params = array( 
            ':licenseNo' => $_POST['licenseNo'] 
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
            die("This username is already in use"); 
        } 
         
        // Now we perform the same type of check for the email address, in order 
        // to ensure that it is unique. 
        $query = " 
            SELECT 
                1 
            FROM member 
            WHERE 
                Email = :email 
        "; 
         
        $query_params = array( 
            ':email' => $_POST['email']
        ); 
         
        try 
        { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        $row = $stmt->fetch(); 
         
        if($row) 
        { 
            die("This email address is already registered"); 
        } 
         
        // An INSERT query is used to add new rows to a database table. 
        // Again, we are using special tokens (technically called parameters) to 
        // protect against SQL injection attacks. 
        $query = " 
            INSERT INTO member ( 
                LicenseNo, 
                Password, 
                salt, 
                Email
            ) VALUES ( 
                :licenseNo, 
                :password, 
                :salt, 
                :email
        )"; 
		
	
         
        // A salt is randomly generated here to protect again brute force attacks 
        // and rainbow table attacks.  The following statement generates a hex 
        // representation of an 8 byte salt.  Representing this in hex provides 
        // no additional security, but makes it easier for humans to read. 
        // For more information: 
        // http://en.wikipedia.org/wiki/Salt_%28cryptography%29 
        // http://en.wikipedia.org/wiki/Brute-force_attack 
        // http://en.wikipedia.org/wiki/Rainbow_table 
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
         
        // This hashes the password with the salt so that it can be stored securely 
        // in your database.  The output of this next statement is a 64 byte hex 
        // string representing the 32 byte sha256 hash of the password.  The original
        // password cannot be recovered from the hash.  For more information: 
        // http://en.wikipedia.org/wiki/Cryptographic_hash_function 
        $password = hash('sha256', $_POST['password'] . $salt); 
         
        // Next we hash the hash value 65536 more times.  The purpose of this is to 
        // protect against brute force attacks.  Now an attacker must compute the hash 65537 
        // times for each guess they make against a password, whereas if the password
        // were hashed only once the attacker would have been able to make 65537 different  
        // guesses in the same amount of time instead of only one. 
        for($round = 0; $round < 65536; $round++) 
        { 
            $password = hash('sha256', $password . $salt); 
        } 
        
        // Here we prepare our tokens for insertion into the SQL query.  We do not 
        // store the original password; only the hashed version of it.  We do store 
        // the salt (in its plaintext form; this is not a security risk). 
        $query_params = array( 
            ':licenseNo' => $_POST['licenseNo'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            // Execute the query to create the user 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
		
        $fName = $_POST['fname'];
		$MemberType = $_POST['membertype'];
		$lName = $_POST['lname'];
		$AddHouseNo = $_POST['addhouseno'];
		$AddAptNo = $_POST['addaptno'];
		$Addstreet = $_POST['addstreet'];
		$AddCity = $_POST['addcity'];
		$AddPstcde = $_POST['addpstcde'];
		$AddProvince = $_POST['addprovince'];
		$PrimPhoneNo = $_POST['primphoneno'];
		$SecPhoneNo = $_POST['secphoneno'];
		$CreditCrdNo = $_POST['creditcrdno'];
		$CreditExp = $_POST['creditexp'];
		$Email = $_POST['email'];
		 
	$update ="UPDATE member SET FName = '$fName', MemberType = '$MemberType', LName = '$lName', AddHouseNo = '$AddHouseNo', AddAptNo = '$AddAptNo', Addstreet = '$Addstreet', AddCity = '$AddCity', AddPstcde = '$AddPstcde', AddProvince = '$AddProvince', PrimPhoneNo = '$PrimPhoneNo', SecPhoneNo = '$SecPhoneNo', CreditCrdNo = '$CreditCrdNo', CreditExp = '$CreditExp', RegAnn = CURDATE() WHERE Email = '$Email'";
	
	// Execute the query
$result2 = $db->query($update);


// Check for errors
if (!$result) {
  
  echo "Update record failed: (" . $db->errno . ") " . $db->error;

} 

		 
        // This redirects the user back to the login page after they register 
        header("Location: login.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to login.php"); 
    } 
     
?> 
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
   <h1 class="page-header">Register</h1>
<form action="register.php" role="form" method="post"> 
<div class="form-group">
<label for="licenseNo">License Number:</label>
    <input type="text" name="licenseNo" value="" /> 
</div>
<div class="form-group">
<label for="membertype">Member Type:</label>
	<?php
	$rows = $db->query("SELECT MemberType FROM memberFees");
	echo "<select name=membertype  value='Choose'>Dropdown</option>";
	foreach ($rows as $row) {
		echo "<option value='$row[MemberType]'</option>";
		echo "<p>"."$row[MemberType]"."</p>";
	}
	
	echo "</select>";
	?>
	</div>
	<div class="form-group">
    <label for="email">E-Mail:</label>
    <input type="text" id="email" name="email" value="" /> 
	</div>
	<div class="form-group">
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="" />
	</div>
	<div class="form-group">
	<label for="fname">First Name:</label>
    <input type="text" name="fname"  id="fname" value="" /> 
	</div>
	<div class="form-group">
	<label for="lname">Last Name:</label>
    <input type="text" name="lname" value="" />
	</div>
	<div class="form-group">
	<label for="addhouse">House Number:</label>
    <input type="text" name="addhouseno" value="" /> 
	</div>
	<div class="form-group">
	<label for="addaptno">Apartment Number:</label>
    <input type="text" name="addaptno" value="" /> 
	</div>
	<div class="form-group">
	<label for="addstreet">Street Name:</label>
    <input type="text" name="addstreet" value="" /> 
	</div>
	<div class="form-group">
	<label for="addcity">City:</label>
    <input type="text" name="addcity" value="" /> 
	</div>
	<div class="form-group">
	<label for="addpstcde">Postal code:</label>
    <input type="text" name="addpstcde" value="" /> 
	</div>
	<div class="form-group">
	<label for="addprovince">Province:</label> 
   <select name=addprovince  value='Choose'>DropDown</option>
   <option value="ON">ON</option>
   <option value="QC">QC</option>
   <option value="AB">AB</option>
   <option value="BC">BC</option>
   <option value="MB">MB</option>
   <option value="NB">NB</option>
   <option value="NL">NL</option>
   <option value="NS">NS</option>
   <option value="NT">NT</option>
   <option value="NU">NU</option>
   <option value="PE">PE</option>
   <option value="SK">SK</option>
   <option value="YT">YT</option>
   </select>
   </div>
   <div class="form-group">
	<label for="primphoneno">Primary Phone Number:</label>
    <input type="text" name="primphoneno" value="" /> 
	</div>
	<div class="form-group">
	<label for="secphoneno">Secondary Phone Number:</label>
    <input type="text" name="secphoneno" value="" /> 
	</div>
	<div class="form-group">
	<label for="creditcrdno">Credit Card Number:</label>
    <input type="text" name="creditcrdno" value="" /> 
	</div>
	<div class="form-group">
	<label for="creditexp">Credit Card Expire Date:</label>
	<input type="text" id=datepicker name="creditexp">
	</div>
	<div class="form-group">
	<input type="submit" value="Register" />
	</div>
</form>
</div>
</div>
</div>
</div>
</body>
</html>