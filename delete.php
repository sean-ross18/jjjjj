<?php
function delete_old_bookings()
{
	$MonthAgo = date("Y-m-d",strtotime("-1 month"));
	$con = mysqli_connect("localhost", "root", "", "test");
 
	if($con === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	
	try {
		$sql = "SELECT id FROM bookings WHERE date < ?";
		
		if($stmt = mysqli_prepare($con, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $MonthAgo);
			mysqli_stmt_execute($stmt);
			if (!($res = $stmt->get_result())) {
				echo "Getting initial result set failed: (" . $stmt->errno . ") " . $stmt->error;
			} else {
				$idarray = array();
				while($row =mysqli_fetch_assoc($res))
				{
					$idarray[] = $row;
				}
				var_dump($idarray);
			
				for ($i = 0; $i < count($idarray); $i++){	
					$sql = "DELETE FROM bookings WHERE id=?";
					echo $idarray[$i]['id'];
					echo " ";
					if($stmt = mysqli_prepare($con, $sql)){
						mysqli_stmt_bind_param($stmt, "i", $idarray[$i]['id']);
						mysqli_stmt_execute($stmt);
					} else{
						echo "ERROR: Could not prepare query: $sql. " . mysqli_error($con);
					}
				}
				echo "old booking deleted";
			}
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