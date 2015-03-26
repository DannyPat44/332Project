<html>
<head><title>Load KTCS Database</title></head>
<body>

<?php
/* Program: KTCS_load.php
 * Desc:    Creates and loads the KTCS database tables with 
 *          sample data.
 */
 
 $host = "localhost";
 $user = "root";
 $password = "";
 $database = "KTCS";

 $cxn = mysqli_connect($host,$user,$password, $database);
 // Check connection
 if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  die();
  }
   
   mysqli_query($cxn,"drop table Car;");
   mysqli_query($cxn,"drop table Member;");
   mysqli_query($cxn,"drop table Reservations;");
   mysqli_query($cxn,"drop table Locations;");
   mysqli_query($cxn,"drop table Comments;");
   mysqli_query($cxn,"drop table MemberFees;");
   mysqli_query($cxn,"drop table RentalFees;");
   mysqli_query($cxn,"drop table History;");

   mysqli_query($cxn,"CREATE TABLE Car(
                  VIN			CHAR(17)		NOT NULL,
                  Make			VARCHAR(15)		NOT NULL,
                  Model			VARCHAR(15)		NOT NULL,
				  Year			INTEGER			NOT NULL,
				  FeeClass		VARCHAR(15)		NOT NULL,
				  LocNo			INTEGER,
				  ODMReading	INTEGER			NOT NULL,
				  GasReading	FLOAT			NOT NULL,
				  ODMReadingMatn INTEGER		NOT NULL,
				  DateMatn       Date		    NOT NULL,
                  PRIMARY KEY(VIN));");

   mysqli_query($cxn,"CREATE TABLE Member(
                  MemberNo		INT(11)  		NOT NULL AUTO_INCREMENT,
				  MemberType	VARCHAR(15),
				  FName 		VARCHAR(15)		NOT NULL,
				  LName			VARCHAR(20)		NOT NULL,
				  AddHouseNo	INTEGER			NOT NULL,
				  AddAptNo		INTEGER,
				  AddStreet		VARCHAR(15)		NOT NULL,
				  AddCity		VARCHAR(30)		NOT NULL,
				  AddPstcde		CHAR(6)			NOT NULL,
				  AddProvince	VARCHAR(5)		NOT NULL,
				  PrimPhoneNo	INTEGER			NOT NULL,
				  SecPhoneNo	INTEGER,
				  Email			VARCHAR(30)		NOT NULL,
				  LicenseNo		VARCHAR(30)		NOT NULL,
				  CreditCrdNo	CHAR(16)		NOT NULL,
				  CreditExp		DATE			NOT NULL,
				  RegAnn		DATE			NOT NULL,
				  Password		CHAR(64) COLLATE utf8_unicode_ci NOT NULL,
				  salt          CHAR(16) COLLATE utf8_unicode_ci NOT NULL,
				  UNIQUE KEY LicenseNo (LicenseNo),
				  UNIQUE KEY email (email),
                  PRIMARY KEY(MemberNo))ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");


   mysqli_query($cxn,"CREATE TABLE MemberFees(
				MemberType	VARCHAR(15)			NOT NULL,
				AnnualFee	DECIMAL(5,2)		NOT NULL,
                PRIMARY KEY(MemberType));");
 

   mysqli_query($cxn,"CREATE TABLE Locations(
                  LocNo			INTEGER			NOT NULL,
				  LocName		VARCHAR(30)		NOT NULL,
				  NoSpaces		INTEGER			NOT NULL,
				  LocAddress	VARCHAR(70)		NOT NULL,
                  PRIMARY KEY(LocNo));");

  mysqli_query($cxn,"CREATE TABLE History(
				  ResNo			INTEGER     	NOT NULL,
                  MemberNo		INTEGER 		NOT NULL,
                  VIN			CHAR(17)		NOT NULL,
                  ResDate		DATE        	NOT NULL,
                  PickupTime	DATETIME 		NOT NULL,
				  PickupLocNo   INTEGER         NOT NULL,
				  ReturnTime    DATETIME        NOT NULL,
				  PickupODMReading INTEGER  	NOT NULL,
				  ReturnODMReading INTEGER  	NOT NULL,
				  Charge		DECIMAL(6,2)	,
                  PRIMARY KEY(ResNo));");
				  
