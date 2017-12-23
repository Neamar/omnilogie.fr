<?php
/**
* Mod�le : membres/lock.php
* But : tenter de r�cup�rer un verrou en �criture sur l'article.
* Bypasse le syst�me de vue, renvoie 'OK' si possible de r�cup�rer un lock, un message d'erreur sinon.
*/

if(!Member::is(AUTHOR,'any') && !isset($_SESSION['Membre']['Articles'][$Article->ID]))
	exit('Vous n\'avez pas le droit de verrouiller cet article.');

if(time() - Cache::modified('Lock',$TitreOmni) < 60)
{
	$Verrouilleur = Cache::get('Lock',$TitreOmni);
	if($Verrouilleur!=AUTHOR)
		exit('Cet article est en cours de modification par ' . $Verrouilleur);
}

//Personne n'est sur l'article :)
Cache::set('Lock',$TitreOmni,AUTHOR);
exit('OK');