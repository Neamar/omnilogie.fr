<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* Envoie un mail aux auteurs d'articles toujours en brouillon.
*/

$Drafts = SQL::query("SELECT Titre, A.Auteur, Mail, Hash, DATE_FORMAT(M.Date,'%e/%m/%y') AS Redaction, DATEDIFF(NOW(),M.Date) AS Intervalle
FROM OMNI_Omnilogismes O
LEFT JOIN OMNI_Modifs M ON (O.ID = M.Reference)
LEFT JOIN OMNI_Auteurs A ON (A.ID = O.Auteur)
WHERE O.Statut = 'BROUILLON'
AND M.Modification = 'Création de l\'omnilogisme'
AND (DATEDIFF(NOW(),M.Date) % 10 = 0)
GROUP BY A.Auteur");

while($Draft = mysql_fetch_assoc($Drafts))
{
	External::mail($Draft['Mail'],'Article en brouillon : « ' . $Draft['Titre'] . ' »',
	'<p>Bonjour ' . $Draft['Auteur'] . ' !<br />
Vous avez commencé à écrire un article sur Omnilogie, et l\'avez placé en <em>brouillon</em>. Cela signifie que vous souhaitez encore y apporter des modifications avant qu\'il ne soit rendu public...<br />
Cependant, il s\'agit normalement d\'un statut temporaire ; dans votre cas cela fait maintenant ' . $Draft['Intervalle'] . ' jours que l\'article a été écrit (rédigé le ' . $Draft['Redaction'] . ').</p>

<p>Vous pouvez aller terminer la rédaction de l\'article  en suivant ce lien qui vous connectera automatiquement : «&nbsp;<a href="http://omnilogie.fr/membres/Edit/' . urlencode($Draft['Titre']) . '?membre=' . $Draft['Hash'] . '">' . $Draft['Titre'] . '</a>&nbsp;».<br />
Merci d\'avance !</p>');
}