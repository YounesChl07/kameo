<?php
header('Content-type: application/json');
header('WWW-Authenticate: Bearer');
header('Expires: ' . gmdate('r', 0));
header('HTTP/1.0 200 Ok');
header_remove("Set-Cookie");
header_remove("X-Powered-By");
header_remove("Content-Security-Policy");

include_once 'globalfunctions.php';
require_once 'authentication.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/include/lang_management.php'; //french by defaut, as many files as wanted can be added to the array

$token = getBearerToken();
log_inputs($token);

if(isset($_POST['action'])){

    $action=isset($_POST['action']) ? $_POST['action'] : NULL;
    $email=isset($_POST['email']) ? $_POST['email'] : NULL;
    $company=isset($_POST['company']) ? $_POST['company'] : NULL;
    $mail=isset($_POST['mail']) ? $_POST['mail'] : NULL;
    $ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
    $status=isset($_POST['status']) ? $_POST['status'] : NULL;
    $portfolioID=isset($_POST['portfolioID']) ? $_POST['portfolioID'] : NULL;
    $size=isset($_POST['size']) ? $_POST['size'] : NULL;
    $testBoolean=isset($_POST['testBoolean']) ? "Y" : "N";
    $testDate=isset($_POST['testDate']) ? ($_POST['testDate'] == "" ? NULL : $_POST['testDate']) : NULL;
    $testStatus=isset($_POST['testStatus']) ? $_POST['testStatus'] : NULL;
    $testAddress=isset($_POST['testAddress']) ? addslashes($_POST['testAddress']) : NULL;
    $testResult=isset($_POST['testResult']) ? addslashes($_POST['testResult']) : NULL;
    $deliveryDate=isset($_POST['deliveryDate']) ? ($_POST['deliveryDate'] == "" ? NULL : $_POST['deliveryDate']) : NULL;
    $deliveryAddress=isset($_POST['deliveryAddress']) ? addslashes($_POST['deliveryAddress']) : NULL;
    $price=isset($_POST['price']) ? $_POST['price'] : NULL;
    $type=isset($_POST['type']) ? $_POST['type'] : "leasing";
    $addAccessory=isset($_POST['glyphicon-plus']) ? ($_POST['glyphicon-plus']) : NULL;
    $deleteAccessory=isset($_POST['glyphicon-minus']) ? ($_POST['glyphicon-minus']) : NULL;
    $accessoriesNumber=isset($_POST['accessoriesNumber']) ? $_POST['accessoriesNumber'] : NULL;
    $categoryAccessory=isset($_POST['accessoryCategory']) ? $_POST['accessoryCategory'] : NULL;
    $typeAccessory=isset($_POST['accessoryAccessory']) ? $_POST['accessoryAccessory'] : NULL;
    $buyingPrice=isset($_POST['buyingPriceAcc']) ? $_POST['buyingPriceAcc'] : NULL;
    $sellingPrice=isset($_POST['sellingPriceAcc']) ? $_POST['sellingPriceAcc'] : NULL;

    if($action=='add'){
        if(!get_user_permissions("admin", $token)){
          error_message('403');
        }
        include 'connexion.php';
        $stmt = $conn->prepare("INSERT INTO client_orders (HEU_MAJ, USR_MAJ, EMAIL, PORTFOLIO_ID, SIZE, STATUS, TEST_BOOLEAN, TEST_DATE, TEST_ADDRESS, TEST_STATUS, TEST_RESULT, ESTIMATED_DELIVERY_DATE, DELIVERY_ADDRESS, LEASING_PRICE, TYPE, COMPANY)
        VALUES(CURRENT_TIMESTAMP, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt)
        {
          $stmt->bind_param("ssisssssssssdsi", $email, $mail, $portfolioID, $size, $status, $testBoolean, $testDate, $testAddress, $testStatus, $testResult, $deliveryDate, $deliveryAddress, $price, $type, $company );
          $stmt->execute();
          $response['response']="success";
          $stmt->close();
        } else
              error_message('500', 'Error occured while changing data');

        $conn->close();

        successMessage("SM0003");
    }else if($action=='update'){
        if($deliveryAddress!=NULL){
            $deliveryAddress="'".$deliveryAddress."'";
        }else{
            $deliveryAddress='NULL';
        }

        include 'connexion.php';
        $sql= "UPDATE client_orders  SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$email', STATUS='$status', PORTFOLIO_ID='$portfolioID', SIZE='$size', DELIVERY_ADDRESS=$deliveryAddress, LEASING_PRICE='$price', TYPE='$type' WHERE ID='$ID'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();

        if($deliveryDate != NULL){
            include 'connexion.php';
            $sql= "UPDATE client_orders  SET ESTIMATED_DELIVERY_DATE='$deliveryDate' WHERE ID='$ID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }

        if($testBoolean=="Y"){
            include 'connexion.php';

            if($testDate!=NULL){
                $testDate="'".$testDate."'";
            }else{
                $testDate='NULL';
            }
            if($testAddress!=NULL){
                $testAddress="'".$testAddress."'";
            }else{
                $testAddress='NULL';
            }
            if($testResult!=NULL){
                $testResult="'".$testResult."'";
            }else{
                $testResult='NULL';
            }
            if($deliveryAddress!=NULL){
                $deliveryAddress="'".$deliveryAddress."'";
            }else{
                $deliveryAddress='NULL';
            }

            $sql= "UPDATE client_orders  SET TEST_BOOLEAN='Y', TEST_DATE=$testDate, TEST_ADDRESS=$testAddress, TEST_RESULT=$testResult WHERE ID='$ID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }

        if(isset($_POST['accessoryCategory']) && isset($_POST['accessoryAccessory']))
        {
            include 'connexion.php';

            foreach( $categoryAccessory as $index => $categoryAccessory)
            {
                $category = $categoryAccessory;
                $accessory = $typeAccessory[$index];
                $buyingP = $buyingPrice[$index];
                $sellingP = $sellingPrice[$index];
                $sql2 = "INSERT INTO order_accessories(BRAND, CATEGORY, BUYING_PRICE, PRICE_HTVA, DESCRIPTION, ORDER_ID) VALUES ('$accessory', '$category', '$buyingP', '$sellingP', '//', '$ID')";

                if ($conn->query($sql2) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
            }

            $conn->close();
        }
        successMessage("SM0003");
    }else if($action=="confirmCommand"){
        if(!get_user_permissions("fleetManager", $token)){
          error_message('403');
        }
        include 'connexion.php';
        $ID=isset($_POST['ID']) ? $conn->real_escape_string($_POST['ID']) : NULL;
        $stmt = $conn->prepare("UPDATE client_orders SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ= ?, STATUS='confirmed' WHERE ID=?");

        if (!$stmt->bind_param("si", $token, $ID)) {
            $response = array ('response'=>'error', 'message'=> "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode($response);
            die;
        }

        if (!$stmt->execute()) {
            $response = array ('response'=>'error', 'message'=> "Echec lors de l'exécution : (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode($response);
            die;
        }

        $stmt->close();
        $response = array ('response'=>'success', 'message'=> L::successMessages_orderConfirmation);
        echo json_encode($response);
        die;

    }else if($action=="refuse"){
        if(!get_user_permissions("fleetManager", $token)){
          error_message('403');
        }

        include 'connexion.php';
        $ID=isset($_POST['ID']) ? $conn->real_escape_string($_POST['ID']) : NULL;
        $reasonOfRefusal=isset($_POST['reasonOfRefusal']) ? $conn->real_escape_string($_POST['reasonOfRefusal']) : NULL;

        $stmt = $conn->prepare("update client_orders set STATUS='refused', REMARK  = CONCAT(REMARK, 'Refusé par votre fleet manager pour la raison suivante: $reasonOfRefusal <br>') WHERE ID=?");

        if (!$stmt->bind_param("i", $ID)) {
            $response = array ('response'=>'error', 'message'=> "Echec lors du liage des paramètres : (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode($response);
            die;
        }

        if (!$stmt->execute()) {
            $response = array ('response'=>'error', 'message'=> "Echec lors de l'exécution : (" . $stmt->errno . ") " . $stmt->error);
            echo json_encode($response);
            die;
        }

        $stmt->close();
        $response = array ('response'=>'success', 'message'=> L::successMessages_orderRefusalConfirmation);
        echo json_encode($response);
        die;
    }else if($action=="delete"){
    }


}else if(isset($_GET['action'])){

    $action=isset($_GET['action']) ? $_GET['action'] : NULL;

    if($action=='list'){
		if(get_user_permissions(["admin", "fleetManager"], $token)){

            include 'connexion.php';
            $sql="SELECT * FROM customer_referential WHERE TOKEN='$token'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat=mysqli_fetch_assoc($result);
            $company=$resultat['COMPANY'];

            if($company=="KAMEO"){
                $sql= "SELECT *  FROM client_orders ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
            }else{
                $sql="SELECT co.* FROM client_orders co, customer_referential cr WHERE cr.COMPANY='$company' AND cr.EMAIL=co.EMAIL ORDER BY CASE STATUS WHEN 'new' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'closed' THEN 3 ELSE 5 END, id DESC";
            }

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result2 = mysqli_query($conn, $sql);
            $length = $result2->num_rows;
            $conn->close();
            $response=array();
            $response['response']="success";
            $response['ordersNumber']=$length;
            $i=0;

            while($row = mysqli_fetch_array($result2)){
                $emailCustomer=$row['EMAIL'];
                $response['order'][$i]['ID']=$row['ID'];
                $response['order'][$i]['size']=$row['SIZE'];
                $response['order'][$i]['status']=$row['STATUS'];
                $response['order'][$i]['estimatedDeliveryDate']=$row['ESTIMATED_DELIVERY_DATE'];
                $response['order'][$i]['testStatus']=$row['TEST_STATUS'];
                $response['order'][$i]['testDate']=$row['TEST_DATE'];
                $response['order'][$i]['testBoolean']=$row['TEST_BOOLEAN'];
                $response['order'][$i]['price']=$row['LEASING_PRICE'];
                $response['order'][$i]['type']=$row['TYPE'];


                $portfolioID=$row['PORTFOLIO_ID'];
                include 'connexion.php';
                $sql= "SELECT * FROM bike_catalog WHERE ID='$portfolioID'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $resultat=mysqli_fetch_assoc($result);
                $response['order'][$i]['brand']=$resultat['BRAND'];
                $response['order'][$i]['model']=$resultat['MODEL'];
                $priceHTVA=$resultat['PRICE_HTVA'];


                $companyID=$row['COMPANY'];
                $sql="select COMPANY_NAME FROM companies WHERE ID='$companyID'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);
                $resultat=mysqli_fetch_assoc($result);
                $response['order'][$i]['companyID']=$companyID;
                $response['order'][$i]['companyName']=$resultat['COMPANY_NAME'];

                $emailUser=$row['EMAIL'];
                if($emailUser != ""){
                  include 'connexion.php';
                  $sql= "SELECT * FROM customer_referential WHERE EMAIL='$emailUser'";
                  if ($conn->query($sql) === FALSE) {
                      $response = array ('response'=>'error', 'message'=> $conn->error);
                      echo json_encode($response);
                      die;
                  }
                  $result = mysqli_query($conn, $sql);
                  $resultat=mysqli_fetch_assoc($result);
                  $response['order'][$i]['user']=$resultat['PRENOM']." ".$resultat['NOM'];
                }else{
                  $response['order'][$i]['user']="N/A";
                }
                $i++;
            }
        }
        log_output($response);
        echo json_encode($response);
        die;

    }else if($action=='retrieve'){
        $ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

        include 'connexion.php';
        $sql= "SELECT * FROM client_orders WHERE ID='$ID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();
        $response=array();
        $email = $resultat['EMAIL'];
        $response['response']="success";
        $response['order']['ID']=$resultat['ID'];
        $response['order']['email']=$email;
        $response['order']['size']=$resultat['SIZE'];
        $response['order']['status']=$resultat['STATUS'];
        $response['order']['testBoolean']=$resultat['TEST_BOOLEAN'];
        $response['order']['testDate']=$resultat['TEST_DATE'];
        $response['order']['testAddress']=$resultat['TEST_ADDRESS'];
        $response['order']['testStatus']=$resultat['TEST_STATUS'];
        $response['order']['testResult']=$resultat['TEST_RESULT'];
        $response['order']['price']=$resultat['LEASING_PRICE'];
        $response['order']['type']=$resultat['TYPE'];
        $response['order']['comment']=br2nl($resultat['REMARK']);

        $email=$resultat['EMAIL'];

        $portfolioID=$resultat['PORTFOLIO_ID'];

        include 'connexion.php';
        $sql= "SELECT * FROM bike_catalog WHERE ID='$portfolioID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $response['order']['portfolioID']=$portfolioID;
        $response['order']['brand']=$resultat['BRAND'];
        $response['order']['model']=$resultat['MODEL'];
        $response['order']['frameType']=$resultat['FRAME_TYPE'];
        $priceHTVA=$resultat['PRICE_HTVA'];


        $sql= "SELECT * FROM customer_referential WHERE EMAIL='$email'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $company=$resultat['COMPANY'];
        $response['order']['name']=$resultat['NOM'];
        $response['order']['firstname']=$resultat['PRENOM'];
        $response['order']['phone']=$resultat['PHONE'];
        $response['order']['priceHTVA']=$priceHTVA;


        $sql= "SELECT *, order_accessories.ID as orderID FROM order_accessories INNER JOIN accessories_categories ON order_accessories.CATEGORY = accessories_categories.ID INNER JOIN accessories_catalog ON accessories_catalog.ACCESSORIES_CATEGORIES = accessories_categories.ID WHERE order_accessories.ORDER_ID='$ID' AND accessories_catalog.ID=order_accessories.BRAND";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);

        $i=0;

        while($resultat = mysqli_fetch_array($result)){
            $response['order'][$i]['typeAccessory']=$resultat['MODEL'];
            $response['order'][$i]['aCategory']=$resultat['CATEGORY'];
            $response['order'][$i]['aBuyingPrice']=$resultat['BUYING_PRICE'];
            $response['order'][$i]['aPriceHTVA']=$resultat['PRICE_HTVA'];
            $response['order'][$i]['accessoryID']=$resultat['orderID'];
            $i++;
        }
        $response['accessoryNumber']=$i;
        $result = mysqli_query($conn, $sql);

        echo json_encode($response);
        die;
    }

    if($action=='delete')
    {
        include 'connexion.php';
        $ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

        $sql = "DELETE FROM order_accessories WHERE ID='$ID'";

        if ($conn->query($sql) === FALSE) {

            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $response = array ('response'=>'success', 'message'=> "Successfully Deleted!");

        echo json_encode($response);
        die;
    }
}
else{
    errorMessage("ES0012");
}


?>
