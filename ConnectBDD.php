<?php
//  exit("L'intégralité des sites Neamar est en maintenance pour quelques minutes. Nous serons de retour pour 15h !");
  @ini_set('default_charset', 'ISO-8859-1');

  $url = parse_url(getenv("DATABASE_URL"));
  //Fichier assez utile :-)
  mysql_connect($url["host"] . ":" . $url["port"], $url["user"], $url["pass"]); // Connexion à MySQL légèrement sécurisée.
  mysql_select_db("db222432208"); // Sélection de la base de données
?>
