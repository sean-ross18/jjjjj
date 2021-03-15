<?php
function add_customer_booking($name, $phone, $email, $date)
{
	$con = mysqli_connect("localhost", "root", "", "test");
 
	if($con === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	
	$aeskey = '4ldetn43t4aed0ho10smhd1l';
	try {
		$sql = "INSERT INTO bookings (name, phone, email, date) VALUES (?, AES_ENCRYPT(?,?), AES_ENCRYPT(?,?),?)";
		if($stmt = mysqli_prepare($con, $sql)){
			mysqli_stmt_bind_param($stmt, "ssssss", $name, $phone,$aeskey, $email, $aeskey, $date);
			mysqli_stmt_execute($stmt);
		} else{
			echo "ERROR: Could not prepare query: $sql. " . mysqli_error($con);
		}
		
		mysqli_stmt_close($stmt);
 
		mysqli_close($con);
	}
	catch( mysqli_sql_exception $e )
	{
		echo $e->getMessage();
	}
}
?>