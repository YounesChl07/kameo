<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$bookingID=$_POST['reservationID'];

$response=array();

if($bookingID != NULL)
{

	
    include 'connexion.php';
	$sql="SELECT *  FROM reservations aa, building_access bb WHERE aa.ID = '$bookingID' AND BUILDING_START=BUILDING_REFERENCE";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $row = mysqli_fetch_assoc($result);



    $response['response']="success";
    $response['reservationBikeNumber']=$row['FRAME_NUMBER'];
    $response['reservationStartDate']=date('d/m/Y H:i', $row['DATE_START']);            
    $response['reservationEndDate']=date('d/m/Y H:i', $row['DATE_END']);            
    $response['reservationStartBuilding']=$row['BUILDING_FR'];
    $response['reservationEndBuilding']=$row['BUILDING_FR'];  
    $response['reservationUser']=$row['EMAIL'];
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>