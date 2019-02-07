<?php
/**
* But : Fonctions génériques pour les administrateurs
*
*/
//Admin


//Infos pour la création des bannières
define('BANNER_PATH',PATH . '/images/Banner');
define('ORIGINAL_WIDTH',690);
define('ORIGINAL_HEIGHT',95);
//Combien virer de chaque côté
define('SIDE_SHRINK',150);
//Quel rapport appliquer
define('REDUCE_FACTOR',.75);

define('THUMB_WIDTH',round(REDUCE_FACTOR*(ORIGINAL_WIDTH-2*SIDE_SHRINK)));
define('THUMB_HEIGHT',round(REDUCE_FACTOR*ORIGINAL_HEIGHT));


//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Admin
{
	const ADMIN_SELECT= '
<select name="statut-%ID">
	<option value="INDETERMINE">Indéterminé</option>
<optgroup label="Oui">
	<option value="ACCEPTE">Accepté</option>
	<option value="A_CORRIGER">À corriger</option>
</optgroup>
<optgroup label="Non">
	<option value="REFUSE">Non, non et non</option>
	<option value="REVOIR_FOND">Revoir le fond</option>
	<option value="REVOIR_FORME">Revoir la forme</option>
	<option value="DEJA_TRAITE">Sujet déjà traité</option>
	<option value="MYSTIQUE">Non mystique</option>
</optgroup>
</select>
';
	static $DernierAuteur=-1;

	/**
	* Récupère la liste des articles à paraître dans l'ordre prévu.
	* Notons que cet ordre est modifié à chaque ajout d'un article en file d'attente / à chaque parution.
	* @return array<Omni>
	*/
	public static function getProchains()
	{
		//Derniers auteurs parus :
		$Derniers=SQL::query('SELECT DISTINCT Auteur FROM OMNI_Omnilogismes WHERE !ISNULL(Sortie) ORDER BY Sortie DESC LIMIT 12');

		$Param = Omni::buildParam(Omni::TRAILER_PARAM);

		$Param->Order ='(Omnilogismes.ID!=' . file_get_contents(DATA_PATH . '/priorite') . '),';

		while($Dernier=mysql_fetch_assoc($Derniers))
			$Param->Order .='(Omnilogismes.Auteur=' . $Dernier['Auteur']  . '),';

		$Param->Order .= 'Omnilogismes.ID';
		$Param->Where = 'Statut="ACCEPTE" AND ISNULL(Sortie)';
		return $Param;
	}

	/**
	* Récupère la liste des articles avec une bannière.
	* @return :array un tableau d'ID des articles avec bannières.
	*/
	public static function getBanner()
	{

		$Existants = array();
		$dh = opendir(PATH . '/images/Banner/');
		while (($file = readdir($dh)) !== false)
		{
			if(!is_dir(PATH . '/images/Banner/' . $file))
				$Existants[] = intval($file);
		}
		closedir($dh);
		rsort($Existants);

		return $Existants;
	}

	/**
	* Récupère le contrôleur de l'interface d'administration.
	* Ce contrôleur va analyser les données POST, regarder si elles correspondent au préfixe $Prefixe et si l'article concerné est bien dans $Where.
	* S'il trouve une valeur correspondante, il appellera la fonction $Callback, avec en paramètre l'objet Omni et la valeur POST.
	* @param Where:String les articles à récupérer
	* @param Prefixe:String le préfixe à surveiller sur les valeurs _POST
	* @param Callback:Function la fonction à appeler si un paramètre matche.
	* @param Order:String l'ordre dans lequel les articles doivent être affichés, la date de création par défaut.
	* @param getAgainCallback:Function si au moins un article est modifié, la fonction à appeler avant de recharger les données. Prototype : function($Where:String):String
	*/
	public static function getControleur($Where,$Prefixe,$Callback,$Order='Omnilogismes.ID',$getAgainCallback=null)
	{
		$Param = Omni::buildParam(Omni::HUGE_PARAM);

		$Param->Where = $Where;
		$Param->Order = $Order;

		$Articles = Omni::get($Param);

		//Faut-il remettre à jour $Articles ?
		$getAgain = false;

		foreach($Articles as $Article)
		{
			//Le POST-ID correspondant :
			$PID = $Prefixe . '-' . $Article->ID;
			if(isset($_POST[$PID]) || (isset($_FILES[$PID]) && $_FILES[$PID]['name']!=''))
			{
				call_user_func($Callback,$Article,(isset($_POST[$PID])?$_POST[$PID]:$_FILES[$PID]),$Prefixe);
				$getAgain = true;
			}
		}

		//Remettre à jour la liste si elle a été potentiellement modifiée :
		if($getAgain)
		{
			if(!is_null($getAgainCallback))
				$Param->Where = $getAgainCallback($Param->Where);
			$Articles = Omni::get($Param);
		}

		return $Articles;
	}

	/**
	* Affiche le formulaire dans le style "admin".
	* @param Articles:array<Omni> les articles à afficher
	* @param Callback:Function la fonction à appeler qui prend en paramètre l'article et renvoie le contenu à afficher avant le titre.
	* @param Max:int le nombre maximum d'articles à afficher.
	* @param fileForm:Boolean s'il s'agit d'un formulaire de transfert de fichier
	* @param SubmitCaption:String la chaîne à afficher sur le bouton d'envoi. Si non défini, il n'y a pas de bouton.
	* @return :String le contenu du formulaire.
	*/
	public static function getVue($Articles,$Callback,$Max=10,$Action='',$fileForm=false,$SubmitCaption='Enregistrer les modifications')
	{
		if(count($Articles)==0)
		{
			echo '<p>Module propre, tout a été traité !</p>';
			return;
		}
		$Nb = $Max;
		$Retour = '<form method="post" action="' . $Action . '" ' . ($fileForm?'enctype="multipart/form-data"':'') . '>' . "\n";
		foreach($Articles as $Article)
		{
			$Retour .= call_user_func($Callback,$Article) . Anchor::omni($Article) . '<br />' . "\n";
			$Nb--;
			if($Nb==0 && count($Articles)>$Max)
			{
				$Retour .='<header class="message">Ce module est tronqué aux ' . $Max . ' premiers articles. Effectuez les actions nécessaires pour faire apparaître la suite (' . (count($Articles)-$Max). ' restant' . (count($Articles)-$Max>1?'s':'') .').</header>';
				break;
			}
		}

		if($SubmitCaption!='')
			$Retour .= '<input type="submit" value="' . $SubmitCaption . '" />'  . "\n";

		$Retour .= "</form>\n";

		return $Retour;
	}

	public static function adminControleurCallback(Omni $Article,$Valeur,$Prefixe)
	{
		global $C;

		if($Article->Statut == $Valeur || $Valeur=='INDETERMINE')
			return;

		$ToUpdate = array
		(
			'Statut'=>$Valeur,
		);

		if(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
			Debug::fail(mysql_error());
		else
		{
			//Logger la modification
			$Article->registerModif('Statut changé vers ' . $Valeur);

			//Réussi !
			$C['Message'] = 'Modifications enregistrées !';
			$C['MessageClass'] = 'info';
		}
	}

	public static function accrocheControleurCallback(Omni $Article,$Valeur,$Prefixe)
	{
		global $C;

		if($Article->Accroche == $Valeur)
			return;

		$Valeur = preg_replace('#"([^"]+)"#U','« $1 »',$Valeur);

		$ToUpdate = array
		(
			'Accroche'=>$Valeur,
		);

		if(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
			Debug::fail(mysql_error());
		else
		{
			//Logger la modification
			$Article->registerModif(Event::ACCROCHAGE);

			//Réussi !
			$C['Message'] = 'Accroche ajoutée !';
			$C['MessageClass'] = 'info';
		}
	}

	public static function suiteControleurCallback(Omni $Article,$Valeur,$Prefixe)
	{
		global $C;

		if($Article->TitreSuivant == $Valeur)
			return;

		$Param = Omni::buildParam(Omni::TRAILER_PARAM);
		$Param->Where = 'Omnilogismes.Titre="' . $Valeur . '"';

		$ToUpdate = array
		(
			'SuiviPar'=>Omni::getSingle($Param)->ID
		);

		if(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
			Debug::fail(mysql_error());
		else
		{
			//Logger la modification
			$Article->registerModif('Ajout d\'une suite');

			//Réussi !
			$C['Message'] = 'Cet article est maintenant suivi par « ' . $Valeur . ' »';
			$C['MessageClass'] = 'info';
		}
	}

	public static function anecdoteControleurCallback(Omni $Article,$Valeur,$Prefixe)
	{
		global $C;


		if($Article->Anecdote == $Valeur)
			return;

		$ToUpdate = array('Anecdote'=>$Valeur);
		if(!empty($_POST['anecdote-source-' . $Article->ID]))
			$ToUpdate['SourceAnecdote'] = $_POST['anecdote-source-' . $Article->ID];

		if(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
			Debug::fail(mysql_error());
		else
		{
			//Logger la modification
			$Article->registerModif('Ajout d\'une anecdote');

			//Réussi !
			$C['Message'] = 'Cet article est maintenant muni de votre anecdote, merci.';
			$C['MessageClass'] = 'info';
		}
	}

	public static function messageControleurCallback(Omni $Article,$Valeur,$Prefixe)
	{
		global $C;


		if($Article->Message == $Valeur)
			return;

		$ToUpdate = array('Message'=>$Valeur);

		if(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
			Debug::fail(mysql_error());
		else
		{
			//Logger la modification
			$Article->registerModif('Ajout d\'un message');

			//Réussi !
			$C['Message'] = 'Cet article est maintenant muni de votre message, merci.';
			$C['MessageClass'] = 'info';
		}
	}

	public static function banniereControleurCallback(Omni $Article,$Valeur,$Prefixe)
	{
		global $C;

		$ID = $Article->ID;

		//Fichier correctement uploadé
		if ($_FILES['banniere-' . $ID]['error'] > 0)
		{
			$C['Message'] = 'Erreur lors de l\'envoi de la bannière.';
			return false;
		}
		//Extension valide
		$ext = substr(strrchr($_FILES['banniere-' . $ID]['name'],'.'),1);
		if (strtolower($ext)!='png')
		{
			$C['Message'] = 'Extension non autorisée pour les bannières, uniquement png.';
			return false;
		}

		$ID = $Article->ID;

		//Déplacement
		move_uploaded_file($_FILES['banniere-' . $ID]['tmp_name'],BANNER_PATH . '/Originaux/' . $ID . '.png');
		return self::banniereEffet($Article);
	}

	/**
	 * Applique les effets sur la bannière en supposant que l'original (sans effet) est présent.
	 */
	public static function banniereEffet(Omni $Article, $forceAuthorId = -1)
	{
		global $C;

		$ID = $Article->ID;

		//Charger l'image
		$Original = imagecreatefrompng(BANNER_PATH . '/Originaux/' . $ID . '.png');

		//Taille
		if (imagesx($Original)!= 690 || imagesy($Original)!=95)
		{
			$C['Message'] = 'Les dimensions de la bannière sont incorrectes ; redimensionner en 690x95.';
			return false;
		}

		/**
		* Construire la version griffée
		*/
		//Récupérer la luminosité moyenne de l'ensemble
		$Moyenne = imagecreatetruecolor(1,1);
		imagecopyresampled($Moyenne, $Original, 0, 0, 0, 0, 1,1,imagesx($Original), imagesy($Original));

		$rgb = imagecolorat($Moyenne,0,0);
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;
		$L = floor(($r + $g + $b)/3);

		imagedestroy($Moyenne);

		//Charger le bon masque
		$Masque = '/Masques/Masque-' . floor(($L)/100) . '.png';
		$Masque=imagecreatefrompng(BANNER_PATH . $Masque);

		//Appliquer le masque
		imagecopy($Original,$Masque,0,0,0,0,imagesx($Masque),imagesy($Masque));

		//Enregistrer
		imagepng($Original,BANNER_PATH . '/' . $ID . '.png');
		imagedestroy($Masque);

		/**
		* Construire la miniature
		*/
		$Thumbnail = imagecreatetruecolor(THUMB_WIDTH,THUMB_HEIGHT);

		//Réduire l'image
		imagecopyresampled($Thumbnail,$Original,0,0,SIDE_SHRINK,0,THUMB_WIDTH,THUMB_HEIGHT, ORIGINAL_WIDTH - 2*SIDE_SHRINK,ORIGINAL_HEIGHT);

		//Enregistrer
		imagepng($Thumbnail,BANNER_PATH . '/Thumbs/' . $ID . '.png');

		imagedestroy($Thumbnail);
		imagedestroy($Original);

		//Logger la modification
		$Article->registerModif(Event::BANNIERE, false, $forceAuthorId);

		return true;
	}



	public static function adminVueCallback(Omni $Article)
	{
		$Select = Admin::ADMIN_SELECT;
		$Select = str_replace(strtoupper($Article->Statut) . '">', strtoupper($Article->Statut) . '" selected="selected">',$Select);
		$Select = str_replace('%ID', $Article->ID, $Select);

		if(self::$DernierAuteur == $Article->Auteur)
			$Select = str_replace('<select', '<select style="margin-left:15px;" ',$Select);
		self::$DernierAuteur = $Article->Auteur;
		return $Select;
	}

	public static function banniereVueCallback(Omni $Article)
	{
		return '<input type="file" name="banniere-' . $Article->ID . '" id="banniere-' . $Article->ID . '" />';
	}

	public static function accrocheVueCallback(Omni $Article)
	{
		return '<input type="text" name="accroche-' . $Article->ID . '" id="accroche-' . $Article->ID . '" value="' . $Article->Accroche . '"  style="width:50%" spellcheck="true" />';
	}

	public static function suiteVueCallback(Omni $Article)
	{
		return '<input type="text" name="suite-' . $Article->ID . '" id="suite-' . $Article->ID . '" value="' . $Article->TitreSuivant . '" />';
	}

	public static function editVueCallback(Omni $Article)
	{
		return '<a href="' . str_replace('/O/','/admin/Edit/',Link::omni($Article->Titre)) . '">Modifier</a> | ';
	}

	public static function refVueCallback(Omni $Article)
	{
		return '<a href="' . str_replace('/O/','/admin/Ref/',Link::omni($Article->Titre)) . '">Référencer</a> | ';
	}

	public static function anecdoteVueCallback(Omni $Article)
	{
		return '<textarea name="anecdote-' . $Article->ID . '" id="anecdote-' . $Article->ID . '" style="width:98%" cols="25" rows="3">' . $Article->Anecdote . '</textarea><br />'
		. '<input type="text" name="anecdote-source-' . $Article->ID . '" id="anecdote-source-' . $Article->ID . '" placeholder="Source" value="' . $Article->SourceAnecdote . '" />';
	}

	public static function messageVueCallback(Omni $Article)
	{
		return '<input type="text" name="message-' . $Article->ID . '" id="message-' . $Article->ID . '" value="' . $Article->Message . '" style="width:70%" />';
	}
}