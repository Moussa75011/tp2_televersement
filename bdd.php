<?php
try{
    $db = new PDO('mysql:host=localhost;dbname=testing', 'root', "root");
}catch(PDOException $e){
    die('Erreur connexion : '.$e->getMessage());
}