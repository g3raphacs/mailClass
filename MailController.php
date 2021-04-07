<?php

include 'MailObject.php';

/**
 * Nouvel object MailObject
 */
$mail = new MailObject($data = array(
    "from" => "fakeadress@mailtrap.io",
    "fromName" => "John Doe",
    "to" => $_POST['to'],
    "subject"=> $_POST['subject'],
    "template_file"=> "./template.php",
    "template_data"=> array(
        "{SITE_ADDR}" => "https://dev-jm.fr",
        "{IMG}" => "https://images.unsplash.com/photo-1617444114429-fc5dd4bca329?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1"
    ),
    "cc" => $_POST['cc'],
    "bcc" => $_POST['bcc']
));

$mail = new MailObject();

$mail->hydrate($data = array(
    "from" => "salut@gmail.com"
));