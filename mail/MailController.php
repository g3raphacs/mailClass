<?php
require_once "../vendor/autoload.php";
use Mail\MailObject;

//Parametres
$from = "fakeadress@mailtrap.io";
$fromName = "John Doe";
$template_file = "../template.php";
$template_data =  array(
    "{from}" => $from,
    "{fromName}" => $fromName,
    "{LINK}" => "https:google.fr",
    "{IMG}" => "https://images.unsplash.com/photo-1617444114429-fc5dd4bca329?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1",
);

//Nouvel object MailObject
$mail = new MailObject($data = array(
    "from" => $from,
    "fromName" => $fromName,
    "template_file"=> $template_file,
    "template_data"=> $template_data,
    "html"=> true,
    "maxFileSize"=> 1000000
));


//Envoi du mail
$sendmail = $mail->sendMail();


if(is_array($sendmail)){
    showExceptions($sendmail);
}else{
    if($sendmail){ 
        echo '<div style="color: white; background-color: DodgerBlue; margin-top: 10rem;text-align: center; width: 25%;font-size: 1.5rem; margin-right: auto;margin-left: auto;">mail envoyé</div>'; 
    }else{ 
        echo "le mail n'a pas été envoyé"; 
    }
}


//Afficher les exceptions
function showExceptions($exceptions){
    for($i = 0 ; $i<count($exceptions); $i++){
        echo '<div style="color: white; background-color: red; margin-top: 10rem;text-align: center; width: 25%;font-size: 1.5rem; margin-right: auto;margin-left: auto;">'.$exceptions[$i].'</div>'; 
    }
}
