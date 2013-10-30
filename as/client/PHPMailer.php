<?php
/**
 * @author Marcelo Heredia
 * Oct, 2013
*/

/**
* Esta funcion llama a la libreria PHPMailer e evia el pdf como adjunto
*
* @param string $to a quien se envia el email
* @param array $bcc mails a los que se enviaran copias
* @param string $mensaje mensaje que ira en el cuerpo del email
* @param string $asunto asunto del email
* @param string $pdf direccion fisica del pdf para ser adjuntado
* @return int 0 si no ocurrio ningun error
*/
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

