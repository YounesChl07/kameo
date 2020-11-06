<?php
session_cache_limiter('nocache');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: application/json');
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

try{
    if(isset($_GET['action'])){
        $action=isset($_GET['action']) ? $_GET['action'] : NULL;
        $comingFrom=isset($_GET['comingFrom']) ? $_GET['comingFrom'] : NULL;
        $ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;

        if($action=="list"){

            $response=array();
            $marginBike=0.7;
            $marginOther=0.3;
            $leasingDuration=36;

            $revenuEmployee=3000;
            $frequenceBikePerWeek=3;
            $distanceBike=5000;
            $type="employé";
            $prime=1;

            if($revenuEmployee<636.49){
                $taxRate=0;
            }else if($revenuEmployee>=636.49 && $revenuEmployee < 951.87){
                $taxeRate=(($revenuEmployee-636.49)*0.25)/$revenuEmployee;
            }else if($revenuEmployee >= 951.87 && $revenuEmployee < 1680.32){
                $taxRate=(($revenuEmployee-951.87)*0.4)/$revenuEmployee;
            }else if($revenuEmployee >= 1680.32 && $revenuEmployee < 2908.05){
                $taxRate=(($revenuEmployee-1949.17)*0.45 + (1680.32-951.87)*0.4)/$revenuEmployee;
            }else{
                $taxRate=(($revenuEmployee-2908.05)*0.5 + (2908.05-1680.32)*0.44 + (1680.32-951.87)*0.4+ (951.87-636.49)*0.4)/$revenuEmployee;
            }


            if($prime=='0'){
                $impactBikeAllowance=0;
            }else{
                $primeForBike=0.24;
                $impactBikeAllowance=($primeForBike*$frequenceBikePerWeek*2*$distanceBike/1000*4);
            }



            include 'connexion.php';

            if($comingFrom)
            {
                $sql="SELECT ID as ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyPrice, PRICE_HTVA as price, LINK as url, (round((PRICE_HTVA*(1-0.27)*(1+?)+(3*84+4*100)*(1+?))/?)) as leasingPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, LICENSE as license FROM bike_catalog WHERE STAANN != 'D' ORDER BY STOCK DESC, MODEL";
            }
            else
            {
                $sql="SELECT ID as ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyPrice, PRICE_HTVA as price, LINK as url, (round((PRICE_HTVA*(1-0.27)*(1+?)+(3*84+4*100)*(1+?))/?)) as leasingPrice, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission FROM bike_catalog WHERE STAANN != 'D' ORDER BY BRAND, MODEL";
            }

            $stmt = $conn->prepare($sql);
            if($stmt){
                $stmt->bind_param('ddi', $marginBike, $marginOther, $leasingDuration);
                $stmt->execute();
                $response['response']="success";
                $bikes = ($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
                $response['bikeNumber']=count($bikes);
                foreach($bikes as $index=>$bike){

                  if($type=='ouvrier'){
                      $impactOnGrossSalary=($bike['leasingPrice']*12/17.58);
                  }else{
                      $impactOnGrossSalary=($bike['leasingPrice']*12/18.08);
                  }

                  $socialCotisation=$impactOnGrossSalary*0.1307;
                  $basisForTaxes=$impactOnGrossSalary-$socialCotisation;

                  $taxes=$basisForTaxes*$taxRate;
                  $impactOnNetSalary=($basisForTaxes-$taxes);

                  $bikes[$index]["realImpact"]=($impactOnNetSalary-$impactBikeAllowance);
                }


                $response['bike']=($bikes);
                echo json_encode($response);
            }else{
                error_message('500', 'Unable to retrieve portfolio bikes');
            }
            $stmt->close();
            $conn->close();
            die;

        }
        if($action=="retrieve"){
            include 'connexion.php';
            $sql="SELECT ID, BRAND as brand, MODEL as model, FRAME_TYPE as frameType, UTILISATION as utilisation, ELECTRIC as electric, STOCK as stock, DISPLAY as display, BUYING_PRICE as buyingPrice, PRICE_HTVA as portfolioPrice, LINK as url, MOTOR as motor, BATTERY as battery, TRANSMISSION as transmission, LICENSE as license FROM bike_catalog WHERE ID='$ID'";
            $stmt = $conn->prepare($sql);
            if($stmt){
                //$stmt->bind_param('ffi', $marginBike, $marginOther, $leasingDuration);
                $stmt->execute();
                $response = array("response" => "success");
                $response = array_merge($response,$stmt->get_result()->fetch_array(MYSQLI_ASSOC));
            }else{
                error_message('500', 'Unable to retrieve portfolio bike');
            }
            $stmt->close();
            $conn->close();

            $response['img']=strtolower(str_replace(" ", "-", $response['brand']))."_".strtolower(str_replace(" ", "-", $response['model']))."_".strtolower($response['frameType']);
			echo json_encode($response);
        }
    }else{
        errorMessage("ES0012");
    }

}  catch (Exception $e) {
    $response['response']="error";
    $response['message']=$e->getMessage();
    echo json_encode($response);
    die;

}



?>
