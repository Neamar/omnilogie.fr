<?php
/**
* Fichier d'�v�nement
* Event::ACCEPTE
*
* Met � jour le cache listant les statuts
*/

//Cet �venement peut �tre appel� de plusieurs endroits, il est donc "factoris�" dans un dossier � part.
Event::callGeneric(basename(__FILE__));