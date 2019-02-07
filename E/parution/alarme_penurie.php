<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* Envoyer un mail d'alerte en cas de pénurie
*/


//Récupérer les futurs auteurs :
$ProchainsParam = Admin::getProchains();
$ProchainsParam->Select ='DISTINCT Auteurs.Auteur';
$Prochains = Omni::get($ProchainsParam);

if(count($Prochains)<=2)
	External::mail('omni@neamar.fr','Articles à valider en urgence ! (' . count($Prochains) . ' auteur' . ((count($Prochains)>1)?'s':'') . ' en liste)','<p>Le robot OmniScient vous informe qu\'il faudrait valider des articles <strong>rapidement</strong> pour éviter de rompre la chaîne. Mais le robot n\'est qu\'un robot ; libre à vous de ne pas suivre ses conseils. <small>Enfin... à vos risques et périls</small>.</p><p>Lien : <a href="http://omnilogie.fr/admin/">Panneau d\'administration</a></p>');