<?php
include 'globalfunctions.php';

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

require_once('php-mailer/PHPMailerAutoload.php');
$mail = new PHPMailer();



// Form Fields
$name = $_POST["widget-contact-form-name"];
$firstName = $_POST["widget-contact-form-firstName"];


$email = $_POST["widget-contact-form-email"];
$phone = isset($_POST["widget-contact-form-phone"]) ? $_POST["widget-contact-form-phone"] : null;
$subject = isset($_POST["widget-contact-form-subject"]) ? $_POST["widget-contact-form-subject"] : 'Nouveau message - Inscription';
$home_adress = $_POST["widget-contact-form-home_adress"];
$work_adress = $_POST["widget-contact-form-work_adress"];
$antispam = $_POST['widget-contact-form-antispam'];
$captcha = strlen($_POST['g-recaptcha-response']);


$length = strlen($phone);
if ($length<8 or $length>12) {
	errorMessage(ES0004);
}

if($captcha == 0){
    errorMessage(ES0020);
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($antispam) && $antispam == '') {
    
 if($email != '' && $message != '') {
            
                //If you don't receive the email, enable and configure these parameters below: 
     
                //$mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->Host = 'mail.yourserver.com';                  // Specify main and backup SMTP servers, example: smtp1.example.com;smtp2.example.com
                //$mail->SMTPAuth = true;                               // Enable SMTP authentication
                //$mail->Username = 'SMTP username';                    // SMTP username
                //$mail->Password = 'SMTP password';                    // SMTP password
                //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                //$mail->Port = 587;                                    // TCP port to connect to 
     
     	        $mail->IsHTML(true);                                    // Set email format to HTML
                $mail->CharSet = 'UTF-8';
     			
				//$mail->AddAddress('thibaut.mativa@kameobikes.com', 'Thibaut Mativa');
				$mail->AddAddress('thibaut.mativa@gmail.com', 'Thibaut Mativa');
				//$mail->AddAddress('julien.jamar@kameobikes.com', 'Julien Jamar');
				//$mail->AddAddress('antoine.lust@kameobikes.com', 'Antoine Lust');
				//$mail->AddAddress('pierre-yves.adant@kameobikes.com', 'Pierre-Yves Adant');

                $mail->From = $email;
                $mail->FromName = $firstName.' '.$name;
                $mail->AddReplyTo($email, $name);
                $mail->Subject = $subject;
          
                $name = isset($name) ? "Nom: $name<br><br>" : '';
				$firstName = isset($firstName) ? "Prenom: $firstName<br><br>" : '';
                $email = isset($email) ? "Email: $email<br><br>" : '';
                $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
                $home_adress = isset($home_adress) ? "Adresse domicile: $home_adress <br><br>" : '';
                $work_adress = isset($work_adress) ? "Adresse du travail: $work_adress <br><br>" : '';

                $mail->Body = $name . $firstName . $email . $phone . $home_adress . $work_adress;
     
                         
        if(!$mail->Send()) {
		   $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
            
		}else {
           $response = array ('response'=>'success');  
        }
     echo json_encode($response);

} else {
	$response = array ('response'=>'error');     
	echo json_encode($response);
}
    
}
?>
