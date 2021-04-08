<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>Formulaire</title>
</head>
<body>
    <div class="flex flex-col justify-center mt-12 lg:flex-row">
        <div class="lg:mt-48 pl-6 lg:pl-16 text-left">
            <h1 class="text-2xl lg:text-3xl font-semibold">Formulaire d'envoi de mail</h1>
            <p class="mt-2 lg:text-xl">Avec class PHP</p>
            <p class="lg:text-xl">Controller</p>
            <p class="lg:text-xl">Template pour la réception du mail</p>
            <p class="lg:text-xl">Tailwind</p>
            <p class="lg:text-xl">Responsive</p>
        </div>
        <div class="ml-6 mr-6 lg:w-1/3">
            <form action="./mail/MailController.php" method="post" enctype="multipart/form-data" class="mt-6 lg:border-l-2 lg:border-blue-100 h-full lg:pl-16">
                <label for="to">Destinataire(s) :</label>
                <input type="email" id="to" name="to" multiple="multiple" class="mb-2 rounded-sm px-4 py-3 mt-2 focus:outline-none bg-gray-100 w-full">
                <label for="cc">Cc :</label>
                <input type="email" id="cc" name="cc" multiple="multiple" class="mb-2 rounded-sm px-4 py-3 mt-2 focus:outline-none bg-gray-100 w-full">
                <label for="bcc">bcc :</label>
                <input type="email" id="bcc" name="bcc" multiple="multiple" class="mb-2 rounded-sm px-4 py-3 mt-2 focus:outline-none bg-gray-100 w-full">
                <label for="subject">Objet :</label>
                <input type="text" id="subject" name="subject" class="mb-2 rounded-sm px-4 py-3 mt-3 focus:outline-none bg-gray-100 w-full">
                <label for="msg">Message :</label>
                <textarea rows="2" cols="33" id="msg" name="msg" class="mb-2 rounded-sm px-4 py-3 mt-2 focus:outline-none bg-gray-100 w-full"></textarea>
                <label for="file">Pièce(s) jointe(s) :</label>
                <input type="file" id="file" name="file[]" multiple="multiple" class="mb-2 rounded-sm px-4 py-3 mt-2 focus:outline-none bg-gray-100 w-full">
                <button type="submit" class="mt-3 mb-6 block text-center text-white bg-blue-900 p-3 duration-300 rounded-sm hover:bg-blue-700 w-full">Envoyer</button>
            </form>
        </div>
    </div>
</body>
</html>
