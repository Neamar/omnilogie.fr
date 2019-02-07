<?php
/**
* But : rediriger aléatoirement vers un article.
*/


/*
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '!ISNULL(Sortie) AND Titre LIKE "%é%"';
$Param->Order = 'RAND()';
$Param->Limit = '1';

$Article = Omni::getSingle($Param);

header('Content-Type: text/plain; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé
Debug::redirect(Link::omni($Article->Titre));

*/