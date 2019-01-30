<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();

include 'globalfunctions.php';




$user = $_POST['widget-new-booking-mail-customer'];
$frameNumber=$_POST['widget-new-booking-frame-number'];
$buildingStart=$_POST['widget-new-booking-building-start'];
$buildingEnd=$_POST['widget-new-booking-building-end'];
$timestampStart=$_POST['widget-new-booking-timestamp-start'];
$timestampEnd=$_POST['widget-new-booking-timestamp-end'];

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $frameNumber != NULL & $buildingStart != NULL && $buildingEnd != NULL && $timestampStart != NULL && $timestampEnd != NULL && $user!= NULL ) {

	include 'connexion.php';
    $sql= "select * from reservations aa where aa.STAANN!='D' and aa.FRAME_NUMBER = '$frameNumber' and not exists (select 1 from reservations bb where bb.STAANN!='D' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and ((bb.DATE_END > '$timestampStart' and bb.DATE_END < '$timestampEnd') OR (bb.DATE_START>'$timestampStart' and bb.DATE_START<'$timestampEnd')))";
   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);     
    $length = $result->num_rows;
	
	 if($length == 0){
        errorMessage("ES0019");
    }
	
	include 'connexion.php';
    
    $timestamp= time();
    $sql= "INSERT INTO reservations (HEU_MAJ, USR_MAJ, FRAME_NUMBER, DATE_START, BUILDING_START, DATE_END, BUILDING_END, EMAIl, STAANN) VALUES ('$timestamp','new_booking', '$frameNumber', '$timestampStart', '$buildingStart', '$timestampEnd', '$buildingEnd', '$user', '')";

   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	} 
    $conn->close();
    successMessage("SM0006");
} else{
	errorMessage("ES0012");
}
?>