mysqli_query($cxn,"CREATE TABLE Comments(
                  CommentNo 	INTEGER 		NOT NULL,
				  CommentDate	DATETIME		NOT NULL,
				  MemeberNo 	INTEGER 		NOT NULL,
				  Topic			VARCHAR(32) 	NOT NULL,
				  VIN			CHAR(17)				,
				  Comment       VARCHAR(250)   	NOT NULL,
				  ReplyNo		INTEGER					,
                  PRIMARY KEY(CommentNo));");
				
mysqli_query($cxn,"CREATE TABLE RentalFees(
                  FeeClass      VARCHAR(15) 	 NOT NULL,
				  Rate          DECIMAL(4,2) 	 NOT NULL,
                  PRIMARY KEY(FeeClass));");
				  
mysqli_query($cxn,"CREATE TABLE Reservations(
				  ResNo			INTEGER    		 NOT NULL AUTO_INCREMENT,
                  MemberNo		INTEGER			 NOT NULL,
                  VIN			CHAR(17)		 NOT NULL,
                  ResDate		DATE        	 NOT NULL,
                  PickupTime	DATETIME 		 NOT NULL,
				  PickupLocNo   INTEGER          NOT NULL,
				  ReturnTime    DATETIME         NOT NULL,
                  PRIMARY KEY(ResNo));");
   
mysqli_query($cxn,"insert into RentalFees values
         ('Sedan','24.50'),
		 ('Van','30.00'),
		 ('Coupe','22.50')
        ");
mysqli_query($cxn,"insert into Reservations values
         (NULL,'1','12345678912345678',CURDATE(),'2015-03-09 23:59:59','23','2015-03-11 14:00:00'),
		 ('NULL','2','16645678912345678',CURDATE(),'2015-05-12 18:00:00','14','2015-05-14 18:00:00'),
		 ('NULL','1','17845678912345678',CURDATE(),'2015-04-11 23:52:59','2','2015-04-18 23:52:59'),
		 ('NULL','2','16645678912345678',CURDATE(),'2015-06-12 23:59:59','14','2015-06-15 23:59:59'),
		 ('NULL','1','17845678912345678',CURDATE(),'2015-06-10 18:00:00','2','2015-06-12 07:00:00')
		 
		 
		 
        ");
     
mysqli_query($cxn,"insert into Comments values
         ('1',NOW(), '8009', 'Car','12345678912345678','HEY YALL HOW ARE YOU?',NULL),
		 ('2',NOW(), '303', 'Car','16645678912345678','I AM FINE WHAT ABOUT YOU?','8009'),
		 ('3',NOW(), '148', 'General',NULL,'I AM WELL TOO, THANKS','303')
        ");
		
mysqli_query($cxn,"insert into MemberFees values
         ('Student','100.50'),
		 ('Senior','60.50'),
		 ('VIP','134.50')
        ");

mysqli_query($cxn,"insert into History values
         ('41','1','12345678912345678',CURDATE(),'2014-12-31 23:59:59','23','2015-01-02 23:59:59','80808','1000000', 45),
		 ('5','1','16345678912345678',CURDATE(),'2014-11-31 23:59:59','14','2014-12-03 23:59:59','1010101','10011011', 1000),
		 ('36','2','17845678912345678',CURDATE(),'2014-09-11 23:52:59','2','2014-09-18 23:52:59','202', '400', 850)
        ");
mysqli_query($cxn,"insert into Locations values
         ('2','Brock Road','13','145 Division St'),
		 ('14','The Lake','0','18 Burn Street'),
		 ('48','Queens Campus','2','45 ByTown Drive')
        ");
		
mysqli_query($cxn,"insert into Car values
         ('12345678912345678','Ford','Bronco','1999','Van',NULL,'1233122','0.68','1234',CURDATE()),
		 ('16345678912345678','Chevy','Cobra','2002','Sedan',2,'123','0.75','453',CURDATE()),
		 ('17845678912345678','Honda','Viper','1432','Coupe',14,'21312','1','789',CURDATE()),
		 ('16645678912345678','Chevy','Kart','1000','Van',48,'121232','0.5','77777',CURDATE())
        ");		
		

   mysqli_close($cxn); 

echo "KTCS database created.";

?>
</body></html>