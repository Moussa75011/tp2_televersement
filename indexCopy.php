<?php

//Connexion a la base de donnée
require './bdd.php';

if(isset($_FILES['file'])){
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];


    
    //3) On va maintenant verifier les extention des image qu'on recoit
    //en utilisant la fonction explode() qui separe le nom de notre fichier et son
    //extension sous forme de tableau a chaque fois il retrouve un point/
    //Ainsi on recupere le dernier element de notre tableau avec la fonction
    //end() sur lequel on verifie si l'extension demandé est le bon.
    //la strtlower mettra toute les chaine de caractere en minuscule et dc une
    //image .jPg, jpg, Jpg ... sera accepeter


    //explode retourne le nom de l'image sous forme de tableau
    $tabExtension = explode('.', $name);
    //On recupere le denier elt du tableau et on le convertir en minuscule
    $extension = strtolower(end($tabExtension));

    //3) Tableau des extensions que l'on accepte 
    $extensions = ['jpg', 'png', 'jpeg', 'gif'];
    


    //3)Taille maximale en bytes que l'on accepte pour l'image
    $maxSize = 900000;

    //3) On test si le l'extention envoyé est != de celui accepté et si la taille <= a celui attendu
    // et si erreur ya, donc error == 0, on affiche un message
    if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){


    //4) Pour eviter que le nouveau fichier qui possede le meme nom que l'ancien
    // lors du televersement, on va la fonction uniqid() 
    //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
    $uniqueName = uniqid('', true);
    
    //et la variable file aura le nom changé comme ceci : $file = 5f586bf96dcd38.73540086.jpg
    $file = $uniqueName.".".$extension;

    


    //2) La fonction permet de televerser un fichier d'un dossier a un autre en 
    //indiquant en paramettre le chemin ou est telecharge le fichier  ou image
    //et celui ou il televersé

    move_uploaded_file($tmpName, './upload/'.$name);

    //On insere notre image dans notre table si elle respecte le condition(taille, l'extension...) 
    $req = $db->prepare('INSERT INTO file (name) VALUES (?)');
    $req->execute([$file]);

    echo "Image enregistrée";

    }
    else{
        //echo "Mauvaise extension ou taille trop grande";
         echo "Une erreur est survenue";
    }


}
?>


<!--- Formulaire pour le televersement --->

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

</head>
<body>
    <h2>Ajouter une image</h2>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <label for="file">Fichier</label>
        <input type="file" name="file">
        <button type="submit">Enregistrer</button>
    </form>

    <h2>Mes images</h2>
    <?php 
        $req = $db->query('SELECT name FROM file');
        while($data = $req->fetch()){
            echo "<img src='./upload/".$data['name']."' width='300px' ><br>";
        }
    ?>
</body>
</html>


 