<?php
//  exit("L'int�gralit� des sites Neamar est en maintenance pour quelques minutes. Nous serons de retour pour 15h !");
  @ini_set('default_charset', 'ISO-8859-1');

  $url = parse_url(getenv("DATABASE_URL"));
  //Fichier assez utile :-)
  mysql_connect($url["host"] . ":" . $url["port"], $url["user"], $url["pass"]); // Connexion � MySQL l�g�rement s�curis�e.
  mysql_select_db("omnilogie"); // S�lection de la base de donn�es
  mysql_set_charset('latin1');
  mysql_query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))")
?>
