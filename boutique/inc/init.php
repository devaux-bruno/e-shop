<?php

//date_default_timezone_set
date_default_timezone_set('Europe/Paris');

//Session
session_start();

//Connexion à la base de donnée

$pdo = new PDO('mysql:host=localhost;dbname=???', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
//PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING  ----- On définit le mode d'erreur en wrning pour le développement.
//PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ----- On initialise  la commande pour définir les jeux de caractère en UTF8.
//On peut donc définir plus de paramètres au lancement de la connexion à la BDD, exemple :
//ex: PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ------ Permet de mettre de définir un racourci par défault  pour la methode Fetch afin de gagner du temps, etc.....



// Définir la racine du site :
define('URL', '/boutique/'); //---- ici la constante URL partiront de localhost et passerons par le dossier boutique.
define('CODEMDP', 'HELLO');


//On crée des variables pour y introduire le contenu de notre site.
$contenu = '';
$contenu_gauche = '';
$contenu_droite = '';


//Inclusion du fichier des fonctions PHP pour ne l'avoir qu'une seul fois sur notre site.
require_once('functions.php'); // On n'inclu Obligatoirement qu'une seul fois le fichier.




