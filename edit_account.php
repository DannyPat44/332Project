<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
     
	 $LicenseNo = $_SESSION['member']['licenseNo'];

	 $Email =  $_SESSION['member']['Email'];

    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['member'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
     
    // This if statement checks to determine whether the edit form has been submitted
    // If it has, then the account updating code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        // Make sure the user entered a valid E-Mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
        
		 
        // If the user is changing their E-Mail address, we need to make sure that 
        // the new value does not conflict with a value that is already in the system. 
        // If the user is not changing their E-Mail address this check is not needed.
        if($_POST['email'] != $_SESSION['member']['email']) 
        { 
            // Define our SQL query 
            $query = " 
                SELECT 
                    1 
                FROM member
                WHERE 
                    email = :email 
            "; 
             
            // Define our query parameter values 
            $query_params = array( 
                ':email' => $_POST['email'] 
            ); 
             
            try 
            { 
                // Execute the query 
                $stmt = $db->prepare($query); 
                $result = $stmt->execute($query_params); 
            } 
            catch(PDOException $ex) 
            { 
                // Note: On a production website, you should not output $ex->getMessage(). 
                // It may provide an attacker with helpful information about your code.  
                die("Failed to run query: " . $ex->getMessage()); 
            } 
             
            // Retrieve results (if any) 
            $row = $stmt->fetch(); 
            if($row) 
            { 
                die("This E-Mail address is already in use"); 
            } 
        } 
         
        // If the user entered a new password, we need to hash it and generate a fresh salt 
        // for good measure. 
        if(!empty($_POST['password'])) 
        { 
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
            $password = hash('sha256', $_POST['password'] . $salt); 
            for($round = 0; $round < 65536; $round++) 
            { 
                $password = hash('sha256', $password . $salt); 
            } 
        } 
        else 
        { 
            // If the user did not enter a new password we will not update their old one. 
            $password = null; 
            $salt = null; 
        } 
         
        // Initial query parameter values 
        $query_params = array( 
            ':email' => $_POST['email'], 
            ':licenseNo' => $_SESSION['member']['licenseNo'], 
        ); 
         
        // If the user is changing their password, then we need parameter values 
        // for the new password hash and salt too. 
        if($password !== null) 
        { 
            $query_params[':password'] = $password; 
            $query_params[':salt'] = $salt; 
        } 
         
        // Note how this is only first half of the necessary update query.  We will dynamically 
        // construct the rest of it depending on whether or not the user is changing 
        // their password. 
        $query = " 
            UPDATE member 
            SET 
                email = :email 
        "; 
         
        // If the user is changing their password, then we extend the SQL query 
        // to include the password and salt columns and parameter tokens too. 
		
		
        if($password !== null) 
        { 
            $query .= " 
                , password = :password 
                , salt = :salt 
            "; 
        } 
         
        // Finally we finish the update query by specifying that we only wish 
        // to update the one record with for the current user. 
        $query .= " 
            WHERE 
                 LicenseNo = :licenseNo 
        ";

		$fName = $_POST['fname'];
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
		
		 $query2 = "UPDATE member SET Email = '$Email'";
		 
		if(!empty($_POST['fname']))
		{
		
		$query2 .= ", FName = '$fName'";
		
		}
		
		if(!empty($_POST['lname']))
		{
		
		$query2 .= ", LName = '$lName'";
		
		}
		
		if(!empty($_POST['addhouseno']))
		{
		
		$query2 .= ", AddHouseNo = '$AddHouseNo'";
		
		}
		
		if(!empty($_POST['addAptNo']))
		{
		
		$query2 .= ", AddAptNo = '$AddAptNo'";
		
		}
		
		if(!empty($_POST['addstreet']))
		{
		
		$query2 .= ", AddStreet = '$AddStreet'";
		
		}
		
		if(!empty($_POST['addcity']))
		{
		
		$query2 .= ", AddCity = '$AddCity'";
		
		}
		
		if(!empty($_POST['addpstcde']))
		{
		
		$query2 .= ", AddPstcde = '$AddPstcde'";
		
		}
		
		if(!empty($_POST['addprovince']))
		{
		
		$query2 .= ", AddProvince = '$AddProvince'";
		
		}
		
		if(!empty($_POST['primphoneno']))
		{
		
		$query2 .= ", PrimPhoneNo = '$PrimPhoneNo'";
		
		}
		
		if(!empty($_POST['secphoneno']))
		{
		
		$query2 .= ", SecPhoneNo = '$SecPhoneNo'";
		
		}
		
		if(!empty($_POST['creditcrdno']))
		{
		
		$query2 .= ", CreditCrdNo = '$CreditCrdNo'";
		
		}
		
		if(!empty($_POST['creditexp']))
		{
		
		$query2 .= ", CreditExp = '$CreditExp'";
		
		}
		
		
		
         $query2 .= " WHERE LicenseNo = '$LicenseNo' ";
		 
		 echo $query2;
		
		$result2 = $db->query($query2); 
		
        try 
        { 
            // Execute the query 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // Now that the user's E-Mail address has changed, the data stored in the $_SESSION 
        // array is stale; we need to update it so that it is accurate. 
        $_SESSION['member']['email'] = $_POST['email']; 
         
        // This redirects the user back to the members-only page after they register 
        header("Location: private.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to private.php"); 
    } 
     
?> 
<h1>Edit Account</h1> 
<form action="edit_account.php" method="post"> 
    Email:<br /> 

    <b><?php echo htmlentities($_SESSION['member']['Email'], ENT_QUOTES, 'UTF-8'); ?></b> 
    <br /><br /> 
    E-Mail Address:<br /> 
    <input type="text" name="email" value="<?php echo htmlentities($_SESSION['member']['Email'], ENT_QUOTES, 'UTF-8'); ?>" /> 

    <br /><br /> 
    Password:<br /> 
    <input type="password" name="password" value="" /><br /> 
    <i>(leave blank if you do not want to change your password)</i> 
    <br /><br /> 
	License Number:<br /> 
    <input type="text" name="licenseNo" value="" /> 
    <br /><br /> 
	First Name:<br /> 
    <input type="text" name="fname" value="" /> 
    <br /><br /> 
	Last Name:<br /> 
    <input type="text" name="lname" value="" /> 
    <br /><br /> 
	House Number:<br /> 
    <input type="text" name="addhouseno" value="" /> 
    <br /><br /> 
	Apartment Number:<br /> 
    <input type="text" name="addaptno" value="" /> 
    <br /><br /> 
	Street Name:<br /> 
    <input type="text" name="addstreet" value="" /> 
    <br /><br /> 
	City:<br /> 
    <input type="text" name="addcity" value="" /> 
    <br /><br /> 
	Postal code:<br /> 
    <input type="text" name="addpstcde" value="" /> 
    <br /><br /> 
	Province:<br /> 
    <input type="text" name="addprovince" value="" /> 
    <br /><br /> 
	Primary Phone Number:<br /> 
    <input type="text" name="primphoneno" value="" /> 
    <br /><br />
	Secondary Phone Number:<br /> 
    <input type="text" name="secphoneno" value="" /> 
    <br /><br />
	Credit Card Number:<br /> 
    <input type="text" name="creditcrdno" value="" /> 
    <br /><br />
	Credit Card Expire Date:<br /> 
    <input type="text" name="creditexp" value="" /> 
    <br /><br />
    <input type="submit" value="Update Account" /> 
	<i> </i>
</form>