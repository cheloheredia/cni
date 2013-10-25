<?php
function enviarpdf($to, $bcc, $mensaje, $asunto, $pdf){
	require_once("../phpmailer/class.phpmailer.php");
    $mail = new PHPMailer(); 
    $mail->IsSMTP();
    $mail->Host = "ssl://smtp.gmail.com";
    $mail->Port = 465;   
    $mail->SMTPAuth = true;
    $mail->Username = "marceloherediaa@gmail.com";
    $mail->Password = "herbalife";
    $webmaster_email = "webmaster@cnibolivia.com";
    $mail->From = $webmaster_email;
    $mail->FromName = "CNIBolivia";
    $mail->AddAddress($to);
    if (sizeof($bcc) > 0) {
        for ($i = 0; $i < sizeof($bcc); $i++) { 
            $mail->AddCC($bcc[$i]);
        }
    }
    $mail->AddReplyTo($webmaster_email,"CNIBolivia");
    $mail->WordWrap = 50;
    $mail->AddAttachment($pdf);
    $mail->IsHTML(true);
    $mail->Subject = $asunto;
    $mail->Body = $mensaje;
    $mail->AltBody = $mensaje;
    if(!$mail->Send()){
        return 1;
    } else {
        return 0;
    }
}

