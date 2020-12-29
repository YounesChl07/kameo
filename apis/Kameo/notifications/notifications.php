<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

require_once '../globalfunctions.php';
require_once '../authentication.php';
require_once '../connexion.php';

$token = getBearerToken();

switch($_SERVER["REQUEST_METHOD"])
{
	case 'GET':
		$action=isset($_GET['action']) ? $_GET['action'] : NULL;

		if($action === 'retrieveNotifications'){
			if(get_user_permissions(["search", "order","chatsManager","admin", 'fleetManager', 'personnalBike'], $token)){
				$stmt = $conn->prepare("SELECT ID FROM customer_referential WHERE TOKEN = ?");
				$stmt->bind_param("s", $token);
				if ($stmt->execute())
				{
					$id = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['ID'];
					$stmt->close();
					if(get_user_permissions("chatsManager", $token))
						$result = $conn->query("SELECT * FROM notifications WHERE (USER_ID = '".$id."' OR USER_ID = 0) AND `READ` = 'N' AND (STAAN <> 'D' OR STAAN IS NULL) ORDER BY DATE");
					else
						$result = $conn->query("SELECT * FROM notifications WHERE USER_ID = '".$id."' AND `READ` = 'N' AND (STAAN <> 'D' OR STAAN IS NULL) ORDER BY DATE");
					$response=array();
					if ($result && $result->num_rows>0) {
						$notifications = $result->fetch_all(MYSQLI_ASSOC);
						$response['response']="success";
						$response['notificationsNumber']=count($notifications);
						$response['notification']=$notifications;
						foreach($response['notification'] as $index=>$notification){
							if($notification['TYPE']=='lateBooking'){
								$reservationID=$notification['TYPE_ITEM'];
								$informations = execSQL("SELECT aa.DATE_START_2, aa.BIKE_ID, aa.DATE_END_2, bb.MODEL from reservations aa, customer_bikes bb WHERE aa.ID='$reservationID' AND  aa.BIKE_ID=bb.ID", array(), false);
								$response['notification'][$index]['start']=$informations[0]['DATE_START_2'];
								$response['notification'][$index]['end']=$informations[0]['DATE_END_2'];
								$response['notification'][$index]['model']=$informations[0]['MODEL'];
								$bikeID=$informations[0]['BIKE_ID'];
								$dateEnd=$informations[0]['DATE_END_2'];
								$nextBooking = execSQL("SELECT aa.DATE_START_2 from reservations aa WHERE aa.BIKE_ID='$bikeID' AND  aa.DATE_START_2>'$dateEnd'", array(), false);
								if($nextBooking != NULL){
									$response['notification'][$index]['nextBookingStart']=$nextBooking[0]['DATE_START_2'];
								}
							}
						}
					}else {
						$response['response']="success";
						$response['notificationsNumber']=0;
					}
					echo json_encode($response);
				}else
					echo json_encode(array ('response'=>'error'));
			}else
				error_message('403');
		}else
				error_message('405');
		break;
	case 'POST':
		$action=isset($_POST['action']) ? $_POST['action'] : NULL;
			error_message('405');
	break;
	default:
			error_message('405');
		break;
}

$conn->close();
?>
