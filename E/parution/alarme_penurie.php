<?php
/**
* Fichier d'�v�nement
* Event::PARUTION
*
* Envoyer un mail d'alerte en cas de p�nurie
*/


//R�cup�rer les futurs auteurs :
$ProchainsParam = Admin::getProchains();
$ProchainsParam->Select ='DISTINCT Auteurs.Auteur';
$Prochains = Omni::get($ProchainsParam);

if(count($Prochains)<=2)
	External::mail('omni@neamar.fr','Articles � valider en urgence ! (' . count($Prochains) . ' auteur' . ((count($Prochains)>1)?'s':'') . ' en liste)','<p>Le robot OmniScient vous informe qu\'il faudrait valider des articles <strong>rapidement</strong> pour �viter de rompre la cha�ne. Mais le robot n\'est qu\'un robot ; libre � vous de ne pas suivre ses conseils. <small>Enfin... � vos risques et p�rils</small>.</p><p>Lien : <a href="http://omnilogie.fr/admin/">Panneau d\'administration</a></p>');