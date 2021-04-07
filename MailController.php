<?php

include 'MailObject.php';

//Parametres
$from = "fakeadress@mailtrap.io";
$fromName = "John Doe";
$template_file = "./template.php";
$template_data =  array(
    "{from}" => $from,
    "{fromName}" => $fromName,
    "{LINK}" => "https://dev-jm.fr",
    "{IMG}" => "https://images.unsplash.com/photo-1617444114429-fc5dd4bca329?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1",
);

//Nouvel object MailObject
$mail = new MailObject($data = array(
    "from" => $from,
    "fromName" => $fromName,
    "template_file"=> $template_file,
    "template_data"=> $template_data,
    "html"=> true,
));


//Envoi du mail
$sendmail = $mail->sendMail();

if($sendmail){ 
    echo 'mail envoyé'; 
}else{ 
    echo "echec"; 
}