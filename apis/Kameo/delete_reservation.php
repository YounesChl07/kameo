<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$ID=$_POST['widget-deleteReservation-form-ID'];
$confirmation=$_POST['widget-deleteReservation-form-confirmation'];

if($confirmation!="DELETE"){
    errorMessage("ES0028");
}

$response=array();

if($ID != NULL)
{

    include 'connexion.php';
	$sql="select * from reservations WHERE ID = '$ID'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $conn->close();

    $dateEnd=$row['DATE_END_2'];
    $buildingStart=$row['BUILDING_START'];
    $buildingEnd=$row['BUILDING_END'];
    $bikeID=$row['BIKE_ID'];

    if($buildingStart!=$buildingEnd){
        include 'connexion.php';
        $sql="select * from reservations WHERE DATE_START_2>'$dateEnd' and BIKE_ID='$bikeID' and STAANN != 'D' ORDER BY DATE_START_2";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        if($length>0){
            errorMessage("ES0030");
        }
        $conn->close();


    }

    include 'connexion.php';
	$sql="update reservations set STAANN='D', USR_MAJ='mykameo', HEU_MAJ=CURRENT_TIMESTAMP WHERE ID = '$ID'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
    $conn->close();

    successMessage("SM0011");

}
else
{
	errorMessage("ES0012");
}

?>
