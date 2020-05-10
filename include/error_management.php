<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';


if(isset($_GET['action'])){
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;
    $item=isset($_GET['item']) ? $_GET['item'] : NULL;
    
    if($action=="list"){
        if($item=="bikes"){
            
            include 'connexion.php';
            $sql="SELECT * FROM customer_bikes WHERE STAANN != 'D'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();

            $dossier="../images_bikes/";
            $response=array();
            $i=0;

            while($row = mysqli_fetch_array($result)){
                $fichier=$row['FRAME_NUMBER'].'.jpg';
                $frameNumber=$row['FRAME_NUMBER'];
                $bikeID=$row['ID'];
                $fichierMini=$row['FRAME_NUMBER'].'_mini.jpg';

                if (!file_exists($dossier.$fichier) || !file_exists($dossier.$fichierMini)){                
                    $response['bike']['img'][$i]['id']=$bikeID;
                    $response['bike']['img'][$i]['frameNumber']=$frameNumber;
                    $response['bike']['img'][$i]['path']=$dossier.$fichier;

                    $i++;
                }
            }

            $response['bike']['img']['number']=$i;

            $response['response']="success";
            echo json_encode($response);
            die;

        }else if($item=='bills'){
            
            include 'connexion.php';
            $sql="SELECT * FROM customer_bikes aa WHERE COMPANY != 'KAMEO' AND CONTRACT_START != 'NULL' and STAANN != 'D' and (CONTRACT_TYPE = 'leasing' OR CONTRACT_TYPE = 'renting') and BILLING_TYPE != 'paid'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();

            $i=0;            

            while($row = mysqli_fetch_array($result)){
                
                
                $bikeID=$row['ID'];
                $bikeNumber=$row['FRAME_NUMBER'];
                $contractStart=new DateTime($row['CONTRACT_START']);
                $dateTemp=$contractStart;
                
                if($row['CONTRACT_TYPE']=='leasing'){
                    if($row['CONTRACT_END'] != NULL){
                        $contractEnd=new DateTime($row['CONTRACT_END']);
                        $now=new DateTime('now');
                        if($now<$contractEnd){
                            $contractEnd=$now;
                        }
                    }else{
                        $contractEnd=new DateTime('now');
                    }
                }else if($row['CONTRACT_TYPE']=='renting'){
                    
                    
                    if($row['CONTRACT_END'] != NULL){
                        $contractEnd=new DateTime($row['CONTRACT_END']);
                        $now=new DateTime('now');
                        if($now<$contractEnd){
                            $contractEnd=$now;
                        }
                    }else{
                        $contractEnd=new DateTime('now');
                    }
                    
                    
                    if($contractEnd->format('m')==1){
                        $monthBefore=12;
                        $yearBefore=(($contractEnd->format('Y'))-1);
                    }else{
                        $monthBefore=(($contractEnd->format('m'))-1);
                        $yearBefore=$contractEnd->format('Y');
                    }
                    $dayBefore=$contractEnd->format('d');

                    if(strlen($monthBefore)==1){
                        $monthBefore='0'.$monthBefore;
                    }
                    if(strlen($dayBefore)==1){
                        $dayBefore='0'.$dayBefore;
                    }
                    
                    $contractEnd=new DateTime($yearBefore.'-'.$monthBefore.'-'.$dayBefore);

                    
                    
                    
                }
                
                $day=$contractStart->format('d');
                $month=$contractStart->format('m');
                $year=$contractStart->format('Y');
                
                
                while($dateTemp<$contractEnd){
                    
                                        
                    $dateTempString=$dateTemp->format('d-m-Y');
                    $dateTempString2=$dateTemp->format('Y-m-d');
                    include 'connexion.php';
                    $sql="SELECT * FROM factures_details WHERE BIKE_ID='$bikeID' and DATE_START = '$dateTempString2'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $result2 = mysqli_query($conn, $sql);
                    $length = $result2->num_rows;
                    $conn->close();
                    
                    if($length == 0){
                        $response['bike']['bill'][$i]['ID']=$bikeID;
                        $response['bike']['bill'][$i]['sql']=$sql;
                        $response['bike']['bill'][$i]['bikeNumber']=$bikeNumber;
                        $response['bike']['bill'][$i]['description']="Facture manquante pour le vélo à la date du $dateTempString";
                        $i++;
                    }
                    
                    if($month=='12'){
                        $month='01';
                        $year++;   
                    }else{
                        $month++;
                    }
                    $dateTemp->setDate($year, $month, $day);                    
                }
                
                
            }
            

            $response['bike']['bill']['number']=$i;

            $response['response']="success";
            echo json_encode($response);
            die;
            
            
        }
        
    }
}else{
    errorMessage("ES0012");
}


?>
