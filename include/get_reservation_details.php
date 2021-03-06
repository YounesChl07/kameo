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
	$sql="SELECT aa.FRAME_NUMBER, aa.EMAIL, aa.DATE_START, aa.DATE_END, bb.BUILDING_FR as building_start_fr, cc.BUILDING_FR as building_end_fr  FROM reservations aa, building_access bb, building_access cc WHERE aa.ID = '$bookingID' AND aa.BUILDING_START=bb.BUILDING_REFERENCE and aa.BUILDING_END=cc.BUILDING_REFERENCE";
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
    $response['reservationStartBuilding']=$row['building_start_fr'];
    $response['reservationEndBuilding']=$row['building_end_fr'];  
    $response['reservationEmail']=$row['EMAIL'];
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>