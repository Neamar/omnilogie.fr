<?php
/**
* Fichier d'évènement
* Event::SPECIAL
*
* @standalone
* @access admins
*
* Envoie un mail aux personnes ayant réservé une proposition sans l'avoir effectuée
*/
SQL::query('SET SESSION group_concat_max_len = 50000');

$Auteurs = SQL::query('SELECT GROUP_CONCAT(Description SEPARATOR "|#") AS Propositions, Auteur, Mail, Hash
FROM OMNI_Propositions
JOIN OMNI_Auteurs ON(OMNI_Auteurs.ID = ReservePar)
WHERE ISNULL(OmniID)
GROUP BY ReservePar');


while($Auteur=mysql_fetch_assoc($Auteurs))
{
	$TableauPropositions = explode('|#', $Auteur['Propositions']);
	if(count($TableauPropositions) == 1)
	{
		$Propositions = '<p>Proposition :<br />' . $TableauPropositions[0] . '</p>';
	}
	else
	{
		$Propositions = '<ul>';
		foreach($TableauPropositions as $Proposition)
		{
			$Propositions .= '<li>' . $Proposition . '</li>';
		}
		$Propositions .= '</ul>';
	}

	$Pseudo = $Auteur['Auteur'];
	$Hash = $Auteur['Hash'];
	$Texte = <<<FIN
<p>Bonjour $Pseudo !<br />
Vous êtes membre sur Omnilogie et nous vous en remercions.</p>

<p>Lors d'une de vos précédentes visites, vous avez réservé des propositions d'article, indiquant que vous vous sentiez prêt à rédiger un omnilogisme sur le sujet.</p>

$Propositions

<p>Vous pouvez commencer à <a href="http://omnilogie.fr/membres/Redaction?membre=$Hash">rédiger votre article</a> dès maintenant !<br />
<br />
Merci d'avance pour votre contribution.</p>

<p><small>Note : ce mail automatique n'est pas envoyé à intervalle régulier, mais uniquement lorsque le stock d'articles disponibles sur le site est excessivement bas.</small></p>

FIN;

	External::mail($Auteur['Mail'], 'Des propositions vous attendent ;)', $Texte);
}

