<?php
/**
* Contrôleur : admin/arbre.php
* But : enregistrer les nouvelles catégories
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

if(isset($_POST['parent']) && !empty($_POST['enfant']))
{
	$Borne_D = intval($_POST['parent']);
	$Categorie = $_POST['enfant'];

	SQL::query('UPDATE OMNI_Categories
	SET Borne_D = Borne_D + 2
	WHERE Borne_D >= ' . $Borne_D);

	SQL::query('UPDATE OMNI_Categories
	SET Borne_G = Borne_G + 2
	WHERE Borne_G >= ' .$Borne_D);

	SQL::query('INSERT INTO OMNI_Categories
	VALUES (\'\',
	' . $Borne_D . ',
	' . ($Borne_D+1) . ',
	\'' . $Categorie . '\'
	)');

	$C['Message'] = 'Catégorie ajoutée !';

	Event::dispatch(Event::NOUVELLE_CATEGORIE);
}

//Récupérer tous les articles parus.
//Utiliser le système de pagination
$Param = Omni::buildParam(Omni::FULL_PARAM);
$Param->Select = 'Omnilogismes.ID, Omnilogismes.Statut, Omnilogismes.Titre, Omnilogismes.Accroche, Omnilogismes.Omnilogisme';

$Param->Where = '
!ISNULL(Sortie)
AND Statut="ACCEPTE"';

$omnis = Omni::get($Param);
$dumps = array();
foreach($omnis as $omni) {
	Typo::setTexte($omni->Omnilogisme);
	;

	$dump = array(
		'id' => $omni->ID,
		'text' => utf8_encode(Typo::parseLinear()),
		'titre' => utf8_encode($omni->Titre),
		'accroche' => utf8_encode($omni->Accroche)
	);
	$dumps[] = $dump;
}
echo json_encode($dumps);
exit();
