<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
</head>
<body>
    <form action="./MailController.php" method="post" enctype="multipart/form-data">
        <label for="to">Destinataire:</label>
        <input type="email" id="to" name="to" multiple="multiple">
        <label for="cc">Cc:</label>
        <input type="email" id="cc" name="cc" multiple="multiple">
        <label for="bcc">bcc:</label>
        <input type="email" id="bcc" name="bcc" multiple="multiple">
        <label for="subject">Objet:</label>
        <input type="text" id="subject" name="subject">
        <label for="message">Message:</label>
        <input type="textarea" rows="5" cols="33" id="message" name="message">
        <label for="file">Pièce jointe:</label>
        <input type="file" id="file" name="file[]" multiple="multiple">
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
