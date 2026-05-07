<?php
//  exit("L'intégralité des sites Neamar est en maintenance pour quelques minutes. Nous serons de retour pour 15h !");
error_reporting(E_ALL ^ E_DEPRECATED);

$url = parse_url(getenv("DATABASE_URL"));
//Fichier assez utile :-)
mysql_connect($url["host"] . ":" . $url["port"], $url["user"], $url["pass"]); // Connexion à MySQL légèrement sécurisée.
mysql_select_db("omnilogie"); // Sélection de la base de données
mysql_query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))")

/**
 * Une note à propos de l'encodage.
 * À l'origine, le code était en latin1, les données en latin1.
 * J'ai migré le code en utf8.
 * Cependant, les données de la base de données sont toujours encodées en latin1, et mysql convertit automatiquement en utf8 à la volée (le charset par défaut de la connexion est utf8mb4).
 * Seul souci : maintenant que le site est en utf8, le navigateur envoie tout en utf8, mais au moment du stockage MySQL convertit en latin1 : si le caractère n'existe pas, crash. Pour éviter ça, j'ai ajouté Sql::toLatin1Entities() qui convertit à l'écriture ce qui ne tient pas en latin1 en entités HTML, comme le faisait le site en 2010.
 */
?>
