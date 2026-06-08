<?php
/**
* Classe External, permettant de connecter Omnilogie au reste du monde en récupérant des données distantes.
* Pour accélerer le rendu des pages, la plupart des actions sont déférée en fin de script.
*
*/
//External

class External
{
	/**
	* Récupère une ressource distante.
	* @param URL:String l'url à télécharger
	* @param OutFile:String l'emplacement où sauvegarder le fichier. Si null, on place tout dans le cache du site.
	* @param POST:array les données POST à simuler
	* @param CacheTime:int la durée de cache en secondes. 0 désactive le cache
	* @return :String le chemin vers le fichier sauvegardé.
	*/
	public static function fetch($URL,$CacheTime=0)
	{

		if(Cache::modified(__CLASS__,$URL)  + $CacheTime < time())
		{
			$DL = curl_init();
			curl_setopt($DL, CURLOPT_URL,$URL);
			curl_setopt($DL, CURLOPT_RETURNTRANSFER, 1);
			Cache::set(__CLASS__,$URL,curl_exec($DL));
			curl_close($DL);
		}

		return Cache::get(__CLASS__,$URL);

		/*

		//Déterminer l'emplacement de sauvegarde.
		if(is_null($OutFile))
			$OutFile = '/Cache/' . md5($URL);

		if($CacheTime==0 || !is_file(PATH . $OutFile) || filemtime(PATH . $OutFile) + $CacheTime < time())
		{
			$DL = curl_init();
			curl_setopt($DL, CURLOPT_URL,$URL);
			curl_setopt($DL, CURLOPT_RETURNTRANSFER, 1);
			$Retour = curl_exec($DL);
			curl_close($DL);
			file_put_contents(PATH . $OutFile,$Retour);
		}

		return $OutFile;*/
	}

	/**
	* Tweete le message demandé sur le compte défini par TWITTER_PSEUDO et TWITTER_PASSWORD.
	* NOTE: Message sera encodé en UTF-8.
	* @param Message:String le message à envoyer.
	* @return
	*/
	public static function tweet($Message)
	{
		include_once(LIB_PATH . '/twitter.php');
		postTweet($Message);
	}

	/**
	* Envoie un mail à $to.
	* L'envoi du mail se fait en fin de page, afin de ne pas ralentir l'affichage.
	* @param To:String le destinataire du message
	* @param Subject:String le sujet du mail
	* @param Message:String le message au format HTML.
	*/
	public static function mail($to,$subject,$message,$from='admin@omnilogie.fr')
	{
		// Disabled
		return;
		// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// En-têtes additionnels
		$headers .= 'From: Omnilogue <' . $from . '>' . "\r\n";//admin@omnilogie.fr est une liste de diffusion vers les administrateurs.

		register_shutdown_function("mail",$to, $subject, $message, $headers);

		$fichier = fopen(DATA_PATH . '/mail_logs', 'a'); //Ouvrir le fichier
		fputs($fichier, time() . '| ' . $to . '=>' . $subject);//Puis enregistrer les données
		fputs($fichier, "\n");
		fclose($fichier); //Et fermer le fichier

		Event::log('Envoi de mail à ' . $to . ' : ' . $subject);
	}
}
