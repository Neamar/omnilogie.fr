<?php
//  exit("L'intégralité des sites Neamar est en maintenance pour quelques minutes. Nous serons de retour pour 15h !");
error_reporting(E_ALL ^ E_DEPRECATED);

$url = parse_url(getenv("DATABASE_URL"));
//Fichier assez utile :-)
mysql_connect($url["host"] . ":" . $url["port"], $url["user"], $url["pass"]); // Connexion à MySQL légèrement sécurisée.
mysql_select_db("omnilogie"); // Sélection de la base de données
mysql_set_charset("utf8");
mysql_query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))")
?>
