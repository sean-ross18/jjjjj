<?php
function get_customer_list($date)
{
	$con = mysqli_connect("localhost", "root", "", "test");
 
	if($con === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	$aeskey = '4ldetn43t4aed0ho10smhd1l';
	try {
		$sql = "SELECT id, name, AES_DECRYPT(phone, ?), AES_DECRYPT(email, ?), date FROM bookings WHERE date = ?";
		
		if($stmt = mysqli_prepare($con, $sql)){
			mysqli_stmt_bind_param($stmt, "sss", $aeskey,$aeskey,$date);
			mysqli_stmt_execute($stmt);
			if (!($res = $stmt->get_result())) {
				echo "Getting result set failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			$bookarray = array();
			while($row =mysqli_fetch_assoc($res))
			{
				$bookarray[] = $row;
			}
			$json_bookings = json_encode($bookarray);
			return $json_bookings;
			
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