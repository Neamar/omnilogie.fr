<?php
/**
* Fichier d'évènement
* Event::NOUVEAU
*
* Met à jour le cache listant les statuts
*/

//Cet évenement peut être appelé de plusieurs endroits, il est donc "factorisé" dans un dossier à part.
Event::callGeneric(basename(__FILE__));