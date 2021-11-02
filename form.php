<?php
//variables et gestion d'erreurs
$errors = [];
$formOk = false;
$uploadFile = "";
    if($_SERVER["REQUEST_METHOD"] === "POST" ){   // verifs formulaire soumis
        if (empty($_POST['uploadFile']))
        // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés
        $uploadDir = 'public/uploads/';
        // recupération de l'extension du fichier
        $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        //nommage de l'image
        $uploadFile = $uploadDir . uniqid() . '.' . $extension;
        //extensions autorisées
        $authorizedExtensions = ['jpg','png', 'gif','webp'];
        //poid maxi autorisé pour l'image
        $maxFileSize = 1000000;

 // Je sécurise et effectue mes tests

    /****** Si l'extension est autorisée *************/
    if( (!in_array($extension, $authorizedExtensions))){
        $errors[] = 'Veuillez sélectionner une image de type Jpg, Png, gif ou webp !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if( file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize)
    {
    $errors[] = "Votre fichier doit faire moins de 1Mo !";
    }

    /****** Si je n'ai pas d"erreur alors j'upload *************/
    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile); 

// verifications des données du formulaire
    $profil = array_map('trim', $_POST);
    if (empty($profil['firstname'])) {
        $errors[] = 'Votre prénom est obligatoire';
    }
    if (empty($profil['lastname'])) {
        $errors[] = 'Votre nom est obligatoire';
    }
    if (empty($profil['age'])) {
        $errors[] = 'Votre age est obligatoire';
    } 
    $formOK = empty($errors);
    }
    //si l'image existe on l'upload

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Upload file</title>
</head>
<body>
<h1>Complèter votre profil</h1>
    <form action="form.php" method="post" enctype="multipart/form-data">
    <?php
            if ($formOk){
                echo "<input type='hidden' name='uploadFile' id='uploadFile' value='".$uploadFile."'/>"; //mettre dans le form l'image précedemment chargée pour la supprimer au Post
                echo "<section class='profil'>";
                echo "<img src='" .$uploadFile . "' alt=''>";
                $button = "Supprimez votre photo";
                
            } else {
                echo "<ul>" . PHP_EOL;
                    foreach($errors as $error){
                echo "<li>$error</li>" . PHP_EOL;
                }
                echo "</ul>" . PHP_EOL;
            }
            if (empty($uploadFile))  
            ?>
            <!-- formulaire --> 
            <label for="firstname">Votre prénom :</label>
            <input type="text" name="firstname" id="firstname">

            <label for="lastname">Votre nom :</label>
            <input type="text" name="lastname" id="lastname">

            <label for="age">Votre age :</label>
            <input type="number" name="age" id="age">

            <label for="imageUpload">Insérez votre photo de profil</label>    
            <input type="file" name="avatar" id="imageUpload">
            
            <?php $button = "Envoyer"; ?>
            <input type="submit" value="<?= $button ?>">   
    </form>
</body>
</html>