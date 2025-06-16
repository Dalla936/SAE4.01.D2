<?php
$dbhost = 'localhost'; //url de l'host
$dbname= 'site_jeux'; //name of the database
$dbuser = "postgres"; // username
$dbpass= "postgres"; //mdp

$connection = new PDO('pgsql:host='.$dbhost.";dbname=".$dbname, $dbuser, $dbpass); // pr se connecter à la bd 

$requete = $connection->prepare('SELECT count(*) FROM jeux;');
$requete->execute();
?>